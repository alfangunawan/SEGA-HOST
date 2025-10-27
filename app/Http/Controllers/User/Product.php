<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;

class Product extends Controller
{
    /**
     * Display a listing of units (products) for users
     */
    public function index(Request $request)
    {
        $query = Unit::query()
            ->with('categories')
            ->where('status', 'available');

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $units = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get all categories for filter
        $categories = Category::orderBy('name')->get();
        
        return view('User.showProduct', compact('units', 'categories'));
    }

    /**
     * Display the specified unit (product)
     */
    public function show(Unit $unit)
    {
        if ($unit->status !== 'available') {
            // Still show the page but disable rental functionality
            // You can uncomment the line below if you want to hide unavailable units completely
            // abort(404);
        }
        
        $unit->load('categories');
        
        return view('User.productDetail', compact('unit'));
    }
}