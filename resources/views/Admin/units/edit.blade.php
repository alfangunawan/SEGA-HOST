@extends('admin.layouts.app')

@section('title', __('Ubah Unit'))
@section('header', __('Ubah Unit / Server'))
@section('subheader', __('Perbarui detail unit dan kategori yang terkait.'))

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.units.update', $unit) }}" method="POST">
            @method('PUT')
            @include('admin.units._form', ['submitLabel' => __('Perbarui')])
        </form>
    </div>
@endsection