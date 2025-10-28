@extends('admin.layouts.app')

@section('title', __('Template Konfigurasi'))
@section('header', __('Template Konfigurasi Server'))
@section('subheader', __('Kelola kumpulan field konfigurasi yang dapat diterapkan pada unit atau server.'))

@section('content')
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Daftar Template') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Gunakan template untuk mempercepat pengisian konfigurasi teknis pada setiap unit.') }}</p>
            </div>
            <a href="{{ route('admin.configurations.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ __('Template Baru') }}</span>
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/60">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Nama Template') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Slug') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Field') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Diperbarui') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-800">
                    @forelse ($profiles as $profile)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/80">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $profile->name }}</div>
                                    @if ($profile->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($profile->description, 120) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <code class="rounded bg-gray-100 px-2 py-1 text-xs dark:bg-slate-800">{{ $profile->slug }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">{{ $profile->fields_count }}</span>
                                    <div class="flex flex-col text-xs text-gray-500 dark:text-gray-400">
                                        @php
                                            $fieldLabels = $profile->fields->sortBy(fn ($field) => $field->meta['order'] ?? 0)->take(3)->pluck('label');
                                        @endphp
                                        <span>{{ $fieldLabels->implode(', ') }}</span>
                                        @if ($profile->fields_count > 3)
                                            <span>{{ __('dan :count lainnya', ['count' => $profile->fields_count - 3]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $profile->updated_at?->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.configurations.edit', $profile) }}"
                                       class="inline-flex items-center rounded-md border border-transparent bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600">
                                        {{ __('Ubah') }}
                                    </a>
                                    <form action="{{ route('admin.configurations.destroy', $profile) }}" method="POST" class="inline-flex"
                                          onsubmit="return confirm('{{ __('Yakin ingin menghapus template ini? Data field akan ikut terhapus.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-transparent bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-500">
                                            {{ __('Hapus') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Belum ada template konfigurasi. Klik tombol "Template Baru" untuk membuat yang pertama.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $profiles->links() }}
        </div>
    </div>
@endsection
