@extends('admin.layouts.app')

@section('title', __('Dashboard Admin'))
@section('header', __('Dashboard Admin'))
@section('subheader', __('Ringkasan aktivitas dan informasi penting untuk administrator.'))

@section('content')
    <div class="space-y-6">
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
            <div class="p-6">
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Selamat datang, :name!', ['name' => optional(auth()->user())->name]) }}</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Gunakan panel ini untuk memantau data terbaru dan mengelola konfigurasi aplikasi.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide dark:text-gray-300">{{ __('Ringkasan Pengguna') }}</h3>
                    <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($userCount) }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ __('Total pengguna terdaftar di sistem.') }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide dark:text-gray-300">{{ __('Status Sistem') }}</h3>
                    <p class="mt-4 text-3xl font-bold text-emerald-600 dark:text-emerald-300">{{ __('Normal') }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ __('Tidak ada isu kritis yang terdeteksi.') }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide dark:text-gray-300">{{ __('Tautan Cepat') }}</h3>
                    <ul class="mt-4 space-y-3 text-sm text-indigo-600 dark:text-indigo-300">
                        <li><a class="hover:text-indigo-500 dark:hover:text-indigo-200" href="#">{{ __('Kelola Pengguna') }}</a></li>
                        <li><a class="hover:text-indigo-500 dark:hover:text-indigo-200" href="#">{{ __('Laporan Bulanan') }}</a></li>
                        <li><a class="hover:text-indigo-500 dark:hover:text-indigo-200" href="#">{{ __('Pengaturan Sistem') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Aktivitas Terbaru') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Belum ada aktivitas terbaru. Tambahkan integrasi log untuk menampilkan data pada bagian ini.') }}</p>
            </div>
        </div>
    </div>
@endsection