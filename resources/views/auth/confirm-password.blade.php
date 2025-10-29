<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Validasi
                Akses</span>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Konfirmasi Kata Sandi</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Area ini memerlukan konfirmasi ulang untuk memastikan keamanan data sensitif. Masukkan kata sandi Anda
                sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button
                    class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 text-white text-sm tracking-wide px-6 py-3 rounded-2xl">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Konfirmasi ulang membantu mencegah akses tidak sah saat melakukan perubahan penting.</span>
            </div>
        </div>
    </div>
</x-guest-layout>