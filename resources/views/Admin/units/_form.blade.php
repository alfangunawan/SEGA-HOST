@csrf

@php
    $unit = $unit ?? null;
    $selectedCategories = old('categories', $selectedCategories ?? []);
@endphp

<div class="space-y-6">
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

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.units.index') }}"
           class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</div>
