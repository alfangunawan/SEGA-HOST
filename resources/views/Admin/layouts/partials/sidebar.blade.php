<aside
    class="md:w-64 bg-white border-b md:border-b-0 md:border-r border-gray-200 dark:bg-slate-950 dark:border-slate-800">
    <nav class="p-4 space-y-1 text-sm">
        @php($isActive = request()->routeIs('admin.dashboard'))
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Dashboard') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.categories.*'))
        <a href="{{ route('admin.categories.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Kategori') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.units.*'))
        <a href="{{ route('admin.units.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Unit / Server') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.rentals.*'))
        <a href="{{ route('admin.rentals.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Peminjaman') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.rekap.*'))
        <a href="{{ route('admin.rekap.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Rekap') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.users.*'))
        <a href="{{ route('admin.users.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}">
            <span>{{ __('Pengguna') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </nav>
</aside>