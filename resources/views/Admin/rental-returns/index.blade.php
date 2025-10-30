@php
    use Illuminate\Support\Str;
@endphp

@extends('admin.layouts.app')

@section('title', __('Pengembalian'))
@section('header', __('Pengajuan Pengembalian'))
@section('subheader', __('Tinjau dan proses permintaan pengembalian server dari penyewa.'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div
                class="rounded-md bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.return-requests.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="search" placeholder="{{ __('Cari penyewa atau unit...') }}" value="{{ $search }}"
                    class="w-64 rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                    </svg>
                </span>
            </div>
            <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                {{ __('Filter') }}
            </button>
            @if ($search)
                <a href="{{ route('admin.return-requests.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200">{{ __('Reset') }}</a>
            @endif
        </form>

        <div
            class="bg-white border border-gray-200 rounded-xl overflow-visible shadow-sm dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Penyewa') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Unit') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Periode') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Catatan Penyewa') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40 dark:text-gray-300">
                            {{ __('Aksi') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800">
                    @forelse ($rentals as $rental)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ optional($rental->user)->name ?? '-' }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ optional($rental->user)->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ optional($rental->unit)->name ?? '-' }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Total Biaya:') }} Rp {{ number_format($rental->total_cost, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                                {{ optional($rental->start_date)->translatedFormat('d M Y') }} &ndash;
                                {{ optional($rental->end_date)->translatedFormat('d M Y') }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Diajukan:') }} {{ optional($rental->updated_at)->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $rental->notes ? Str::limit($rental->notes, 120) : __('Tidak ada catatan tambahan.') }}
                            </td>
                            <td class="relative px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                                <div x-data="{ openReject: false }" class="flex items-center gap-2">
                                    <form action="{{ route('admin.return-requests.approve', $rental) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Setujui pengembalian ini? Unit akan tersedia kembali.') }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-100 hover:text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200 dark:hover:bg-emerald-500/20">
                                            {{ __('Terima') }}
                                        </button>
                                    </form>

                                    <button type="button" @click="openReject = true"
                                        class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600 transition hover:bg-rose-100 hover:text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200 dark:hover:bg-rose-500/20">
                                        {{ __('Tolak') }}
                                    </button>

                                    <div x-cloak x-show="openReject"
                                        class="absolute right-0 top-full z-20 mt-2 w-64 rounded-lg border border-rose-200 bg-white p-4 shadow-lg dark:border-rose-500/30 dark:bg-slate-900">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Alasan Penolakan') }}</h4>
                                        <form action="{{ route('admin.return-requests.reject', $rental) }}" method="POST"
                                            class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="reason" rows="3" required
                                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100"
                                                placeholder="{{ __('Tuliskan alasan penolakan...') }}"></textarea>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="openReject = false"
                                                    class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">{{ __('Batal') }}</button>
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-500">{{ __('Kirim') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">
                                {{ __('Tidak ada permintaan pengembalian yang menunggu persetujuan.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dark:text-gray-300">
            {{ $rentals->links() }}
        </div>
    </div>
@endsection
