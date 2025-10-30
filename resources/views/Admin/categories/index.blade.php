@extends('admin.layouts.app')

@section('title', __('Kelola Kategori'))
@section('header', __('Kategori'))
@section('subheader', __('Tambahkan, ubah, atau hapus kategori layanan.'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Daftar Kategori') }}</h2>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Tambah Kategori') }}
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/60">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">{{ __('Nama') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">{{ __('Slug') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">{{ __('Deskripsi') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 dark:text-gray-300">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">{{ $category->slug }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">{{ $category->description ? \Illuminate\Support\Str::limit($category->description, 80) : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-500 font-medium dark:text-indigo-300 dark:hover:text-indigo-200">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('Hapus kategori ini?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-500 font-medium dark:text-rose-300 dark:hover:text-rose-200">{{ __('Hapus') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">{{ __('Belum ada kategori yang ditambahkan.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dark:text-gray-300">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
