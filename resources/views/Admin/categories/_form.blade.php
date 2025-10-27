@csrf

@php
    $category = $category ?? null;
@endphp

<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama Kategori') }}</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required>
        @error('name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Deskripsi') }}</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Batal') }}</a>
        <button type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</div>