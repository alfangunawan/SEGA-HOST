@extends('admin.layouts.app')

@section('title', __('Kelola Unit'))
@section('header', __('Unit / Server'))
@section('subheader', __('Kelola unit server dan hubungkan dengan kategori yang sesuai.'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form action="{{ route('admin.units.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" placeholder="{{ __('Cari unit berdasarkan nama...') }}"
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
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    {{ __('Cari') }}
                </button>
                @if ($search)
                    <a href="{{ route('admin.units.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700">{{ __('Reset') }}</a>
                @endif
            </form>

            <a href="{{ route('admin.units.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Tambah Unit') }}
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Kode') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Nama') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Harga / Hari') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Kategori') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            {{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($units as $unit)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $unit->code }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $unit->name }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            @class([
                                                'bg-emerald-100 text-emerald-700' => $unit->status === 'available',
                                                'bg-amber-100 text-amber-700' => $unit->status === 'maintenance',
                                                'bg-rose-100 text-rose-700' => $unit->status === 'rented',
                                            ])">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp
                                {{ number_format($unit->price_per_day, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $unit->categories->isNotEmpty() ? $unit->categories->pluck('name')->implode(', ') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.units.edit', $unit) }}"
                                        class="text-indigo-600 hover:text-indigo-500 font-medium">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Hapus unit ini?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-600 hover:text-rose-500 font-medium">{{ __('Hapus') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                {{ __('Belum ada unit yang ditambahkan.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $units->links() }}
    </div>
@endsection