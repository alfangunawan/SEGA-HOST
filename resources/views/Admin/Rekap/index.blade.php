@extends('admin.layouts.app')

@section('title', __('Rekap Peminjaman'))
@section('header', __('Rekap Peminjaman'))
@section('subheader', __('Daftar peminjaman yang sudah selesai beserta ringkasan pendapatan.'))

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
                <p class="text-sm font-medium text-indigo-700">{{ __('Total Peminjaman Selesai') }}</p>
                <p class="mt-2 text-2xl font-semibold text-indigo-900">{{ number_format($summary['count']) }}</p>
            </div>
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-sm font-medium text-emerald-700">{{ __('Total Pendapatan') }}</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-900">Rp {{ number_format($summary['revenue'], 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" placeholder="{{ __('Cari nama penyewa atau unit...') }}"
                        value="{{ $search }}"
                        class="w-64 rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                        </svg>
                    </span>
                </div>
                <input type="text" name="date_range" placeholder="{{ __('YYYY-MM-DD to YYYY-MM-DD') }}"
                    value="{{ $dateRange }}"
                    class="w-56 rounded-md border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    aria-describedby="date_range_help">
                <span id="date_range_help" class="sr-only">{{ __('Gunakan format tanggal: 2025-01-01 to 2025-01-31') }}</span>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    {{ __('Filter') }}
                </button>
                @if ($search || $dateRange)
                    <a href="{{ route('admin.rekap.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700">{{ __('Reset') }}</a>
                @endif
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Penyewa') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Periode') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Denda') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Biaya') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rentals as $rental)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $rental->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $rental->unit->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ optional($rental->start_date)->translatedFormat('d M Y') }}
                                &ndash;
                                {{ optional($rental->end_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @php
                                    $penalty = optional($rental->unit)->penalty;
                                @endphp
                                {{ $penalty && $penalty > 0 ? 'Rp ' . number_format($penalty, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($rental->total_cost, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">{{ __('Belum ada peminjaman yang selesai.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $rentals->links() }}
    </div>
@endsection
