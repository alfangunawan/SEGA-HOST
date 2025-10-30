@extends('admin.layouts.app')

@section('title', __('Ubah Template Konfigurasi'))
@section('header', __('Ubah Template Konfigurasi'))
@section('subheader', __('Perbarui profil konfigurasi dan field yang digunakan oleh unit.'))

@section('content')
    <div class="space-y-4">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <form action="{{ route('admin.configurations.update', $configurationProfile) }}" method="POST">
                @method('PUT')
                @include('admin.configurations._form', ['submitLabel' => __('Simpan Perubahan')])
            </form>
        </div>
    </div>
@endsection