@extends('admin.layouts.app')

@section('title', __('Tambah Peminjaman'))
@section('header', __('Tambah Peminjaman'))
@section('subheader', __('Masukkan detail peminjaman baru.'))

@section('content')
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
        <form action="{{ route('admin.rentals.store') }}" method="POST">
            @include('admin.rentals._form', ['submitLabel' => __('Simpan')])
        </form>
    </div>
@endsection