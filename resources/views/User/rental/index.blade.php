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
                                                @case('returned_early')
                                                    bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
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
                                            @switch($rental->status)
                                                @case('returned_early')
                                                    Selesai Lebih Awal
                                                    @break
                                                @case('completed')
                                                    Selesai
                                                    @break
                                                @default
                                                    {{ ucfirst($rental->status) }}
                                            @endswitch
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

                                @if($rental->status === 'overdue')
                                    <!-- Info Denda Keterlambatan -->
                                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <span class="text-sm text-red-700 dark:text-red-300">
                                                    Denda keterlambatan ({{ $rental->daysOverdue() }} hari):
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-semibold text-red-700 dark:text-red-300">
                                                    Rp {{ number_format($rental->penalty_cost > 0 ? $rental->penalty_cost : $rental->calculatePenalty(), 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-red-600 dark:text-red-400">
                                                    (Rp 5.000/hari)
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Total yang harus dibayar -->
                                        <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-700">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-red-700 dark:text-red-300">Total yang harus dibayar:</span>
                                                <span class="text-lg font-bold text-red-800 dark:text-red-200">
                                                    Rp {{ number_format($rental->total_cost + ($rental->penalty_cost > 0 ? $rental->penalty_cost : $rental->calculatePenalty()), 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                (Biaya rental: Rp {{ number_format($rental->total_cost, 0, ',', '.') }} + Denda: Rp {{ number_format($rental->penalty_cost > 0 ? $rental->penalty_cost : $rental->calculatePenalty(), 0, ',', '.') }})
                                            </div>
                                        </div>
                                    </div>
                                @elseif($rental->penalty_cost > 0)
                                    <!-- Info Denda untuk status completed/returned_early yang pernah overdue -->
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

                                @if($rental->penalty_cost < 0)
                                    <!-- Info Refund untuk early return -->
                                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-green-700 dark:text-green-300">
                                                Refund pengembalian lebih awal: <span class="font-semibold">Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}</span>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Dibuat: {{ $rental->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <!-- Tombol Server Detail - untuk semua status -->
                                        <a href="{{ route('products.show', $rental->unit) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                            Server Detail
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
                                            <!-- Tombol Kelola Server - untuk rental aktif -->
                                            <a href="{{ route('rentals.show', $rental) }}" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                                                Kelola Server
                                            </a>
                                        @endif
                                        
                                        @if($rental->status === 'overdue')
                                            <!-- Tombol Detail Penyewaan - untuk rental terlambat -->
                                            <a href="{{ route('rentals.show', $rental) }}" 
                                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Detail Penyewaan
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

    <!-- Early Return Modal (untuk rental aktif) -->
    <div id="earlyReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Kembalikan Lebih Awal
                    </h3>
                    <button onclick="closeEarlyReturnModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" id="earlyReturnForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian lebih awal *
                        </label>
                        <textarea id="return_reason" name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan Anda..."></textarea>
                    </div>

                    <div class="mb-4 p-3 bg-orange-50 dark:bg-orange-900 rounded-lg" id="refundInfo">
                        <!-- Refund info akan diisi via JavaScript -->
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_return" value="1" required
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                Saya konfirmasi untuk mengembalikan server ini lebih awal.
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEarlyReturnModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">
                            Konfirmasi Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Overdue Return Modal (untuk rental terlambat) -->
    <div id="overdueReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Kembalikan Server Terlambat
                    </h3>
                    <button onclick="closeOverdueReturnModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" id="overdueReturnForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="overdue_return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian *
                        </label>
                        <textarea id="overdue_return_reason" name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan Anda..."></textarea>
                    </div>

                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 rounded-lg" id="penaltyInfo">
                        <!-- Penalty info akan diisi via JavaScript -->
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_penalty" value="1" required
                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                Saya memahami dan menyetujui pembayaran denda keterlambatan.
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeOverdueReturnModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Kembalikan dengan Denda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let currentRentalId = null;

    function openEarlyReturnModal(rentalId) {
        currentRentalId = rentalId;
        document.getElementById('earlyReturnModal').classList.remove('hidden');
        
        // Set form action
        const form = document.getElementById('earlyReturnForm');
        form.action = `/rentals/${rentalId}/early-return`;
        
        // Calculate and display refund info (simplified - you might want to fetch actual data)
        const refundInfo = document.getElementById('refundInfo');
        refundInfo.innerHTML = `
            <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Informasi Refund</h4>
            <div class="text-sm text-orange-700 dark:text-orange-300">
                <p>• Refund 80% dari hari yang tidak terpakai</p>
                <p>• Biaya administrasi 20%</p>
                <p class="mt-2 text-xs">Refund akan diproses dalam 3-5 hari kerja.</p>
            </div>
        `;
    }

    function closeEarlyReturnModal() {
        document.getElementById('earlyReturnModal').classList.add('hidden');
        document.getElementById('earlyReturnForm').reset();
        currentRentalId = null;
    }

    function openOverdueReturnModal(rentalId) {
        currentRentalId = rentalId;
        document.getElementById('overdueReturnModal').classList.remove('hidden');
        
        // Set form action - create new route for overdue return
        const form = document.getElementById('overdueReturnForm');
        form.action = `/rentals/${rentalId}/overdue-return`;
        
        // Display penalty info (you might want to fetch actual data)
        const penaltyInfo = document.getElementById('penaltyInfo');
        penaltyInfo.innerHTML = `
            <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Informasi Denda</h4>
            <div class="text-sm text-red-700 dark:text-red-300">
                <p>• Denda: Rp 5.000 per hari keterlambatan</p>
                <p>• Total denda akan ditambahkan ke tagihan</p>
                <p>• Pembayaran wajib dilakukan dalam 7 hari</p>
                <p class="mt-2 text-xs font-semibold">Dengan mengembalikan server, Anda menyetujui pembayaran denda.</p>
            </div>
        `;
    }

    function closeOverdueReturnModal() {
        document.getElementById('overdueReturnModal').classList.add('hidden');
        document.getElementById('overdueReturnForm').reset();
        currentRentalId = null;
    }

    // Close modals when clicking outside
    document.getElementById('earlyReturnModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEarlyReturnModal();
        }
    });

    document.getElementById('overdueReturnModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeOverdueReturnModal();
        }
    });
    </script>
</x-app-layout>