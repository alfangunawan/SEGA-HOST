<header class="bg-white shadow-sm border-b border-gray-200 dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="text-xs uppercase tracking-wide text-indigo-600 font-semibold dark:text-indigo-300">
                {{ __('Panel Admin') }}</div>
            <h1 class="text-2xl font-bold leading-tight mt-1">@yield('header', 'Dashboard')</h1>
            <p class="text-sm text-gray-500 mt-1 dark:text-gray-300">
                @yield('subheader', __('Kelola aplikasi dan pantau data penting.'))
            </p>
        </div>
        <div class="flex items-center gap-3" x-data="themeToggle()" x-init="init()">
            <button type="button" @click="toggle()"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 text-gray-600 transition hover:text-indigo-500 hover:border-indigo-400 dark:border-slate-700 dark:text-gray-300 dark:hover:text-indigo-300"
                :aria-label="isDark ? '{{ __('Aktifkan mode terang') }}' : '{{ __('Aktifkan mode gelap') }}'"
                :title="isDark ? '{{ __('Aktifkan mode terang') }}' : '{{ __('Aktifkan mode gelap') }}'">
                <svg x-show="!isDark" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364l-1.06-1.06M6.697 6.697l-1.061-1.061m12.728 0-1.06 1.06M6.697 17.303l-1.061 1.061M12 8.25A3.75 3.75 0 1 0 12 15a3.75 3.75 0 0 0 0-6.75Z" />
                </svg>
                <svg x-show="isDark" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" />
                </svg>
            </button>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ __('Masuk sebagai') }}
                <span class="font-semibold">{{ optional(auth()->user())->name }}</span>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-100 border border-indigo-100 transition dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10 dark:hover:bg-indigo-500/30">
                {{ __('Kembali ke Dashboard') }}
            </a>
        </div>
    </div>
</header>