<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfigurationProfileController extends Controller
{
    /**
     * Display a listing of configuration profiles.
     */
    public function index(): View
    {
        $profiles = ConfigurationProfile::with(['fields' => fn($query) => $query->orderByRaw("JSON_EXTRACT(meta, '$.order')")->orderBy('label')])
            ->withCount('fields')
            ->latest()
            ->paginate(10);

        return view('admin.configurations.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new configuration profile.
     */
    public function create(): View
    {
        $configurationProfile = new ConfigurationProfile();

        return view('admin.configurations.create', compact('configurationProfile'));
    }

    /**
     * Store a newly created configuration profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        [$normalizedFields, $errors] = $this->normalizeFields($request->input('fields', []));

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        if (empty($normalizedFields)) {
            throw ValidationException::withMessages([
                'fields' => __('Minimal satu field konfigurasi diperlukan.'),
            ]);
        }

        $profile = ConfigurationProfile::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? null, $validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        $this->syncFields($profile, $normalizedFields);

        return redirect()->route('admin.configurations.index')
            ->with('status', __('Profil konfigurasi berhasil ditambahkan.'));
    }

    /**
     * Show the form for editing the specified configuration profile.
     */
    public function edit(ConfigurationProfile $configuration): View
    {
        $configuration->load('fields');

        return view('admin.configurations.edit', [
            'configurationProfile' => $configuration,
        ]);
    }

    /**
     * Update the specified configuration profile in storage.
     */
    public function update(Request $request, ConfigurationProfile $configuration): RedirectResponse
    {
        $validated = $this->validatedData($request, $configuration->id);

        [$normalizedFields, $errors] = $this->normalizeFields($request->input('fields', []));

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        if (empty($normalizedFields)) {
            throw ValidationException::withMessages([
                'fields' => __('Minimal satu field konfigurasi diperlukan.'),
            ]);
        }

        $configuration->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['slug'] ?? null, $validated['name'], $configuration->id),
            'description' => $validated['description'] ?? null,
        ]);

        $this->syncFields($configuration, $normalizedFields);

        return redirect()->route('admin.configurations.index')
            ->with('status', __('Profil konfigurasi berhasil diperbarui.'));
    }

    /**
     * Remove the specified configuration profile from storage.
     */
    public function destroy(ConfigurationProfile $configuration): RedirectResponse
    {
        $configuration->delete();

        return redirect()->route('admin.configurations.index')
            ->with('status', __('Profil konfigurasi berhasil dihapus.'));
    }

    /**
     * Validate incoming request data.
     */
    protected function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('configuration_profiles', 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['nullable', 'string', 'max:255'],
            'fields.*.key' => ['nullable', 'string', 'max:255'],
            'fields.*.type' => ['nullable', 'in:text,textarea,number,select'],
            'fields.*.options_text' => ['nullable', 'string'],
            'fields.*.is_required' => ['nullable'],
            'fields.*.help' => ['nullable', 'string', 'max:500'],
        ]);
    }

    /**
     * Normalize field payload for storage.
     */
    protected function normalizeFields(array $fieldsInput): array
    {
        $normalized = [];
        $errors = [];
        $existingKeys = [];

        foreach ($fieldsInput as $index => $fieldData) {
            $rowKey = "fields.$index";
            $label = isset($fieldData['label']) ? trim($fieldData['label']) : '';

            if ($label === '') {
                $errors["$rowKey.label"] = __('Label wajib diisi.');
                continue;
            }

            $rawKey = isset($fieldData['key']) ? trim($fieldData['key']) : '';
            $generatedKey = $rawKey !== '' ? $this->slugifyKey($rawKey) : $this->slugifyKey($label);

            if ($generatedKey === '') {
                $generatedKey = 'field_' . ($index + 1);
            }

            $baseKey = $generatedKey;
            $counter = 1;
            while (in_array($generatedKey, $existingKeys, true)) {
                $generatedKey = $baseKey . '_' . $counter++;
            }
            $existingKeys[] = $generatedKey;

            $type = isset($fieldData['type']) && in_array($fieldData['type'], ['text', 'textarea', 'number', 'select'], true)
                ? $fieldData['type']
                : 'text';

            $options = null;
            $optionsText = $fieldData['options_text'] ?? $fieldData['options'] ?? '';

            if ($type === 'select') {
                $options = $this->parseOptions($optionsText);

                if (empty($options)) {
                    $errors["$rowKey.options_text"] = __('Minimal satu opsi diperlukan untuk field select.');
                }
            }

            $normalized[] = [
                'id' => isset($fieldData['id']) ? (int) $fieldData['id'] : null,
                'label' => $label,
                'key' => $generatedKey,
                'type' => $type,
                'options' => $options,
                'is_required' => filter_var($fieldData['is_required'] ?? false, FILTER_VALIDATE_BOOL),
                'help' => isset($fieldData['help']) ? trim($fieldData['help']) : null,
                'order' => $index,
            ];
        }

        return [$normalized, $errors];
    }

    /**
     * Persist field changes for the given profile.
     */
    protected function syncFields(ConfigurationProfile $profile, array $fields): void
    {
        $keptIds = [];

        foreach ($fields as $position => $fieldData) {
            $attributes = [
                'label' => $fieldData['label'],
                'key' => $fieldData['key'],
                'type' => $fieldData['type'],
                'options' => $fieldData['options'],
                'is_required' => $fieldData['is_required'],
                'meta' => [
                    'help' => $fieldData['help'],
                    'order' => $position,
                ],
            ];

            if ($fieldData['id']) {
                $existing = $profile->fields()->find($fieldData['id']);

                if ($existing) {
                    $existing->update($attributes);
                    $keptIds[] = $existing->id;
                    continue;
                }
            }

            $newField = $profile->fields()->create($attributes);
            $keptIds[] = $newField->id;
        }

        if (!empty($keptIds)) {
            $profile->fields()->whereNotIn('id', $keptIds)->delete();
        } else {
            $profile->fields()->delete();
        }
    }

    /**
     * Parse select field options from textarea input.
     */
    protected function parseOptions(?string $optionsText): ?array
    {
        if ($optionsText === null) {
            return null;
        }

        $options = [];
        $lines = preg_split('/\r?\n/', $optionsText);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                continue;
            }

            if (str_contains($trimmed, '|')) {
                [$value, $label] = array_map('trim', explode('|', $trimmed, 2));
            } else {
                $value = $trimmed;
                $label = $trimmed;
            }

            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return empty($options) ? null : $options;
    }

    /**
     * Generate a unique slug for the configuration profile.
     */
    protected function generateUniqueSlug(?string $requestedSlug, string $fallbackName, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($requestedSlug ?: $fallbackName);
        $slug = $baseSlug;
        $counter = 1;

        while (
            ConfigurationProfile::where('slug', $slug)
                ->when($ignoreId, fn($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Convert incoming key to slug format.
     */
    protected function slugifyKey(string $value): string
    {
        return Str::slug($value, '_');
    }
}
