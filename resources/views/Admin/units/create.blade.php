@extends('admin.layouts.app')

@section('title', __('Tambah Unit'))
@section('header', __('Tambah Unit / Server'))
@section('subheader', __('Masukkan detail unit baru dan tetapkan kategorinya.'))

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.units.store') }}" method="POST">
            @include('admin.units._form', ['submitLabel' => __('Simpan')])
        </form>
    </div>
@endsection