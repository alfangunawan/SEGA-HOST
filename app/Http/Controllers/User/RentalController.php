<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RentalController extends Controller
{
    /**
     * Display a listing of rentals for the authenticated user.
     */
    public function index(Request $request): View
    {
        $this->updateOverdueRentals();

        $query = Rental::with(['unit'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            $status = $request->status;
            $query->where('status', $status);
        }

        $rentals = $query->paginate(10);
        return view('User.rental.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (!$user->canRentMoreServers()) {
            return redirect()->route('products.index')
                ->with('error', 'Anda sudah mencapai batas maksimal 2 server aktif.');
        }

        $unitId = $request->query('unit');
        $unit = null;

        if ($unitId) {
            $unit = Unit::where('id', $unitId)
                ->where('status', 'available')
                ->first();

            if (!$unit) {
                return redirect()->route('products.index')
                    ->with('error', 'Server yang dipilih tidak tersedia.');
            }
        }

        return view('User.rental.create', compact('unit'));
    }

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->canRentMoreServers()) {
            return redirect()->route('products.index')
                ->with('error', 'Anda sudah mencapai batas maksimal 2 server aktif.');
        }

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'rental_days' => 'required|integer|min:1|max:5',
            'start_date' => 'required|date|after_or_equal:today',
            'terms_accepted' => 'required|accepted',
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);

        if ($unit->status !== 'available') {
            return back()->with('error', 'Server tidak lagi tersedia.')->withInput();
        }

        $startDate = Carbon::parse($validated['start_date']);
        $rentalDays = (int) $validated['rental_days'];
        $endDate = $startDate->copy()->addDays($rentalDays);
        $totalCost = $unit->price_per_day * $rentalDays;

        if (!$user->hasSufficientBalance($totalCost)) {
            return back()->with('error', 'Saldo tidak mencukupi.')->withInput();
        }

        try {
            $rental = DB::transaction(function () use ($user, $unit, $startDate, $endDate, $totalCost) {
                // Lock resources
                $lockedUnit = Unit::lockForUpdate()->find($unit->id);
                $lockedUser = User::lockForUpdate()->find($user->id);

                if (!$lockedUnit || $lockedUnit->status !== 'available') {
                    throw new \Exception('Server tidak lagi tersedia.');
                }

                if (!$lockedUser->hasSufficientBalance($totalCost)) {
                    throw new \Exception('Saldo tidak mencukupi.');
                }

                // Create rental
                $rental = Rental::create([
                    'user_id' => $lockedUser->id,
                    'unit_id' => $lockedUnit->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => Rental::STATUS_ACTIVE, // Langsung aktif setelah pembayaran
                    'total_cost' => $totalCost,
                ]);

                // Update unit and user
                $lockedUnit->update(['status' => 'rented']);
                $lockedUser->decrement('balance', $totalCost);

                return $rental;
            });

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Server berhasil disewa! Pembayaran telah dipotong dari saldo Anda.');

        } catch (\Exception $e) {
            Log::error('Rental creation failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental): View
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        // Auto-update if overdue
        if ($rental->status === Rental::STATUS_ACTIVE && now() > $rental->end_date) {
            $rental->update([
                'status' => Rental::STATUS_OVERDUE,
                'penalty_cost' => $rental->calculatePenalty()
            ]);
            $rental->refresh();
        }

        $rental->load('unit');
        return view('User.rental.show', compact('rental'));
    }

    /**
     * Cancel a pending rental.
     */
    public function cancel(Rental $rental): RedirectResponse
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        if ($rental->status !== Rental::STATUS_PENDING) {
            return back()->with('error', 'Hanya rental pending yang bisa dibatalkan.');
        }

        try {
            DB::transaction(function () use ($rental) {
                if ($rental->previous_status) {
                    $rental->update([
                        'status' => $rental->previous_status,
                        'previous_status' => null,
                        'final_settlement' => null,
                    ]);

                    $note = $this->appendNote($rental->notes, 'Pengajuan pengembalian dibatalkan oleh penyewa.');
                    $rental->update(['notes' => $note]);
                } else {
                    $rental->update([
                        'status' => Rental::STATUS_COMPLETED,
                        'notes' => $this->appendNote($rental->notes, 'Peminjaman dibatalkan sebelum dimulai oleh penyewa.'),
                    ]);

                    $rental->unit->update(['status' => 'available']);
                    $rental->user->increment('balance', $rental->total_cost);
                }
            });

            return redirect()->route('rentals.index')
                ->with('success', 'Permintaan dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Rental cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan permintaan.');
        }
    }

    /**
     * Early return of an active rental
     */
    public function earlyReturn(Rental $rental, Request $request): RedirectResponse
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        if ($rental->status !== Rental::STATUS_ACTIVE) {
            return back()->with('error', 'Hanya rental aktif yang bisa dikembalikan lebih awal.');
        }

        $validated = $request->validate([
            'return_reason' => 'required|string|max:500',
            'confirm_return' => 'required|accepted',
        ]);

        // Calculate refund (80% of unused days)
        $today = Carbon::today();
        $start = Carbon::parse($rental->start_date);
        $end = Carbon::parse($rental->end_date);
        $totalDays = $start->diffInDays($end) + 1;
        $usedDays = $start->diffInDays($today) + 1;
        $unusedDays = max(0, $totalDays - $usedDays);
        $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays * 0.8) : 0;

        try {
            DB::transaction(function () use ($rental, $validated, $today, $refundAmount) {
                $note = $this->appendNote($rental->notes, 'Pengajuan pengembalian lebih awal: ' . $validated['return_reason']);

                $rental->update([
                    'status' => Rental::STATUS_PENDING,
                    'previous_status' => Rental::STATUS_ACTIVE,
                    'end_date' => $today,
                    'notes' => $note,
                    'final_settlement' => $refundAmount > 0 ? -$refundAmount : null,
                ]);
            });

            $message = 'Pengajuan pengembalian telah dikirim dan menunggu persetujuan admin.';
            return redirect()->route('rentals.show', $rental)->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Early return failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengajukan pengembalian.');
        }
    }

    /**
     * Return overdue rental with penalty
     */
    public function returnWithPenalty(Rental $rental, Request $request): RedirectResponse
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        if ($rental->status !== Rental::STATUS_OVERDUE) {
            return back()->with('error', 'Hanya rental terlambat yang bisa dikembalikan dengan denda.');
        }

        $validated = $request->validate([
            'return_reason' => 'required|string|max:500',
            'confirm_penalty' => 'required|accepted',
        ]);

        try {
            DB::transaction(function () use ($rental, $validated) {
                $penaltyAmount = $rental->calculatePenalty();
                $note = $this->appendNote($rental->notes, 'Pengajuan pengembalian dengan denda: ' . $validated['return_reason']);

                $rental->update([
                    'status' => Rental::STATUS_PENDING,
                    'previous_status' => Rental::STATUS_OVERDUE,
                    'notes' => $note,
                    'final_settlement' => $penaltyAmount > 0 ? $penaltyAmount : null,
                ]);
            });

            return redirect()->route('rentals.show', $rental)->with(
                'success',
                'Pengajuan pengembalian dengan denda dikirim. Menunggu persetujuan admin.'
            );
        } catch (\Exception $e) {
            Log::error('Penalty return failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengajukan pengembalian.');
        }
    }

    /**
     * Auto-update overdue rentals
     */
    private function updateOverdueRentals(): void
    {
        Rental::where('user_id', Auth::id())
            ->where('status', Rental::STATUS_ACTIVE)
            ->where('end_date', '<', now())
            ->get()
            ->each(function ($rental) {
                try {
                    $rental->update([
                        'status' => Rental::STATUS_OVERDUE,
                        'penalty_cost' => $rental->calculatePenalty()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Auto-update overdue failed: ' . $e->getMessage());
                }
            });
    }

    private function appendNote(?string $existing, string $message): string
    {
        if (blank($existing)) {
            return $message;
        }

        return $existing . "\n\n" . $message;
    }
}