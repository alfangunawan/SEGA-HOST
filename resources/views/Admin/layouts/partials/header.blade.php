<header class="bg-white border-b border-gray-200 dark:bg-slate-900 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo & Brand --}}
            <div class="flex items-center gap-3">
                {{-- Hamburger Menu --}}
                <button
                    @click="window.innerWidth < 768 ? mobileMenuOpen = !mobileMenuOpen : sidebarCollapsed = !sidebarCollapsed"
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition dark:border-slate-700 dark:text-gray-300 dark:hover:bg-slate-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center justify-center w-10 h-10 bg-indigo-300 rounded-lg overflow-hidden">
                    <img src="{{ asset('img/sega_host_logo.png') }}" alt="SEGA HOST Logo"
                        class="w-full h-full object-contain p-1">
                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex items-center gap-3" x-data="themeToggle()" x-init="init()">
                {{-- Theme Toggle --}}
                <button type="button" @click="toggle()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition dark:border-slate-700 dark:text-gray-300 dark:hover:bg-slate-800"
                    :aria-label="isDark ? '{{ __('Aktifkan mode terang') }}' : '{{ __('Aktifkan mode gelap') }}'">
                    <svg x-show="!isDark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M6.343 6.343L4.929 4.929m12.728 12.728l-1.414-1.414M6.343 17.657l-1.414 1.414M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="isDark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>


                {{-- User Info with Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition cursor-pointer">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                <span
                                    class="text-xs font-semibold text-white">{{ substr(optional(auth()->user())->name, 0, 1) }}</span>
                            </div>
                            <div class="text-sm text-left">
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ optional(auth()->user())->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Client Page') }}
                            </div>
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</header>