@extends('admin.layouts.app')

@section('title', __('Ubah Kategori'))
@section('header', __('Ubah Kategori'))
@section('subheader', __('Perbarui informasi kategori terpilih.'))

@section('content')
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('admin.categories._form', ['submitLabel' => __('Perbarui')])
        </form>
    </div>
@endsection