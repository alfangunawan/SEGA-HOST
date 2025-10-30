<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = storedTheme === 'dark' || (!storedTheme && prefersDark);
            if (useDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body
    class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="relative w-full max-w-xl">
            <div class="absolute inset-0 bg-white/50 dark:bg-gray-900/40 blur-2xl rounded-3xl"></div>

            <div
                class="relative overflow-hidden rounded-3xl border border-white/70 dark:border-gray-800 shadow-2xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl">
                <div class="px-6 py-8 sm:px-10 sm:py-10">
                    <div class="flex items-center justify-between">
                        <a href="/" class="flex items-center space-x-3">
                            <img src="{{ asset('img/sega_logo.png') }}" alt="Sega Logo" class="w-12 h-12">
                            <span
                                class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight">{{ config('app.name', 'SEGA HOST') }}</span>
                        </a>

                        <div class="flex items-center space-x-3">
                            <button id="theme-toggle" type="button" aria-label="Toggle dark mode"
                                class="flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/70 shadow-sm hover:border-blue-400 hover:text-blue-600 dark:hover:border-blue-500 dark:hover:text-blue-300 transition-all">
                                <svg id="theme-toggle-sun" class="w-5 h-5 text-yellow-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 4V2m0 20v-2m8-8h2M2 12h2m13.657-6.343L18.485 4.2M5.515 19.8l1.828-1.457M18.485 19.8l-1.828-1.457M5.515 4.2 7.343 5.657M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                                <svg id="theme-toggle-moon" class="w-5 h-5 text-blue-400 hidden" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="space-y-8 mt-8">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.documentElement;
            const toggle = document.getElementById('theme-toggle');
            const sunIcon = document.getElementById('theme-toggle-sun');
            const moonIcon = document.getElementById('theme-toggle-moon');

            function updateIcons() {
                const isDark = root.classList.contains('dark');
                sunIcon?.classList.toggle('hidden', isDark);
                moonIcon?.classList.toggle('hidden', !isDark);
                toggle?.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            }

            function applyTheme(theme) {
                if (theme === 'dark') {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
                localStorage.setItem('theme', theme);
                updateIcons();
            }

            toggle?.addEventListener('click', function () {
                const isDark = root.classList.contains('dark');
                applyTheme(isDark ? 'light' : 'dark');
            });

            updateIcons();
        });
    </script>
</body>

</html>