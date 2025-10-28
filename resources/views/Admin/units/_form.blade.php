@csrf

@php
    $unit = $unit ?? null;
    $selectedCategories = old('categories', $selectedCategories ?? []);
    $configurationProfiles = $configurationProfiles ?? collect();
    $selectedConfigurationProfileId = old('configuration_profile_id', $unit->configuration_profile_id ?? '');
    $configurationValues = old('configuration', $configurationValues ?? []);
@endphp

<div class="space-y-6" x-data="{ selectedProfileId: @js((string) ($selectedConfigurationProfileId ?? '')) }">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Nama Unit') }}</label>
        <input type="text" name="name" id="name" value="{{ old('name', $unit->name ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500"
               required>
        @error('name')
            <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Deskripsi') }}</label>
        <textarea name="description" id="description" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500">{{ old('description', $unit->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Status') }}</label>
            <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                @foreach ([
                    'available' => __('Tersedia'),
                    'rented' => __('Disewa'),
                    'maintenance' => __('Perbaikan'),
                ] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $unit->status ?? 'available') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Harga per Hari') }}</label>
            <input type="number" step="0.01" min="0" name="price_per_day" id="price_per_day"
                   value="{{ old('price_per_day', $unit->price_per_day ?? '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                   required>
            @error('price_per_day')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="penalty" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Denda per Hari') }}</label>
            <input type="number" step="1" min="0" name="penalty" id="penalty"
                   value="{{ old('penalty', $unit->penalty ?? 5000) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Nilai default 5.000. Sesuaikan sesuai kebutuhan.') }}</p>
            @error('penalty')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="ip_address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Alamat IP') }}</label>
            <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address', $unit->ip_address ?? '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500">
            @error('ip_address')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Lokasi') }}</label>
            <input type="text" name="location" id="location" value="{{ old('location', $unit->location ?? '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500">
            @error('location')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="categories" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Kategori') }}</label>
            <select name="categories[]" id="categories" multiple
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" @selected(in_array($id, $selectedCategories))>{{ $name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Gunakan Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari satu kategori.') }}</p>
            @error('categories')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
            @error('categories.*')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if ($configurationProfiles->isNotEmpty())
        <div class="space-y-4">
            <div>
                <label for="configuration_profile_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Template Konfigurasi') }}</label>
                <select name="configuration_profile_id" id="configuration_profile_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                        x-model="selectedProfileId">
                    <option value="">{{ __('Tanpa Template') }}</option>
                    @foreach ($configurationProfiles as $profile)
                        <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Pilih template untuk memuat field konfigurasi pramuat. Biarkan kosong jika ingin mengisi manual di luar sistem.') }}</p>
                @error('configuration_profile_id')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            @foreach ($configurationProfiles as $profile)
                @php
                    $profileIdString = (string) $profile->id;
                @endphp
                <div class="rounded-lg border border-dashed border-gray-300 p-4 dark:border-slate-700" x-cloak
                     x-show="selectedProfileId === @js($profileIdString)">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $profile->name }}</h3>
                            <span class="text-xs font-medium uppercase tracking-wide text-indigo-500">{{ __('Aktif') }}</span>
                        </div>
                        @if ($profile->description)
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $profile->description }}</p>
                        @endif
                    </div>

                    <div class="mt-4 space-y-5">
                        @foreach ($profile->fields as $field)
                            @php
                                $fieldId = 'configuration_' . $field->id;
                                $fieldName = 'configuration[' . $field->id . ']';
                                $fieldValue = $configurationValues[$field->id] ?? '';
                                $fieldMeta = $field->meta ?? [];
                            @endphp
                            <div>
                                <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $field->label }}
                                    @if ($field->is_required)
                                        <span class="text-rose-500">*</span>
                                    @endif
                                </label>

                                @if ($field->type === 'textarea')
                                    <textarea name="{{ $fieldName }}" id="{{ $fieldId }}" rows="4"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500"
                                              @if ($field->is_required) required @endif>{{ old('configuration.' . $field->id, $fieldValue) }}</textarea>
                                @elseif ($field->type === 'number')
                                    <input type="number" name="{{ $fieldName }}" id="{{ $fieldId }}"
                                           value="{{ old('configuration.' . $field->id, $fieldValue) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                           @if ($field->is_required) required @endif>
                                @elseif ($field->type === 'select')
                                    @php
                                        $options = $field->options ?? [];
                                        $currentValue = old('configuration.' . $field->id, $fieldValue);
                                    @endphp
                                    <select name="{{ $fieldName }}" id="{{ $fieldId }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                            @if ($field->is_required) required @endif>
                                        <option value="">{{ __('Pilih salah satu') }}</option>
                                        @foreach ($options as $optionKey => $optionValue)
                                            @php
                                                if (is_array($optionValue) && array_key_exists('value', $optionValue)) {
                                                    $optionActualValue = (string) $optionValue['value'];
                                                    $optionLabel = $optionValue['label'] ?? $optionActualValue;
                                                } elseif (is_string($optionKey) && ! is_int($optionKey)) {
                                                    $optionActualValue = (string) $optionKey;
                                                    $optionLabel = (string) $optionValue;
                                                } else {
                                                    $optionActualValue = (string) $optionValue;
                                                    $optionLabel = (string) $optionValue;
                                                }
                                            @endphp
                                            <option value="{{ $optionActualValue }}" @selected($currentValue === $optionActualValue)>{{ $optionLabel }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="{{ $fieldName }}" id="{{ $fieldId }}"
                                           value="{{ old('configuration.' . $field->id, $fieldValue) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                           @if ($field->is_required) required @endif>
                                @endif

                                @if (! empty($fieldMeta['help']))
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $fieldMeta['help'] }}</p>
                                @endif

                                @error('configuration.' . $field->id)
                                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.units.index') }}"
           class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</div>
