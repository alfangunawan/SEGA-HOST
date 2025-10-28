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
    public function index(Request $request)
    {
        $query = Rental::with(['unit'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $rentals = $query->paginate(10);

        // Calculate stats
        $stats = [
            'total' => Rental::where('user_id', Auth::id())->count(),
            'active' => Rental::where('user_id', Auth::id())->where('status', 'active')->count(),
            'pending' => Rental::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'overdue' => Rental::where('user_id', Auth::id())->where('status', 'overdue')->count(),
        ];

        return view('User.rental.index', compact('rentals', 'stats'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(Request $request)
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
            'rental_days' => ['required', 'integer', 'min:1', 'max:5'],
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
                ->with('error', 'An error occurred while processing your rental request. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental)
    {
        // Ensure user can only see their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        $rental->load('unit');

        return view('User.rental.show', compact('rental'));
    }

    /**
     * Cancel a pending rental.
     */
    public function cancel(Rental $rental)
    {
        // Ensure user can only cancel their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only cancel pending rentals
        if ($rental->status !== 'pending') {
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
                    'status' => 'cancelled',
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
    public function extend(Rental $rental, Request $request)
    {
        // Ensure user can only extend their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only extend active rentals
        if ($rental->status !== 'active') {
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
     * Return a rented unit (for admin use).
     */
    public function return(Rental $rental)
    {
        // This would typically be admin-only functionality
        if ($rental->status !== 'active') {
            return back()->with('error', 'Only active rentals can be returned.');
        }

        $rental->update(['status' => 'completed']);
        $rental->unit->update(['status' => 'available']);

        return back()->with('success', 'Rental marked as completed.');
    }

    /**
     * Early return of an active rental by user
     */
    public function earlyReturn(Rental $rental, Request $request)
    {
        // Ensure user can only return their own rentals
        if ($rental->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to rental.');
        }

        // Can only return active rentals
        if ($rental->status !== 'active') {
            return back()->with('error', 'Only active rentals can be returned early.');
        }

        $validated = $request->validate([
            'return_reason' => ['required', 'string', 'max:500'],
            'confirm_return' => ['required', 'accepted'],
        ]);

        // Calculate refund (if any) - based on unused days
        $today = Carbon::today();
        $unusedDays = $today->diffInDays($rental->end_date, false);
        $refundAmount = 0;

        if ($unusedDays > 0) {
            // Refund 80% of unused days (20% as processing fee)
            $refundAmount = ($rental->unit->price_per_day * $unusedDays) * 0.8;
        }

        try {
            DB::transaction(function () use ($rental, $validated, $today, $refundAmount) {
                $lockedRental = Rental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
                $lockedUnit = Unit::query()->whereKey($lockedRental->unit_id)->lockForUpdate()->first();
                $lockedUser = User::query()->whereKey($lockedRental->user_id)->lockForUpdate()->first();

                $note = ($lockedRental->notes ? $lockedRental->notes . "\n\n" : '') .
                    'Early return on ' . $today->format('Y-m-d') . '. Reason: ' . $validated['return_reason'];

                $lockedRental->update([
                    'status' => 'returned_early',
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
}