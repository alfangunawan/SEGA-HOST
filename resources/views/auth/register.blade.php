<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">Mulai
                Sekarang</span>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Buat Akun SEGA Host</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Daftar dan dapatkan akses ke panel pengelolaan server yang modern, monitoring real-time, dan dukungan
                teknis selama 24 jam.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Name')"
                        class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                        autocomplete="name"
                        class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Email')"
                        class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required
                        autocomplete="username"
                        class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300 transition-colors">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button
                    class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 text-white text-sm tracking-wide px-6 py-3 rounded-2xl">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Pendaftaran Anda otomatis mengaktifkan perlindungan keamanan lanjutan dan opsi verifikasi dua
                    langkah.</span>
            </div>
        </div>
    </div>
</x-guest-layout>