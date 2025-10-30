@csrf

@php
    $rental = $rental ?? null;
    $durationOptions = $durationOptions ?? range(1, \App\Models\Rental::MAX_RENTAL_DAYS);
    $existingDuration = null;

    if ($rental && $rental->start_date && $rental->end_date) {
        $existingDuration = max($rental->start_date->diffInDays($rental->end_date), 1);
    }

    $selectedDuration = old('duration_days', $existingDuration ?? \App\Models\Rental::MAX_RENTAL_DAYS);
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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-start">
        <div>
            <label for="start_date"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Tanggal Mulai') }}</label>
            <input type="date" name="start_date" id="start_date"
                value="{{ old('start_date', optional(optional($rental)->start_date)->format('Y-m-d')) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
            @error('start_date')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="duration_days"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Durasi Sewa (hari)') }}</label>
            <select name="duration_days" id="duration_days"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
                @foreach ($durationOptions as $option)
                    <option value="{{ $option }}" @selected((int) $selectedDuration === (int) $option)>
                        {{ $option }} {{ __('hari') }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Durasi maksimal sesuai batasan sistem adalah :max hari.', ['max' => \App\Models\Rental::MAX_RENTAL_DAYS]) }}
            </p>
            @error('duration_days')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        @if ($rental)
            <div>
                <label for="status"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Status Peminjaman') }}</label>
                <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                    @foreach (\App\Models\Rental::STATUS_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $rental->status) === $value)>{{ __($label) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.rentals.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ $submitLabel }}</button>
    </div>
</div>