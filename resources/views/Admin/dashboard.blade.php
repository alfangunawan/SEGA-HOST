<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold">{{ __('Selamat datang, :name!', ['name' => Auth::user()->name]) }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Gunakan panel berikut untuk memantau aktivitas terbaru, dan mengelola data aplikasi.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Ringkasan Pengguna') }}</h3>
                        <p class="mt-4 text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Total pengguna terdaftar.') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Notifikasi') }}</h3>
                        <p class="mt-4 text-2xl font-bold">3</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Pengumuman penting menunggu review.') }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('Tautan Cepat') }}</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a href="#"
                                    class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Kelola Pengguna') }}</a>
                            </li>
                            <li><a href="#"
                                    class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Laporan Bulanan') }}</a>
                            </li>
                            <li><a href="#"
                                    class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Pengaturan Sistem') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Aktivitas Terbaru') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Belum ada aktivitas terbaru. Tambahkan integrasi log untuk menampilkan data di sini.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>