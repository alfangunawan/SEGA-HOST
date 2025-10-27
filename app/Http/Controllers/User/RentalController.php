<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
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
        try {
            $user = $request->user();
            
            // Validate rental limit
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
            
            // Check if unit is still available
            if ($unit->status !== 'available') {
                return redirect()->back()
                    ->with('error', 'Selected server is no longer available.')
                    ->withInput();
            }

            $startDate = Carbon::parse($validated['start_date']);
            
            // Pastikan rental_days adalah integer
            $rentalDays = (int) $validated['rental_days'];
            $endDate = $startDate->copy()->addDays($rentalDays);
            $totalCost = $unit->price_per_day * $rentalDays;

            // Create rental
            $rental = Rental::create([
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending',
                'total_cost' => $totalCost,
            ]);

            // Update unit status
            $unit->update(['status' => 'rented']);

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Server rental request submitted successfully!');
                
        } catch (\Exception $e) {
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

        $rental->update([
            'status' => 'cancelled',
            'notes' => ($rental->notes ? $rental->notes . "\n\n" : '') . 
                       "Cancelled by user on " . now()->format('Y-m-d H:i:s')
        ]);
        
        // Make unit available again
        $rental->unit->update(['status' => 'available']);

        return redirect()->route('rentals.index')
            ->with('success', 'Rental has been cancelled successfully. No charges will apply.');
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

        // Pastikan extend_days adalah integer
        $extendDays = (int) $validated['extend_days'];
        $additionalCost = $rental->unit->price_per_day * $extendDays;
        
        // Gunakan copy() untuk menghindari mutasi object asli
        $newEndDate = $rental->end_date->copy()->addDays($extendDays);
        
        $rental->update([
            'end_date' => $newEndDate,
            'total_cost' => $rental->total_cost + $additionalCost,
        ]);

        return back()->with('success', "Rental extended by {$extendDays} days.");
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

        // Update rental
        $rental->update([
            'status' => 'returned_early',
            'end_date' => $today,
            'notes' => ($rental->notes ? $rental->notes . "\n\n" : '') . 
                       "Early return on " . $today->format('Y-m-d') . ". Reason: " . $validated['return_reason'],
            'penalty_cost' => -$refundAmount, // Negative value indicates refund
        ]);

        // Make unit available again
        $rental->unit->update(['status' => 'available']);

        $message = 'Server returned successfully!';
        if ($refundAmount > 0) {
            $message .= ' Refund of Rp ' . number_format($refundAmount, 0, ',', '.') . ' will be processed within 3-5 business days.';
        }

        return redirect()->route('rentals.index')->with('success', $message);
    }
}