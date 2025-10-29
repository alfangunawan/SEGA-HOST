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
        <div class="relative w-full max-w-5xl">
            <div class="absolute inset-0 bg-white/40 dark:bg-gray-900/40 blur-3xl rounded-3xl"></div>

            <div
                class="relative flex flex-col lg:flex-row overflow-hidden rounded-3xl border border-white/60 dark:border-gray-800 shadow-2xl bg-white/80 dark:bg-gray-900/90 backdrop-blur-xl">
                <div
                    class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white p-12 xl:p-14 flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-4">
                            <x-application-logo class="w-12 h-12 text-white" />
                            <span
                                class="text-2xl font-semibold tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                        <p class="mt-6 text-white/80 leading-relaxed text-base">
                            Kelola layanan server premium dengan performa tinggi, konfigurasi fleksibel, dan dukungan
                            real-time yang siap membantu bisnis Anda berkembang.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-white/15 border border-white/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-wide uppercase text-white/80">Infrastruktur</p>
                                <p class="text-sm text-white/70 leading-relaxed">Server berperforma tinggi dengan SLA
                                    99,9% untuk kebutuhan kritikal.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-white/15 border border-white/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-wide uppercase text-white/80">Monitoring</p>
                                <p class="text-sm text-white/70 leading-relaxed">Pantau performa server secara real-time
                                    dengan dashboard intuitif.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-2xl bg-white/15 border border-white/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-wide uppercase text-white/80">Keamanan</p>
                                <p class="text-sm text-white/70 leading-relaxed">Perlindungan menyeluruh dengan
                                    anti-DDoS dan enkripsi end-to-end.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 bg-white/90 dark:bg-gray-900/90">
                    <div class="px-6 py-10 sm:px-10 sm:py-12 lg:p-12">
                        <div class="flex items-center justify-between mb-10">
                            <a href="/" class="flex items-center space-x-3">
                                <x-application-logo class="w-12 h-12 text-blue-600 dark:text-blue-400" />
                                <span
                                    class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight">{{ config('app.name', 'Laravel') }}</span>
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

                        <div class="space-y-8">
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