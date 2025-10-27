<header class="bg-white shadow-sm border-b border-gray-200">
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">{{ __('Panel Admin') }}</div>
            <h1 class="text-2xl font-bold leading-tight mt-1">@yield('header', 'Dashboard')</h1>
            <p class="text-sm text-gray-500 mt-1">@yield('subheader', __('Kelola aplikasi dan pantau data penting.'))
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-sm text-gray-600">
                {{ __('Masuk sebagai') }}
                <span class="font-semibold">{{ optional(auth()->user())->name }}</span>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-100 border border-indigo-100 transition">
                {{ __('Kembali ke Dashboard') }}
            </a>
        </div>
    </div>
</header>