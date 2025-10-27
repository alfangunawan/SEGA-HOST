@extends('admin.layouts.app')

@section('title', __('Ubah Pengguna'))
@section('header', __('Ubah Pengguna'))
@section('subheader', __('Perbarui informasi akun dan peran akses.'))

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @method('PUT')
            @include('admin.users._form', ['submitLabel' => __('Perbarui')])
        </form>
    </div>
@endsection