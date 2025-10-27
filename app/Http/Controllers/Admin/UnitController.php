<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $units = Unit::query()
            ->with('categories')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.units.index', compact('units', 'search'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.units.create', compact('categories'));
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $validated['penalty'] = $validated['penalty'] ?? 5000;

        $unit = Unit::create($validated);
        $unit->categories()->sync($categories);

        return redirect()->route('admin.units.index')
            ->with('status', __('Unit berhasil ditambahkan.'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $selectedCategories = $unit->categories()->pluck('categories.id')->toArray();

        return view('admin.units.edit', compact('unit', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $this->validatedData($request, $unit->id);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $validated['penalty'] = $validated['penalty'] ?? 5000;

        $unit->update($validated);
        $unit->categories()->sync($categories);

        return redirect()->route('admin.units.index')
            ->with('status', __('Unit berhasil diperbarui.'));
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('status', __('Unit berhasil dihapus.'));
    }

    /**
     * Validate request data for creating/updating a unit.
     */
    protected function validatedData(Request $request, ?int $unitId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'penalty' => ['nullable', 'integer', 'min:0'],
            'ip_address' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ]);
    }

}
