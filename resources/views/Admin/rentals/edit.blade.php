@extends('admin.layouts.app')

@section('title', __('Ubah Peminjaman'))
@section('header', __('Ubah Peminjaman'))
@section('subheader', __('Perbarui detail peminjaman terpilih.'))

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.rentals.update', $rental) }}" method="POST">
            @method('PUT')
            @include('admin.rentals._form', ['submitLabel' => __('Perbarui')])
        </form>
    </div>
@endsection