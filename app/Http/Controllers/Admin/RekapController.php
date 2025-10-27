<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapController extends Controller
{
    /**
     * Display a listing of completed rentals.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $dateRange = $request->query('date_range');

        $rentalsQuery = Rental::query()
            ->with(['user', 'unit'])
            ->where('status', 'returned')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('unit', fn($unitQuery) => $unitQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($dateRange, function ($query, $dateRange) {
                [$start, $end] = explode(' to ', $dateRange) + [null, null];

                if ($start) {
                    $query->whereDate('end_date', '>=', $start);
                }

                if ($end) {
                    $query->whereDate('end_date', '<=', $end);
                }
            })
            ->latest('end_date');

        $summary = [
            'count' => (clone $rentalsQuery)->count(),
            'revenue' => (clone $rentalsQuery)->sum('total_cost'),
        ];

        $rentals = $rentalsQuery
            ->paginate(10)
            ->withQueryString();

        return view('admin.rekap.index', compact('rentals', 'search', 'dateRange', 'summary'));
    }
}
