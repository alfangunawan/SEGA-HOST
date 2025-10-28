<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') | {{ config('app.name', 'Laravel') }}</title>

    <script>
        try {
            const storedTheme = window.localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            console.error('Theme detection failed', error);
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="admin-layout font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen flex flex-col">
        @include('admin.layouts.partials.header')

        <div class="flex flex-col md:flex-row flex-1">
            @include('admin.layouts.partials.sidebar')

            <main class="flex-1 bg-slate-50 dark:bg-slate-900/40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
        </div>

        @include('admin.layouts.partials.footer')
    </div>

    @stack('scripts')
</body>

</html>