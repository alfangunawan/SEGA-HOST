<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ConfigurationField;
use App\Models\ConfigurationProfile;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $configurationProfiles = ConfigurationProfile::with([
            'fields' => fn($query) => $query->orderByRaw("JSON_EXTRACT(meta, '$.order')")->orderBy('label'),
        ])
            ->orderBy('name')
            ->get();

        return view('admin.units.create', compact('categories', 'configurationProfiles'));
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $configurationValues = $validated['configuration'] ?? [];
        unset($validated['configuration']);
        $configurationProfileId = $validated['configuration_profile_id'] ?? null;

        $validated['penalty'] = $validated['penalty'] ?? 5000;

        $unit = Unit::create($validated);
        $unit->categories()->sync($categories);

        $this->syncConfigurationValues($unit, $configurationProfileId, $configurationValues);

        return redirect()->route('admin.units.index')
            ->with('status', __('Unit berhasil ditambahkan.'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit): View
    {
        $unit->load(['configurationValues']);
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $selectedCategories = $unit->categories()->pluck('categories.id')->toArray();
        $configurationProfiles = ConfigurationProfile::with([
            'fields' => fn($query) => $query->orderByRaw("JSON_EXTRACT(meta, '$.order')")->orderBy('label'),
        ])
            ->orderBy('name')
            ->get();
        $configurationValues = $unit->configurationValues
            ->pluck('value', 'configuration_field_id')
            ->map(fn($value) => $value ?? '')
            ->toArray();

        return view('admin.units.edit', compact(
            'unit',
            'categories',
            'selectedCategories',
            'configurationProfiles',
            'configurationValues'
        ));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $this->validatedData($request, $unit->id);
        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $configurationValues = $validated['configuration'] ?? [];
        unset($validated['configuration']);
        $configurationProfileId = $validated['configuration_profile_id'] ?? null;

        $validated['penalty'] = $validated['penalty'] ?? 5000;

        $unit->update($validated);
        $unit->categories()->sync($categories);

        $this->syncConfigurationValues($unit, $configurationProfileId, $configurationValues);

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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'penalty' => ['nullable', 'integer', 'min:0'],
            'ip_address' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'configuration_profile_id' => ['nullable', 'exists:configuration_profiles,id'],
            'configuration' => ['nullable', 'array'],
            'configuration.*' => ['nullable'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $profileId = $request->input('configuration_profile_id');

            if (!$profileId) {
                return;
            }

            $profile = ConfigurationProfile::with('fields')->find($profileId);

            if (!$profile) {
                $validator->errors()->add('configuration_profile_id', __('Profil konfigurasi tidak ditemukan.'));

                return;
            }

            foreach ($profile->fields as $field) {
                $value = $request->input("configuration.{$field->id}");

                if ($field->is_required && ($value === null || $value === '')) {
                    $validator->errors()->add("configuration.{$field->id}", __(':label wajib diisi.', [
                        'label' => $field->label,
                    ]));
                }

                if ($field->type === 'select' && !$this->isValidOption($field, $value)) {
                    $validator->errors()->add("configuration.{$field->id}", __('Pilihan tidak valid untuk :label.', [
                        'label' => $field->label,
                    ]));
                }
            }
        });

        return $validator->validate();
    }

    /**
     * Sync configuration values for the unit based on selected profile.
     */
    protected function syncConfigurationValues(Unit $unit, ?int $configurationProfileId, array $values): void
    {
        if (!$configurationProfileId) {
            $unit->configurationValues()->delete();

            return;
        }

        $profile = ConfigurationProfile::with('fields')->find($configurationProfileId);

        if (!$profile) {
            $unit->configurationValues()->delete();

            return;
        }

        $fieldIds = $profile->fields->pluck('id')->all();

        foreach ($profile->fields as $field) {
            $value = $values[$field->id] ?? null;

            if (is_string($value) && trim($value) === '') {
                $value = null;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $unit->configurationValues()->updateOrCreate(
                ['configuration_field_id' => $field->id],
                ['value' => $value]
            );
        }

        if (!empty($fieldIds)) {
            $unit->configurationValues()
                ->whereNotIn('configuration_field_id', $fieldIds)
                ->delete();
        } else {
            $unit->configurationValues()->delete();
        }
    }

    /**
     * Ensure a submitted value matches the provided select options.
     */
    protected function isValidOption(ConfigurationField $field, $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }

        $options = $field->options ?? [];

        if (empty($options)) {
            return true;
        }

        $normalizedValues = [];

        foreach ($options as $optionKey => $optionValue) {
            if (is_array($optionValue) && array_key_exists('value', $optionValue)) {
                $normalizedValues[] = (string) $optionValue['value'];
            } elseif (is_string($optionKey)) {
                $normalizedValues[] = (string) $optionKey;
            } else {
                $normalizedValues[] = (string) $optionValue;
            }
        }

        return in_array((string) $value, $normalizedValues, true);
    }

}
