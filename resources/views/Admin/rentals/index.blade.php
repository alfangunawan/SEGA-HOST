@extends('admin.layouts.app')

@section('title', __('Kelola Peminjaman'))
@section('header', __('Peminjaman'))
@section('subheader', __('Kelola data peminjaman unit dan pantau statusnya.'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form action="{{ route('admin.rentals.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" placeholder="{{ __('Cari penyewa atau unit...') }}"
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
                <select name="status"
                    class="rounded-md border-gray-300 py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ([
                        'ongoing' => __('Sedang Berjalan'),
                        'returned' => __('Dikembalikan'),
                        'late' => __('Terlambat'),
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    {{ __('Filter') }}
                </button>
                @if ($search || $status)
                    <a href="{{ route('admin.rentals.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700">{{ __('Reset') }}</a>
                @endif
            </form>

            <a href="{{ route('admin.rentals.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Tambah Peminjaman') }}
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Penyewa') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Periode') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Denda') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Biaya') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">{{ __('Aksi') }}</th>
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
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @class([
                                        'bg-emerald-100 text-emerald-700' => $rental->status === 'ongoing',
                                        'bg-sky-100 text-sky-700' => $rental->status === 'returned',
                                        'bg-rose-100 text-rose-700' => $rental->status === 'late',
                                    ])">
                                    {{ match ($rental->status) {
                                        'ongoing' => __('Sedang Berjalan'),
                                        'returned' => __('Dikembalikan'),
                                        'late' => __('Terlambat'),
                                        default => $rental->status,
                                    } }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @php
                                    $penalty = optional($rental->unit)->penalty;
                                @endphp
                                {{ $penalty && $penalty > 0 ? 'Rp ' . number_format($penalty, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($rental->total_cost, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.rentals.edit', $rental) }}" class="text-indigo-600 hover:text-indigo-500 font-medium">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.rentals.destroy', $rental) }}" method="POST" onsubmit="return confirm('{{ __('Hapus data peminjaman ini?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-500 font-medium">{{ __('Hapus') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">{{ __('Belum ada data peminjaman.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $rentals->links() }}
    </div>
@endsection
