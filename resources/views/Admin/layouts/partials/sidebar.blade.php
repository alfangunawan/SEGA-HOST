{{-- Mobile Backdrop Overlay --}}
<div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 md:hidden" style="display: none;">
</div>

{{-- Sidebar --}}
<aside x-show="mobileMenuOpen || window.innerWidth >= 768"
    x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    :class="sidebarCollapsed ? 'md:w-20' : 'md:w-64'"
    class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 dark:bg-slate-950 dark:border-slate-800 transition-all duration-300 overflow-y-auto">

    {{-- Mobile Close Button --}}
    <div class="md:hidden flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-800">
        <div class="flex items-center ">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Admin Panel</h1>
        </div>
        <button @click="mobileMenuOpen = false"
            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-1 text-sm">
        @php($isActive = request()->routeIs('admin.dashboard'))
        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Dashboard') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Dashboard') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Dashboard') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.categories.*'))
        <a href="{{ route('admin.categories.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Kategori') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Kategori') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Kategori') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.configurations.*'))
        <a href="{{ route('admin.configurations.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Template Konfigurasi') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h5.586a1 1 0 01.707.293l6.414 6.414a1 1 0 010 1.414l-5.586 5.586a1 1 0 01-1.414 0L4 12.414A2 2 0 014 11V6z" />
                </svg>
                <span class="md:hidden"
                    :class="{ 'md:block': !sidebarCollapsed }">{{ __('Template Konfigurasi') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Template Konfigurasi') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.units.*'))
        <a href="{{ route('admin.units.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Unit / Server') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Unit / Server') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Unit / Server') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.rentals.*'))
        <a href="{{ route('admin.rentals.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Peminjaman') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Peminjaman') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Peminjaman') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.rekap.*'))
        <a href="{{ route('admin.rekap.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Rekap') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Rekap') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Rekap') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @php($isActive = request()->routeIs('admin.users.*'))
        <a href="{{ route('admin.users.index') }}" @click="mobileMenuOpen = false"
            :title="sidebarCollapsed ? '{{ __('Pengguna') }}' : ''"
            class="flex items-center rounded-md px-3 py-2 transition border border-transparent {{ $isActive ? 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-indigo-500/20 dark:text-indigo-200 dark:border-indigo-500/10' : 'hover:bg-gray-100 text-gray-600 dark:hover:bg-slate-800 dark:text-gray-300' }}"
            :class="sidebarCollapsed ? 'md:justify-center' : 'justify-between'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="md:hidden" :class="{ 'md:block': !sidebarCollapsed }">{{ __('Pengguna') }}</span>
                <span class="hidden" :class="{ 'md:inline': !sidebarCollapsed }">{{ __('Pengguna') }}</span>
            </div>
            <svg class="h-4 w-4 md:hidden" :class="{ 'md:block': !sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </nav>
</aside>