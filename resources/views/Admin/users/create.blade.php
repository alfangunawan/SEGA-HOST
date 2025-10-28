@extends('admin.layouts.app')

@section('title', __('Tambah Pengguna'))
@section('header', __('Tambah Pengguna'))
@section('subheader', __('Buat akun baru untuk memberikan akses ke sistem.'))

@section('content')
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('admin.users._form', ['submitLabel' => __('Simpan')])
        </form>
    </div>
@endsection