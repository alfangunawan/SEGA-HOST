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
        
        // Get user's statistics - exclude cancelled rentals
        $activeServers = $user->activeRentalsCount();
        
        // Total orders should exclude cancelled rentals
        $totalOrders = $user->rentals()
            ->whereNotIn('status', ['cancelled'])
            ->count();
        
        // Monthly spending should exclude cancelled rentals
        $monthlySpending = $user->rentals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total_cost');
            
        $supportTickets = 0; // Placeholder
        
        // Get recent rentals - show all including cancelled for transparency
        $recentRentals = $user->rentals()
            ->with('unit')
            ->latest()
            ->take(5)
            ->get();
        
        // Additional stats for better insight - group returned_early with completed
        $completedOrders = $user->rentals()
            ->whereIn('status', ['completed', 'returned_early']) // Gabungkan
            ->count();
            
        $pendingOrders = $user->rentals()
            ->where('status', 'pending')
            ->count();
            
        $cancelledOrders = $user->rentals()
            ->where('status', 'cancelled')
            ->count();
        
        return view('dashboard', compact(
            'activeServers',
            'totalOrders', 
            'monthlySpending',
            'supportTickets',
            'recentRentals',
            'completedOrders',
            'pendingOrders',
            'cancelledOrders'
        ));
    }
}

