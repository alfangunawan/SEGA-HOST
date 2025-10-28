@csrf

@php
    $profile = $configurationProfile ?? null;
    $fieldsFromOld = old('fields', []);

    if (!empty($fieldsFromOld)) {
        $preparedFields = collect($fieldsFromOld)->map(function ($field, $index) {
            $optionsText = $field['options_text'] ?? $field['options'] ?? '';

            return [
                'id' => $field['id'] ?? null,
                'label' => $field['label'] ?? '',
                'key' => $field['key'] ?? '',
                'type' => $field['type'] ?? 'text',
                'is_required' => filter_var($field['is_required'] ?? false, FILTER_VALIDATE_BOOL),
                'options_text' => $optionsText,
                'help' => $field['help'] ?? '',
            ];
        });
    } elseif ($profile && $profile->exists) {
        $preparedFields = $profile->fields->sortBy(fn($field) => $field->meta['order'] ?? 0)->values()->map(function ($field) {
            $optionsText = '';

            if ($field->type === 'select' && !empty($field->options)) {
                $optionsText = collect($field->options)
                    ->map(function ($option) {
                        $value = is_array($option) ? ($option['value'] ?? '') : $option;
                        $label = is_array($option) ? ($option['label'] ?? $value) : $option;

                        return trim($value) === trim($label) ? trim($value) : trim($value) . '|' . trim($label);
                    })
                    ->implode("\n");
            }

            return [
                'id' => $field->id,
                'label' => $field->label,
                'key' => $field->key,
                'type' => $field->type,
                'is_required' => (bool) $field->is_required,
                'options_text' => $optionsText,
                'help' => $field->meta['help'] ?? '',
            ];
        });
    } else {
        $preparedFields = collect([
            [
                'id' => null,
                'label' => '',
                'key' => '',
                'type' => 'text',
                'is_required' => true,
                'options_text' => '',
                'help' => '',
            ],
        ]);
    }

    $initialFields = $preparedFields
        ->values()
        ->map(function ($field, $index) {
            return array_merge($field, [
                'uid' => $field['id'] ?: ('field-' . $index . '-' . \Illuminate\Support\Str::uuid()->toString()),
            ]);
        })
        ->toArray();
@endphp

<div class="space-y-6" x-data="configurationProfileForm({ fields: @js($initialFields) })">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="space-y-2">
            <label for="name"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Nama Profil') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name', $profile?->name ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500"
                required>
            @error('name')
                <p class="text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="slug"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Slug (opsional)') }}</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $profile?->slug ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500"
                placeholder="{{ __('Contoh: high-performance-game') }}">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('Jika dikosongkan akan dibuat otomatis dari nama.') }}</p>
            @error('slug')
                <p class="text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="space-y-2">
        <label for="description"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Deskripsi') }}</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500">{{ old('description', $profile?->description ?? '') }}</textarea>
        @error('description')
            <p class="text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-xl border border-dashed border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900">
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 dark:border-slate-800 px-4 py-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Field Konfigurasi') }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Susun field yang akan ditampilkan ketika admin memilih template ini.') }}</p>
            </div>
            <button type="button" @click="addField"
                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Tambah Field') }}
            </button>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-slate-800" x-show="fields.length">
            <template x-for="(field, index) in fields" :key="field.uid">
                <div class="p-4 space-y-4" x-data="{ isSelect: field.type === 'select' }"
                    @type-changed.window="if($event.detail.uid === field.uid) { field.type = $event.detail.type; isSelect = field.type === 'select'; }">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200"
                                x-text="index + 1"></span>
                            <span>{{ __('Field') }} <span x-text="index + 1"></span></span>
                        </div>
                        <button type="button" class="text-xs font-medium text-rose-600 hover:text-rose-500"
                            @click="removeField(index)">
                            {{ __('Hapus') }}
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Label') }}</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                x-model="field.label" @blur="autofillKey(index)" :name="`fields[${index}][label]`"
                                required>
                            <template x-if="errors[`fields.${index}.label`]">
                                <p class="text-sm text-rose-600" x-text="errors[`fields.${index}.label`]"></p>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Key (opsional)') }}</label>
                            <input type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                x-model="field.key" :name="`fields[${index}][key]`"
                                placeholder="{{ __('Contoh: max_player') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Tipe Input') }}</label>
                            <select
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                                x-model="field.type" :name="`fields[${index}][type]`"
                                @change="isSelect = field.type === 'select'">
                                <option value="text">{{ __('Teks') }}</option>
                                <option value="textarea">{{ __('Textarea') }}</option>
                                <option value="number">{{ __('Angka') }}</option>
                                <option value="select">{{ __('Pilihan (Select)') }}</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Wajib Diisi?') }}</label>
                            <div
                                class="flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 dark:border-slate-700">
                                <input type="hidden" :name="`fields[${index}][is_required]`" value="0">
                                <input type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    x-model="field.is_required" :name="`fields[${index}][is_required]`" value="1">
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-300">{{ __('Centang jika field harus diisi.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2" x-show="isSelect">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Daftar Opsi') }}</label>
                        <textarea
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                            rows="4" x-model="field.options_text" :name="`fields[${index}][options_text]`"
                            placeholder="{{ __('Satu baris per opsi, format: nilai|Label. Contoh: 8|8 GB RAM') }}"></textarea>
                        <template x-if="errors[`fields.${index}.options_text`]">
                            <p class="text-sm text-rose-600" x-text="errors[`fields.${index}.options_text`]"></p>
                        </template>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Catatan / Bantuan') }}</label>
                        <textarea
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                            rows="3" x-model="field.help" :name="`fields[${index}][help]`"
                            placeholder="{{ __('Info singkat untuk admin saat mengisi field ini.') }}"></textarea>
                    </div>

                    <input type="hidden" :name="`fields[${index}][id]`" :value="field.id ?? ''">
                </div>
            </template>
        </div>

        <div class="p-4" x-show="!fields.length">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Belum ada field. Klik "Tambah Field" untuk memulai.') }}</p>
        </div>
    </div>

    @if ($errors->has('fields'))
        <p class="text-sm text-rose-600 dark:text-rose-300">{{ $errors->first('fields') }}</p>
    @endif

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.configurations.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        function configurationProfileForm({ fields }) {
            return {
                fields: fields ?? [],
                errors: @js($errors->getMessages()),
                addField() {
                    this.fields.push({
                        uid: `field-${Date.now()}-${Math.random().toString(36).substring(2, 8)}`,
                        id: null,
                        label: '',
                        key: '',
                        type: 'text',
                        is_required: true,
                        options_text: '',
                        help: '',
                    });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                    if (this.fields.length === 0) {
                        this.addField();
                    }
                },
                slugify(value) {
                    return value
                        .toString()
                        .normalize('NFD')
                        .replace(/[^\w\s-]/g, '')
                        .trim()
                        .replace(/[-\s]+/g, '_')
                        .toLowerCase();
                },
                autofillKey(index) {
                    const field = this.fields[index];
                    if (!field) {
                        return;
                    }
                    if (!field.key && field.label) {
                        field.key = this.slugify(field.label);
                    }
                },
            };
        }
    </script>
@endpush