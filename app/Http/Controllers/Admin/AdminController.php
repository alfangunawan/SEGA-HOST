<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Get active rentals count
        $activeRentals = Rental::where('status', Rental::STATUS_ACTIVE)->count();

        // Get late rentals count
        $lateRentals = Rental::where('status', Rental::STATUS_OVERDUE)->count();

        // Get completed rentals today
        $completedToday = Rental::where('status', Rental::STATUS_COMPLETED)
            ->whereDate('updated_at', $now->toDateString())
            ->count();

        // Calculate monthly revenue
        $monthlyRevenue = Rental::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_cost');

        // Get pending returns
        $pendingReturns = Rental::where('status', Rental::STATUS_PENDING)
            ->when(
                Schema::hasColumn('rentals', 'previous_status'),
                fn($query) => $query->whereNotNull('previous_status')
            )
            ->count();

        // Get units availability
        $unitsAvailable = Unit::where('status', 'available')->count();
        $totalUnits = Unit::count();

        // Get recent rentals
        $recentRentals = Rental::with(['user:id,name', 'unit:id,name'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'userCount' => User::count(),
            'activeRentals' => $activeRentals,
            'lateRentals' => $lateRentals,
            'completedToday' => $completedToday,
            'monthlyRevenue' => $monthlyRevenue,
            'pendingReturns' => $pendingReturns,
            'unitsAvailable' => $unitsAvailable,
            'totalUnits' => $totalUnits,
            'recentRentals' => $recentRentals,
        ]);
    }
}