<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RentalController extends Controller
{
    private const MAX_LOAN_DAYS = 5;

    /**
     * Display a listing of rentals.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $rentals = Rental::query()
            ->with(['user', 'unit'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('unit', fn($unitQuery) => $unitQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.rentals.index', compact('rentals', 'search', 'status'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        $units = Unit::orderBy('name')->pluck('name', 'id');

        return view('admin.rentals.create', compact('users', 'units'));
    }

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        $payload = $this->buildRentalPayload($validated);

        Rental::create($payload);

        return redirect()->route('admin.rentals.index')
            ->with('status', __('Peminjaman berhasil ditambahkan.'));
    }

    /**
     * Show the form for editing the specified rental.
     */
    public function edit(Rental $rental): View
    {
        $rental->loadMissing(['unit']);

        $users = User::orderBy('name')->pluck('name', 'id');
        $units = Unit::orderBy('name')->pluck('name', 'id');

        return view('admin.rentals.edit', compact('rental', 'users', 'units'));
    }

    /**
     * Update the specified rental in storage.
     */
    public function update(Request $request, Rental $rental): RedirectResponse
    {
        $validated = $this->validatedData($request);

        $payload = $this->buildRentalPayload($validated, $rental);

        $rental->update($payload);

        return redirect()->route('admin.rentals.index')
            ->with('status', __('Peminjaman berhasil diperbarui.'));
    }

    /**
     * Remove the specified rental from storage.
     */
    public function destroy(Rental $rental): RedirectResponse
    {
        $rental->delete();

        return redirect()->route('admin.rentals.index')
            ->with('status', __('Peminjaman berhasil dihapus.'));
    }

    /**
     * Approve a pending rental.
     */
    public function approve(Rental $rental): RedirectResponse
    {
        if ($rental->status !== 'pending') {
            return back()->with('status', __('Status peminjaman sudah diperbarui sebelumnya.'));
        }

        $rental->update(['status' => 'active']);

        return back()->with('status', __('Peminjaman telah disetujui dan kini sedang berjalan.'));
    }

    /**
     * Reject a pending rental.
     */
    public function reject(Rental $rental): RedirectResponse
    {
        if ($rental->status !== 'pending') {
            return back()->with('status', __('Status peminjaman sudah diperbarui sebelumnya.'));
        }

        $rental->loadMissing(['unit', 'user']);

        $rental->update([
            'status' => Rental::STATUS_COMPLETED,
            'notes' => $this->appendNote($rental->notes, __('Peminjaman dibatalkan oleh admin.')),
        ]);

        if ($rental->relationLoaded('unit')) {
            $rental->unit?->update(['status' => 'available']);
        } else {
            $rental->unit()->update(['status' => 'available']);
        }

        $rental->user?->increment('balance', $rental->total_cost);

        return back()->with('status', __('Peminjaman telah dibatalkan dan dana dikembalikan.'));
    }

    private function appendNote(?string $existing, string $message): string
    {
        if (blank($existing)) {
            return $message;
        }

        return $existing . "\n\n" . $message;
    }

    /**
     * Validate request data for creating/updating a rental.
     */
    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'start_date' => ['required', 'date'],
            'status' => ['sometimes', 'nullable', Rule::in(Rental::availableStatuses())],
        ]);
    }

    /**
     * Build the payload for storing or updating a rental with derived values.
     */
    protected function buildRentalPayload(array $data, ?Rental $rental = null): array
    {
        $unit = Unit::findOrFail($data['unit_id']);
        $startDate = Carbon::parse($data['start_date']);
        $pricePerDay = (float) ($unit->price_per_day ?? 0);

        $existingStart = null;
        $existingEnd = null;

        if ($rental) {
            $existingStart = $rental->start_date ? Carbon::parse($rental->start_date) : null;
            $existingEnd = $rental->end_date ? Carbon::parse($rental->end_date) : null;
        }
        $existingDuration = null;

        if ($existingStart && $existingEnd) {
            $existingDuration = max($existingStart->diffInDays($existingEnd), 1);
        }

        $durationDays = $existingDuration ?? self::MAX_LOAN_DAYS;

        $unitChanged = $rental && $unit->getKey() !== $rental->unit_id;
        $startChanged = $existingStart ? !$startDate->equalTo($existingStart) : false;
        $shouldReuseExistingSchedule = $rental && !$unitChanged && !$startChanged;

        if ($shouldReuseExistingSchedule && $existingEnd) {
            $endDate = $existingEnd;
            $totalCost = (float) $rental->total_cost;
        } else {
            $endDate = $startDate->copy()->addDays($durationDays);
            $totalCost = max(round($pricePerDay * $durationDays, 2), 0);
        }

        return array_merge($data, [
            'end_date' => $endDate,
            'status' => $data['status'] ?? $rental?->status ?? Rental::STATUS_DEFAULT,
            'total_cost' => $totalCost,
        ]);
    }
}
