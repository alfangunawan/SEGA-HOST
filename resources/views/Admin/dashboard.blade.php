@extends('admin.layouts.app')

@section('title', __('Dashboard Admin'))

@section('content')
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-slate-900 dark:border-slate-800">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Halo, :name', ['name' => optional(auth()->user())->name]) }} 👋
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Selamat datang kembali di SEGA HOST Panel.') }}
                </p>
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-slate-900 dark:border-slate-800">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900/30">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Pengguna Terdaftar') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($userCount) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-slate-900 dark:border-slate-800">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900/30">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Server Aktif') }}</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ __('Normal') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-slate-900 dark:border-slate-800">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gray-100 rounded-lg dark:bg-slate-800">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Status Sistem') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">–</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-slate-900 dark:border-slate-800">
            <div class="p-6 border-b border-gray-200 dark:border-slate-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Aktivitas Terbaru') }}</h2>
            </div>
            <div class="p-6">
                @if($recentRentals->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentRentals as $rental)
                            <div
                                class="flex items-start gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition-colors">
                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $rental->user->name ?? 'Unknown' }} - {{ $rental->unit->name ?? 'Unknown Unit' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $rental->created_at->format('H:i A') }} –
                                        @if($rental->status === 'ongoing')
                                            {{ __('Sedang berjalan') }}
                                        @elseif($rental->status === 'returned')
                                            {{ __('Dikembalikan') }}
                                        @elseif($rental->status === 'late')
                                            {{ __('Terlambat') }}
                                        @else
                                            {{ ucfirst($rental->status) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                                            @if($rental->status === 'ongoing') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                            @elseif($rental->status === 'returned') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                                            @elseif($rental->status === 'late') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                                            @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                                            @endif">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">
                        {{ __('Belum ada aktivitas terbaru.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection