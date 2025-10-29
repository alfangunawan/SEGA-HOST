<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Keamanan
                Akun</span>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Buat Kata Sandi Baru</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Pastikan kata sandi baru Anda kuat dan berbeda dari yang sebelumnya. Setelah disimpan, Anda dapat
                langsung masuk ke dashboard SEGA Host.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required
                    autofocus autocomplete="username"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300 transition-colors">
                    Kembali ke halaman masuk
                </a>

                <x-primary-button
                    class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 text-white text-sm tracking-wide px-6 py-3 rounded-2xl">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 0v1m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk keamanan maksimal. Jangan bagikan
                    kata sandi kepada siapa pun.</span>
            </div>
        </div>
    </div>
</x-guest-layout>