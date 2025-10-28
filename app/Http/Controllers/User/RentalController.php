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
        // Auto-update overdue rentals before displaying
        $this->updateOverdueRentals();

        $query = Rental::with(['unit'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            
            // Group returned_early with completed for "Selesai" filter
            if ($status === Rental::STATUS_COMPLETED) {
                $query->whereIn('status', Rental::getCompletedStatuses());
            } else {
                $query->where('status', $status);
            }
        }

        $rentals = $query->paginate(10);

        // Calculate stats using consistent grouping
        $userId = Auth::id();
        $stats = [
            'total' => Rental::where('user_id', $userId)->count(),
            'active' => Rental::where('user_id', $userId)->where('status', Rental::STATUS_ACTIVE)->count(),
            'pending' => Rental::where('user_id', $userId)->where('status', Rental::STATUS_PENDING)->count(),
            'overdue' => Rental::where('user_id', $userId)->where('status', Rental::STATUS_OVERDUE)->count(),
            'completed' => Rental::where('user_id', $userId)
                                ->whereIn('status', Rental::getCompletedStatuses())
                                ->count(),
        ];

        return view('User.rental.index', compact('rentals', 'stats'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Check if user can rent more servers
        if (!$user->canRentMoreServers()) {
            return redirect()->route('products.index')
                ->with('error', 'You have reached the maximum limit of 2 active server rentals.');
        }

        $unitId = $request->query('unit');
        $unit = null;

        if ($unitId) {
            $unit = Unit::where('id', $unitId)
                ->where('status', 'available')
                ->first();

            if (!$unit) {
                return redirect()->route('products.index')
                    ->with('error', 'Selected server is not available.');
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
                ->with('error', 'You have reached the maximum limit of 2 active server rentals.');
        }

        $validated = $request->validate([
            'unit_id' => ['required', 'exists:units,id'],
            'rental_days' => ['required', 'integer', 'min:1', 'max:' . Rental::MAX_RENTAL_DAYS],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);

        if ($unit->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Selected server is no longer available.')
                ->withInput();
        }

        $startDate = Carbon::parse($validated['start_date']);
        $rentalDays = (int) $validated['rental_days'];
        $endDate = $startDate->copy()->addDays($rentalDays);
        $totalCost = round($unit->price_per_day * $rentalDays, 2);

        if (!$user->hasSufficientBalance($totalCost)) {
            return redirect()->back()
                ->with('error', 'Saldo Anda tidak mencukupi untuk menyewa server ini.')
                ->withInput();
        }

        try {
            $rental = DB::transaction(function () use ($user, $unit, $startDate, $endDate, $totalCost) {
                $lockedUnit = Unit::query()->whereKey($unit->id)->lockForUpdate()->first();

                if (!$lockedUnit || $lockedUnit->status !== 'available') {
                    throw ValidationException::withMessages([
                        'unit_id' => __('Selected server is no longer available.'),
                    ]);
                }

                $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->first();

                if (!$lockedUser || !$lockedUser->hasSufficientBalance($totalCost)) {
                    throw ValidationException::withMessages([
                        'balance' => __('Saldo Anda tidak mencukupi untuk menyewa server ini.'),
                    ]);
                }

                $rental = Rental::create([
                    'user_id' => $lockedUser->id,
                    'unit_id' => $lockedUnit->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => Rental::STATUS_DEFAULT,
                    'total_cost' => $totalCost,
                ]);

                $lockedUnit->update(['status' => 'rented']);

                $lockedUser->forceFill([
                    'balance' => round(((float) $lockedUser->balance) - $totalCost, 2),
                ])->save();

                return $rental;
            });

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Server rental request submitted successfully!');
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first();

            return redirect()->back()
                ->withErrors($exception->errors())
                ->with('error', $message)
                ->withInput();
        } catch (\Throwable $e) {
            Log::error('Rental creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while processing your rental request.')
                ->withInput();
        }
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental): View
    {
        // Ensure user can only see their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Auto-update rental status if it's overdue
        if ($rental->status === Rental::STATUS_ACTIVE && now() > $rental->end_date) {
            try {
                $penalty = $rental->calculatePenalty();
                $rental->update([
                    'status' => Rental::STATUS_OVERDUE,
                    'penalty_cost' => $penalty
                ]);
                // Refresh the model to get updated values
                $rental->refresh();
            } catch (\Throwable $e) {
                Log::error('Auto-update overdue rental failed: ' . $e->getMessage());
            }
        }

        $rental->load('unit');

        return view('User.rental.show', compact('rental'));
    }

    /**
     * Cancel a pending rental.
     */
    public function cancel(Rental $rental): RedirectResponse
    {
        // Ensure user can only cancel their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only cancel pending rentals
        if ($rental->status !== Rental::STATUS_PENDING) {
            return back()->with('error', 'Only pending rentals can be cancelled.');
        }

        try {
            DB::transaction(function () use ($rental) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUnit = Unit::query()->whereKey($lockedRental->unit_id)->lockForUpdate()->first();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                $note = ($lockedRental->notes ? $lockedRental->notes . "\n\n" : '') .
                    'Cancelled by user on ' . now()->format('Y-m-d H:i:s');

                $lockedRental->update([
                    'status' => Rental::STATUS_CANCELLED,
                    'notes' => $note,
                    'penalty_cost' => null,
                ]);

                if ($lockedUnit) {
                    $lockedUnit->update(['status' => 'available']);
                }

                if ($lockedUser) {
                    $lockedUser->forceFill([
                        'balance' => round(((float) $lockedUser->balance) + (float) $lockedRental->total_cost, 2),
                    ])->save();
                }
            });

            return redirect()->route('rentals.index')
                ->with('success', 'Rental has been cancelled successfully. Dana telah dikembalikan ke saldo Anda.');
        } catch (\Throwable $e) {
            Log::error('Rental cancellation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Tidak dapat membatalkan penyewaan saat ini. Silakan coba lagi.');
        }
    }

    /**
     * Extend a rental period.
     */
    public function extend(Rental $rental, Request $request): RedirectResponse
    {
        // Ensure user can only extend their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only extend active rentals
        if ($rental->status !== Rental::STATUS_ACTIVE) {
            return back()->with('error', 'Only active rentals can be extended.');
        }

        $validated = $request->validate([
            'extend_days' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        $extendDays = (int) $validated['extend_days'];
        $additionalCost = round($rental->unit->price_per_day * $extendDays, 2);

        if (!$request->user()->hasSufficientBalance($additionalCost)) {
            return back()->with('error', 'Saldo Anda tidak mencukupi untuk perpanjangan ini.');
        }

        try {
            DB::transaction(function () use ($rental, $extendDays, $additionalCost) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                if (!$lockedUser || !$lockedUser->hasSufficientBalance($additionalCost)) {
                    throw ValidationException::withMessages([
                        'balance' => __('Saldo Anda tidak mencukupi untuk perpanjangan ini.'),
                    ]);
                }

                $newEndDate = Carbon::parse($lockedRental->end_date)->addDays($extendDays);

                $lockedRental->update([
                    'end_date' => $newEndDate,
                    'total_cost' => round(((float) $lockedRental->total_cost) + $additionalCost, 2),
                ]);

                $lockedUser->forceFill([
                    'balance' => round(((float) $lockedUser->balance) - $additionalCost, 2),
                ])->save();
            });

            return back()->with('success', "Rental extended by {$extendDays} days.");
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first();

            return back()->withErrors($exception->errors())->with('error', $message);
        } catch (\Throwable $e) {
            Log::error('Rental extension failed: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memperpanjang penyewaan. Silakan coba lagi.');
        }
    }

    /**
     * Early return of an active rental by user
     */
    public function earlyReturn(Rental $rental, Request $request): RedirectResponse
    {
        // Ensure user can only return their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only return active rentals
        if ($rental->status !== Rental::STATUS_ACTIVE) {
            return back()->with('error', 'Only active rentals can be returned early.');
        }

        $validated = $request->validate([
            'return_reason' => ['required', 'string', 'max:500'],
            'confirm_return' => ['required', 'accepted'],
        ]);

        // Calculate refund (if any) - based on unused days
        $today = Carbon::today();
        $totalDays = $rental->start_date->diffInDays($rental->end_date) + 1;
        $usedDays = $rental->start_date->diffInDays($today) + 1;
        $unusedDays = max(0, $totalDays - $usedDays);
        $refundAmount = 0;

        if ($unusedDays > 0) {
            // Refund percentage of unused days (processing fee deducted)
            $refundAmount = ($rental->unit->price_per_day * $unusedDays) * Rental::REFUND_PERCENTAGE;
        }

        try {
            DB::transaction(function () use ($rental, $validated, $today, $refundAmount) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUnit = Unit::query()->whereKey($lockedRental->unit_id)->lockForUpdate()->first();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                $note = ($lockedRental->notes ? $lockedRental->notes . "\n\n" : '') .
                    'Early return on ' . $today->format('Y-m-d') . '. Reason: ' . $validated['return_reason'];

                $lockedRental->update([
                    'status' => Rental::STATUS_RETURNED_EARLY,
                    'end_date' => $today,
                    'notes' => $note,
                    'penalty_cost' => $refundAmount > 0 ? -$refundAmount : null,
                ]);

                if ($lockedUnit) {
                    $lockedUnit->update(['status' => 'available']);
                }

                if ($refundAmount > 0 && $lockedUser) {
                    $lockedUser->forceFill([
                        'balance' => round(((float) $lockedUser->balance) + $refundAmount, 2),
                    ])->save();
                }
            });

            $message = 'Server returned successfully!';
            if ($refundAmount > 0) {
                $message .= ' Refund sebesar Rp ' . number_format($refundAmount, 0, ',', '.') . ' telah dikreditkan ke saldo Anda.';
            }

            return redirect()->route('rentals.index')->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Early return failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Pengembalian lebih awal gagal diproses. Silakan coba lagi.');
        }
    }

    /**
     * Return overdue rental with penalty deduction from balance
     */
    public function returnWithPenalty(Rental $rental, Request $request): RedirectResponse
    {
        // Ensure user can only return their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only return overdue rentals
        if ($rental->status !== Rental::STATUS_OVERDUE) {
            return back()->with('error', 'Only overdue rentals can be returned with penalty.');
        }

        $validated = $request->validate([
            'return_reason' => ['required', 'string', 'max:500'],
            'confirm_penalty' => ['required', 'accepted'],
        ]);

        try {
            DB::transaction(function () use ($rental, $validated) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUnit = Unit::query()->whereKey($lockedRental->unit_id)->lockForUpdate()->first();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                // Calculate penalty amount
                $penaltyAmount = $lockedRental->penalty_cost > 0 ? $lockedRental->penalty_cost : $lockedRental->calculatePenalty();

                // Deduct penalty from user balance
                if ($lockedUser && $penaltyAmount > 0) {
                    $currentBalance = (float) $lockedUser->balance;
                    $newBalance = round($currentBalance - $penaltyAmount, 2);
                    
                    Log::info("Balance deduction - User: {$lockedUser->id}, Current: {$currentBalance}, Penalty: {$penaltyAmount}, New: {$newBalance}");
                    
                    $lockedUser->forceFill([
                        'balance' => $newBalance,
                    ])->save();
                    
                    // Verify balance was updated
                    $lockedUser->refresh();
                    Log::info("Balance after update: " . $lockedUser->balance);
                }

                // Update rental status
                $note = ($lockedRental->notes ? $lockedRental->notes . "\n\n" : '') .
                    'Returned with penalty on ' . now()->format('Y-m-d H:i:s') . 
                    '. Reason: ' . $validated['return_reason'] .
                    '. Penalty: Rp ' . number_format($penaltyAmount, 0, ',', '.');

                $lockedRental->update([
                    'status' => Rental::STATUS_COMPLETED,
                    'notes' => $note,
                    'penalty_cost' => $penaltyAmount,
                    'final_settlement' => $lockedRental->total_cost + $penaltyAmount,
                ]);

                // Make unit available again
                if ($lockedUnit) {
                    $lockedUnit->update(['status' => 'available']);
                }
            });

            return redirect()->route('rentals.index')->with('success', 
                'Server berhasil dikembalikan! Denda sebesar Rp ' . number_format($rental->calculatePenalty(), 0, ',', '.') . ' telah dipotong dari saldo Anda.'
            );
        } catch (\Throwable $e) {
            Log::error('Penalty return failed: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Pengembalian server gagal diproses. Silakan coba lagi.');
        }
    }

    /**
     * Show payment page
     */
    public function payment(Rental $rental): View|RedirectResponse
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        if ($rental->is_paid) {
            return redirect()->route('rentals.show', $rental)
                ->with('info', 'This rental has already been paid.');
        }

        return view('User.rental.payment', compact('rental'));
    }

    /**
     * Process payment
     */
    public function processPayment(Rental $rental, Request $request): RedirectResponse
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if already paid
        if ($rental->is_paid) {
            return redirect()->route('rentals.show', $rental)
                ->with('info', 'This rental has already been paid.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:bank_transfer,credit_card,e_wallet'],
            'payment_reference' => ['required', 'string', 'max:100'],
        ]);

        try {
            DB::transaction(function () use ($rental, $validated) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                // Check if user has sufficient balance
                if (!$lockedUser || !$lockedUser->hasSufficientBalance($lockedRental->total_cost)) {
                    throw new \Exception('Saldo Anda tidak mencukupi untuk membayar rental ini.');
                }

                // Deduct payment from user balance
                $newBalance = round(((float) $lockedUser->balance) - $lockedRental->total_cost, 2);
                $lockedUser->forceFill([
                    'balance' => $newBalance,
                ])->save();

                // Process payment
                $lockedRental->update([
                    'is_paid' => true,
                    'payment_date' => now(),
                    'payment_method' => $validated['payment_method'],
                    'payment_reference' => $validated['payment_reference'],
                    'status' => Rental::STATUS_ACTIVE, // Aktivasi setelah pembayaran
                ]);
            });

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Pembayaran berhasil! Server rental Anda sekarang aktif. Saldo Anda telah dipotong sebesar Rp ' . number_format($rental->total_cost, 0, ',', '.') . '.');
        } catch (\Throwable $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Auto-update rentals that have become overdue
     */
    private function updateOverdueRentals(): void
    {
        $overdueRentals = Rental::where('user_id', Auth::id())
            ->where('status', Rental::STATUS_ACTIVE)
            ->where('end_date', '<', now())
            ->get();

        foreach ($overdueRentals as $rental) {
            try {
                $penalty = $rental->calculatePenalty();
                $rental->update([
                    'status' => Rental::STATUS_OVERDUE,
                    'penalty_cost' => $penalty
                ]);
            } catch (\Throwable $e) {
                Log::error('Auto-update overdue rental failed for rental ' . $rental->id . ': ' . $e->getMessage());
            }
        }
    }
}