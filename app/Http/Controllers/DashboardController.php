<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Get user's statistics
        $activeServers = $user->activeRentalsCount();

        $totalOrders = $user->rentals()->count();

        // Monthly spending for all rentals this month
        $monthlySpending = $user->rentals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_cost');

        $supportTickets = 0; // Placeholder

        // Get recent rentals - show all including cancelled for transparency
        $recentRentals = $user->rentals()
            ->with('unit')
            ->latest()
            ->take(5)
            ->get();

        // Additional stats for better insight
        $completedOrders = $user->rentals()
            ->where('status', Rental::STATUS_COMPLETED)
            ->count();

        $pendingOrders = $user->rentals()
            ->where('status', Rental::STATUS_PENDING)
            ->count();

        return view('dashboard', compact(
            'activeServers',
            'totalOrders',
            'monthlySpending',
            'supportTickets',
            'recentRentals',
            'completedOrders',
            'pendingOrders'
        ));
    }
}

