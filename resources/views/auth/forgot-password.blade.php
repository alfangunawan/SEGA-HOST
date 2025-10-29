<x-guest-layout>
    <div class="space-y-8">
        <div class="space-y-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Bantuan
                Akun</span>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Atur Ulang Kata Sandi</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Masukkan alamat email terdaftar dan kami akan mengirim tautan untuk mengatur ulang kata sandi Anda.
                Tautan berlaku selama 60 menit demi keamanan akun.
            </p>
        </div>

        <x-auth-session-status :status="session('status')"
            class="text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl px-4 py-3" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')"
                    class="text-xs font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-400" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                    class="block w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/70 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300 transition-colors">
                    Kembali ke halaman masuk
                </a>

                <x-primary-button
                    class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 text-white text-sm tracking-wide px-6 py-3 rounded-2xl">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Periksa folder spam apabila tidak menemukan email dalam beberapa menit, atau hubungi tim dukungan
                    kami.</span>
            </div>
        </div>
    </div>
</x-guest-layout>