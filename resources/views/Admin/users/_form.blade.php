@csrf

@php
    $user = $user ?? null;
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Nama Lengkap') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100 dark:placeholder-gray-500"
                required>
            @error('name')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
            @error('email')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="role"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Peran') }}</label>
            <select name="role" id="role"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
                @foreach ([
                        'admin' => __('Admin'),
                        'user' => __('Anggota'),
                    ] as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role ?? 'user') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('role')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="balance"
                class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Saldo (Rp)') }}</label>
            <input type="number" name="balance" id="balance" min="0" step="0.01"
                value="{{ old('balance', $user->balance ?? 0) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                required>
            @error('balance')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Kata Sandi') }}</label>
            <input type="password" name="password" id="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                   {{ isset($user) ? '' : 'required' }}>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ isset($user) ? __('Biarkan kosong jika tidak ingin mengganti kata sandi.') : __('Minimal 8 karakter.') }}
            </p>
            @error('password')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100"
                   {{ isset($user) ? '' : 'required' }}>
        </div>
    </div>
 
    <div class="flex items-center justify-end gap-3">
    <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-800">{{ __('Batal') }}</a>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</div>
