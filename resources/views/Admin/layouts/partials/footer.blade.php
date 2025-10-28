<footer class="bg-white border-t border-gray-200 dark:bg-slate-900 dark:border-slate-800">
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-sm text-gray-500 flex flex-col gap-2 md:flex-row md:items-center md:justify-between dark:text-gray-300">
        <span>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('Hak cipta dilindungi.') }}</span>
        <span>{{ __('Dibangun untuk kebutuhan administrasi internal.') }}</span>
    </div>
</footer>