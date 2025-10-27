<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Penyewaan') }}
            </h2>
            <a href="{{ route('products.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Sewa Server Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('rentals.index') }}">
                        <div class="flex flex-wrap gap-4 items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter status:</span>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('rentals.index') }}" 
                                   class="px-4 py-2 {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-blue-700 hover:text-white transition-colors">
                                    Semua
                                </a>
                                <a href="{{ route('rentals.index', ['status' => 'pending']) }}" 
                                   class="px-4 py-2 {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-yellow-700 hover:text-white transition-colors">
                                    Pending
                                </a>
                                <a href="{{ route('rentals.index', ['status' => 'active']) }}" 
                                   class="px-4 py-2 {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-green-700 hover:text-white transition-colors">
                                    Aktif
                                </a>
                                <a href="{{ route('rentals.index', ['status' => 'completed']) }}" 
                                   class="px-4 py-2 {{ request('status') === 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-blue-700 hover:text-white transition-colors">
                                    Selesai
                                </a>
                                <a href="{{ route('rentals.index', ['status' => 'cancelled']) }}" 
                                   class="px-4 py-2 {{ request('status') === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-white transition-colors">
                                    Dibatalkan
                                </a>
                                <a href="{{ route('rentals.index', ['status' => 'overdue']) }}" 
                                   class="px-4 py-2 {{ request('status') === 'overdue' ? 'bg-red-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-red-700 hover:text-white transition-colors">
                                    Terlambat
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Rental</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['active'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Terlambat</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['overdue'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rentals List -->
            @if($rentals->count() > 0)
                <div class="space-y-6">
                    @foreach($rentals as $rental)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-lg">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $rental->unit->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Rental #{{ $rental->id }} • {{ $rental->unit->code }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            @switch($rental->status)
                                                @case('pending')
                                                    bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                    @break
                                                @case('active')
                                                    bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                    @break
                                                @case('completed')
                                                    bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                    @break
                                                @case('cancelled')
                                                    bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200
                                                    @break
                                                @case('overdue')
                                                    bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                    @break
                                                @default
                                                    bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                        @if($rental->isOverdue())
                                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-semibold rounded-full">
                                                {{ $rental->daysOverdue() }} hari terlambat
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11a2 2 0 00-2-2H10a2 2 0 00-2 2z"></path>
                                        </svg>
                                        <span>Mulai: {{ $rental->start_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11a2 2 0 00-2-2H10a2 2 0 00-2 2z"></path>
                                        </svg>
                                        <span>Selesai: {{ $rental->end_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $rental->start_date->diffInDays($rental->end_date) }} hari</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="font-semibold">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if($rental->unit->ip_address)
                                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c5 0 9-4 9-9m-9 9c-5 0-9-4-9-9m9 9V3m0 18V3"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">IP Address: </span>
                                            <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white ml-1">{{ $rental->unit->ip_address }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($rental->penalty_cost > 0)
                                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <span class="text-sm text-red-700 dark:text-red-300">
                                                Denda keterlambatan: <span class="font-semibold">Rp {{ number_format($rental->penalty_cost, 0, ',', '.') }}</span>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Dibuat: {{ $rental->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('rentals.show', $rental) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                            Detail
                                        </a>
                                        @if($rental->status === 'pending')
                                            <form method="POST" action="{{ route('rentals.cancel', $rental) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        @if($rental->status === 'active')
                                            <a href="{{ route('products.show', $rental->unit) }}" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                                Kelola Server
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $rentals->appends(request()->query())->links() }}
                </div>
            @else
                <!-- No Rentals Message -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            @if(request('status'))
                                Tidak ada rental dengan status {{ request('status') }}
                            @else
                                Belum ada riwayat penyewaan
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request('status'))
                                Coba filter dengan status lain atau lihat semua rental.
                            @else
                                Mulai sewa server untuk melihat riwayat penyewaan di sini.
                            @endif
                        </p>
                        <div class="flex gap-4 justify-center">
                            @if(request('status'))
                                <a href="{{ route('rentals.index') }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Lihat Semua Rental
                                </a>
                            @endif
                            <a href="{{ route('products.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Sewa Server Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>