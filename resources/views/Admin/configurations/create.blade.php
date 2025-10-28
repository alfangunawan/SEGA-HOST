@extends('admin.layouts.app')

@section('title', __('Tambah Template Konfigurasi'))
@section('header', __('Tambah Template Konfigurasi'))
@section('subheader', __('Buat profil konfigurasi baru yang dapat digunakan oleh unit atau server.'))

@section('content')
    <div class="space-y-4">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <form action="{{ route('admin.configurations.store') }}" method="POST">
                @include('admin.configurations._form', ['submitLabel' => __('Simpan Template')])
            </form>
        </div>
    </div>
@endsection