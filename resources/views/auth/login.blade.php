<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Selamat
                Datang</span>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Masuk ke Akun Anda</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Kelola penyewaan server, pantau performa infrastruktur, dan dapatkan dukungan real-time langsung dari
                dashboard modern SEGA Host.
            </p>
        </div>

        <x-auth-session-status :status="session('status')"
            class="text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl px-4 py-3" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                    autocomplete="username"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Password')"
                        class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lupa
                            kata sandi?</a>
                    @endif
                </div>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center text-xs text-gray-600 dark:text-gray-300">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded-md border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-500 dark:bg-gray-900" />
                    <span class="ms-2">{{ __('Remember me') }}</span>
                </label>

                <div class="hidden sm:flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Keamanan berlapis aktif</span>
                </div>
            </div>

            <div class="space-y-3">
                <x-primary-button
                    class="w-full justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 text-white text-sm tracking-wide px-5 py-3 rounded-2xl">
                    {{ __('Log in') }}
                </x-primary-button>

                <a href="{{ route('register') }}"
                    class="block w-full text-center border border-gray-200 dark:border-gray-700 rounded-2xl px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-500 dark:hover:text-blue-300 transition-all duration-200">
                    Belum punya akun? Daftar sekarang
                </a>
            </div>
        </form>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Sistem login diamankan dengan multi-factor authentication opsional dan enkripsi modern.</span>
            </div>
        </div>
    </div>
</x-guest-layout>