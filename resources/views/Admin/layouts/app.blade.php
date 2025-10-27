<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') | {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-sans antialiased bg-slate-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('admin.layouts.partials.header')

        <div class="flex flex-col md:flex-row flex-1">
            @include('admin.layouts.partials.sidebar')

            <main class="flex-1">
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