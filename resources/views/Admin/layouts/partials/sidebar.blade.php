<aside class="md:w-64 bg-white border-b md:border-b-0 md:border-r border-gray-200">
    <nav class="p-4 space-y-1 text-sm">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Dashboard') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('admin.categories.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Kategori') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('admin.units.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.units.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Unit / Server') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('admin.rentals.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.rentals.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Peminjaman') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('admin.rekap.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.rekap.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Rekap') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        <a href="{{ route('admin.users.index') }}"
            class="flex items-center justify-between rounded-md px-3 py-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <span>{{ __('Pengguna') }}</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </nav>
</aside>