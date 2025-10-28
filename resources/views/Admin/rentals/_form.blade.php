@csrf

@php
    $rental = $rental ?? null;
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="user_id"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Penyewa') }}</label>
            <select name="user_id" id="user_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
                <option value="" disabled @selected(!old('user_id', $rental->user_id ?? null))>
                    {{ __('Pilih penyewa') }}
                </option>
                @foreach ($users as $id => $name)
                    <option value="{{ $id }}" @selected(old('user_id', $rental->user_id ?? '') == $id)>{{ $name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit_id"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Unit') }}</label>
            <select name="unit_id" id="unit_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
                <option value="" disabled @selected(!old('unit_id', $rental->unit_id ?? null))>{{ __('Pilih unit') }}
                </option>
                @foreach ($units as $id => $name)
                    <option value="{{ $id }}" @selected(old('unit_id', $rental->unit_id ?? '') == $id)>{{ $name }}</option>
                @endforeach
            </select>
            @error('unit_id')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
        <div>
            <label for="start_date"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Tanggal Mulai') }}</label>
            <input type="date" name="start_date" id="start_date"
                value="{{ old('start_date', optional($rental)->start_date) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
            @error('start_date')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div
            class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 space-y-2 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-300">
            <p class="font-medium text-gray-700 dark:text-gray-200">{{ __('Ketentuan Peminjaman') }}</p>
            <p>{{ __('Pinjaman berlangsung maksimal 5 hari sejak tanggal mulai. Tanggal berakhir akan dihitung otomatis.') }}
            </p>
            <p>{{ __('Total biaya dihitung otomatis: harga unit per hari x 5 hari.') }}</p>
            @if(optional($rental)->unit)
                <p class="text-gray-700 dark:text-gray-200">
                    {{ __('Perkiraan total biaya saat ini:') }}
                    <span class="font-semibold">Rp {{ number_format($rental->total_cost, 2, ',', '.') }}</span>
                </p>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.rentals.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ $submitLabel }}</button>
    </div>
</div>