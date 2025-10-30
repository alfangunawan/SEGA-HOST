@extends('admin.layouts.app')

@section('title', __('Tambah Kategori'))
@section('header', __('Tambah Kategori'))
@section('subheader', __('Masukkan detail kategori baru untuk menambah layanan.'))

@section('content')
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @include('admin.categories._form', ['submitLabel' => __('Simpan')])
        </form>
    </div>
@endsection