<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Riwayat Penyewaan
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3 items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter:</span>
                        <a href="{{ route('rentals.index') }}" 
                           class="px-4 py-2 {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            Semua
                        </a>
                        <a href="{{ route('rentals.index', ['status' => 'active']) }}" 
                           class="px-4 py-2 {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            Aktif
                        </a>
                        <a href="{{ route('rentals.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 {{ request('status') === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            Pending
                        </a>
                        <a href="{{ route('rentals.index', ['status' => 'overdue']) }}" 
                           class="px-4 py-2 {{ request('status') === 'overdue' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            Terlambat
                        </a>
                        <a href="{{ route('rentals.index', ['status' => 'completed']) }}" 
                           class="px-4 py-2 {{ request('status') === 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600' }} rounded-lg text-sm font-medium transition-colors">
                            Selesai
                        </a>
                    </div>
                </div>
            </div>

            <!-- Rentals List -->
            @if($rentals->count() > 0)
                <div class="space-y-4">
                    @foreach($rentals as $rental)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $rental->unit->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rental->unit->code }} • #{{ $rental->id }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            @switch($rental->status)
                                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                                @case('active') bg-green-100 text-green-800 @break
                                                @case('completed') bg-blue-100 text-blue-800 @break
                                                @case('returned_early') bg-purple-100 text-purple-800 @break
                                                @case('cancelled') bg-gray-100 text-gray-800 @break
                                                @case('overdue') bg-red-100 text-red-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            @switch($rental->status)
                                                @case('returned_early') Selesai Lebih Awal @break
                                                @case('completed') Selesai @break
                                                @case('pending') Pending @break
                                                @case('active') Aktif @break
                                                @case('overdue') Terlambat @break
                                                @case('cancelled') Dibatalkan @break
                                                @default {{ ucfirst($rental->status) }}
                                            @endswitch
                                        </span>
                                        @if($rental->status === 'overdue')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                {{ $rental->daysOverdue() }} hari
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Info Grid with Icons -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</div>
                                            <div class="text-sm font-medium">{{ $rental->start_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal Berakhir</div>
                                            <div class="text-sm font-medium">{{ $rental->end_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Durasi</div>
                                            <div class="text-sm font-medium">{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} hari</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Biaya</div>
                                            <div class="text-sm font-semibold text-blue-600">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- IP Address (Active/Overdue only) -->
                                @if(in_array($rental->status, ['active', 'overdue']) && $rental->unit->ip_address)
                                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c5 0 9-4 9-9m-9 9c-5 0-9-4-9-9m9 9V3m0 18V3"></path>
                                            </svg>
                                            <span class="text-blue-700 dark:text-blue-300">IP: </span>
                                            <code class="ml-1 font-mono font-semibold text-blue-800 dark:text-blue-200">{{ $rental->unit->ip_address }}</code>
                                        </div>
                                    </div>
                                @endif

                                <!-- Overdue Warning -->
                                @if($rental->status === 'overdue')
                                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-sm text-red-700 dark:text-red-300">
                                                    <span class="font-semibold">Denda: Rp {{ number_format($rental->calculatePenalty(), 0, ',', '.') }}</span>
                                                    <div class="text-xs">{{ $rental->daysOverdue() }} hari × Rp 5.000 (belum dibayar)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Penalty Paid Info -->
                                @if($rental->penalty_cost > 0)
                                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-blue-700 dark:text-blue-300">
                                                Denda: <span class="font-semibold">Rp {{ number_format($rental->penalty_cost, 0, ',', '.') }}</span>
                                                <span class="text-xs text-green-600 ml-2">✓ Sudah dibayar</span>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Refund Info -->
                                @if($rental->penalty_cost < 0)
                                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="text-green-700 dark:text-green-300">
                                                Refund: <span class="font-semibold">Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}</span>
                                                <span class="text-xs text-blue-600 ml-2">✓ Diproses</span>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Dibuat: {{ $rental->created_at->format('d M Y') }}
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($rental->status === 'pending')
                                            <form method="POST" action="{{ route('rentals.cancel', $rental) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin membatalkan?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(in_array($rental->status, ['active', 'overdue']))
                                            <a href="{{ route('rentals.show', $rental) }}" 
                                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Kelola Server
                                            </a>
                                        @else
                                            <a href="{{ route('rentals.show', $rental) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($rentals->hasPages())
                    <div class="mt-6">
                        {{ $rentals->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            @if(request('status'))
                                Tidak ada rental dengan status "{{ request('status') }}"
                            @else
                                Belum ada riwayat penyewaan
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request('status'))
                                Coba filter dengan status lain atau lihat semua rental.
                            @else
                                Mulai sewa server untuk melihat riwayat di sini.
                            @endif
                        </p>
                        <div class="flex gap-3 justify-center">
                            @if(request('status'))
                                <a href="{{ route('rentals.index') }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Lihat Semua
                                </a>
                            @endif
                            <a href="{{ route('products.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                               Sewa Server
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>