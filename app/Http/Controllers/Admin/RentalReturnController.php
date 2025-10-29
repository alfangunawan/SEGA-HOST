<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RentalReturnController extends Controller
{
    /**
     * Display pending rental return requests.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $rentals = Rental::query()
            ->with(['user', 'unit'])
            ->where('status', Rental::STATUS_PENDING)
            ->whereNotNull('previous_status')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('unit', fn($unitQuery) => $unitQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.rental-returns.index', compact('rentals', 'search'));
    }

    /**
     * Approve a pending rental return request.
     */
    public function approve(Request $request, Rental $rental): RedirectResponse
    {
        if ($rental->status !== Rental::STATUS_PENDING) {
            return back()->with('status', __('Status peminjaman sudah diperbarui sebelumnya.'));
        }

        $rental->loadMissing(['unit', 'user']);

        DB::transaction(function () use ($rental, $request) {
            $settlement = $rental->final_settlement ?? 0;

            if ($settlement < 0) {
                $rental->user?->increment('balance', abs($settlement));
            } elseif ($settlement > 0) {
                $rental->user?->decrement('balance', $settlement);
            }

            $note = $this->appendNote($rental->notes, __('Pengembalian disetujui oleh :name.', [
                'name' => $request->user()?->name ?? __('Admin'),
            ]));

            $rental->update([
                'status' => Rental::STATUS_COMPLETED,
                'previous_status' => null,
                'notes' => $note,
                'penalty_cost' => $settlement > 0 ? $settlement : null,
                'final_settlement' => null,
            ]);

            $rental->unit?->update(['status' => 'available']);
        });

        return redirect()
            ->route('admin.return-requests.index')
            ->with('status', __('Pengembalian berhasil disetujui. Unit tersedia kembali.'));
    }

    /**
     * Reject a pending rental return request.
     */
    public function reject(Request $request, Rental $rental): RedirectResponse
    {
        if ($rental->status !== Rental::STATUS_PENDING) {
            return back()->with('status', __('Status peminjaman sudah diperbarui sebelumnya.'));
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $note = $this->appendNote($rental->notes, __('Pengembalian ditolak: :reason', [
            'reason' => $validated['reason'],
        ]));

        $rental->update([
            'status' => $rental->previous_status ?? Rental::STATUS_ACTIVE,
            'previous_status' => null,
            'final_settlement' => null,
            'notes' => $note,
        ]);

        return redirect()
            ->route('admin.return-requests.index')
            ->with('status', __('Pengembalian ditolak dan peminjaman tetap aktif.'));
    }

    private function appendNote(?string $existing, string $newNote): string
    {
        if (blank($existing)) {
            return $newNote;
        }

        return $existing . "\n\n" . $newNote;
    }
}
