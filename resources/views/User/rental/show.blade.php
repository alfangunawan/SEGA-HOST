<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Penyewaan') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('rentals.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
                
                @if($rental->status === 'pending')
                    <form method="POST" action="{{ route('rentals.cancel', $rental) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Batalkan
                        </button>
                    </form>
                @endif

                @if($rental->status === 'active')
                    <!-- Early Return Button -->
                    <button onclick="openEarlyReturnModal()" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Kembalikan Lebih Awal
                    </button>
                @endif

                @if($rental->status === 'overdue')
                    <!-- Overdue Return Button -->
                    <button onclick="openOverdueReturnModal()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Kembalikan Server
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Rental Details Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Server Info -->
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                {{ $rental->unit->name }}
                            </h3>
                            
                            <!-- Status and Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $startDate = \Carbon\Carbon::parse($rental->start_date);
                                    $endDate = \Carbon\Carbon::parse($rental->end_date);
                                    
                                    // Durasi total pemakaian (dari start_date sampai hari ini)
                                    $durasiTotal = $startDate->diffInDays($today) + 1;
                                    
                                    // Status overdue jika hari ini > end_date
                                    $isOverdue = $today->greaterThan($endDate);
                                    
                                    // Gunakan method model untuk perhitungan yang konsisten
                                    $hariTerlambat = $rental->daysOverdue();
                                    
                                    // Denda berdasarkan method model
                                    $dendaAmount = $rental->calculatePenalty();
                                @endphp

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</div>
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
                                        {{ ucfirst(str_replace('_', ' ', $rental->status)) }}
                                    </span>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Mulai</div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $rental->start_date->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Berakhir</div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $rental->end_date->format('d M Y') }}
                                        @if($isOverdue)
                                            <div class="text-xs text-red-600 dark:text-red-300 mt-1">
                                                {{ $hariTerlambat }} hari yang lalu
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Durasi Total Pemakaian</div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $durasiTotal }} hari
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Sejak {{ $startDate->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Durasi Terlewat</div>
                                    <div class="font-semibold 
                                        @if($hariTerlambat > 0) 
                                            text-red-600 dark:text-red-400
                                        @else 
                                            text-green-600 dark:text-green-400
                                        @endif">
                                        {{ $hariTerlambat }} hari
                                    </div>
                                    @if($hariTerlambat > 0)
                                        <div class="text-xs text-red-600 dark:text-red-300 mt-1">
                                            Melewati batas rental
                                            <br>
                                            ({{ $hariTerlambat }} hari setelah {{ $endDate->format('d M Y') }})
                                        </div>
                                    @elseif($isOverdue)
                                        <div class="text-xs text-red-600 dark:text-red-300 mt-1">
                                            Rental berakhir hari ini
                                            <br>
                                            ({{ $endDate->format('d M Y') }})
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Rental masih berlangsung
                                            <br>
                                            @php
                                                $sisaHari = $today->diffInDays($endDate);
                                            @endphp
                                            ({{ $sisaHari }} hari tersisa)
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Denda</div>
                                    <div class="font-semibold 
                                        @if($dendaAmount > 0) 
                                            text-red-600 dark:text-red-400
                                        @else 
                                            text-green-600 dark:text-green-400
                                        @endif">
                                    @if($dendaAmount > 0)
                                        Rp {{ number_format($dendaAmount, 0, ',', '.') }}
                                    @else
                                        Tidak ada denda
                                    @endif
                                    </div>
                                    @if($dendaAmount > 0)
                                        <div class="text-xs text-red-600 dark:text-red-300 mt-1">
                                            {{ $hariTerlambat }} hari × Rp 5.000/hari
                                            <br>
                                            (Denda mulai setelah {{ $endDate->format('d M Y') }})
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            @if($isOverdue)
                                                Rental berakhir hari ini, belum ada denda
                                            @else
                                                Rental masih dalam periode yang diizinkan
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($rental->status === 'active')
                                <!-- Early Return Info -->
                                <div class="bg-orange-50 dark:bg-orange-900 border border-orange-200 dark:border-orange-700 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-orange-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-orange-800 dark:text-orange-200 mb-2">
                                                Early Return Available
                                            </h3>
                                            <p class="mt-1 text-sm text-orange-700 dark:text-orange-300">
                                                You can return this server early and get 80% refund for unused days. 
                                                @php
                                                    // PERBAIKAN: Perhitungan hari yang tersisa
                                                    $today = \Carbon\Carbon::today();
                                                    $totalDays = $rental->start_date->diffInDays($rental->end_date) + 1; // Total hari rental
                                                    $usedDays = $rental->start_date->diffInDays($today) + 1; // Hari yang sudah dipakai (termasuk hari ini)
                                                    $unusedDays = max(0, $totalDays - $usedDays); // Hari yang belum dipakai
                                                    $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.8 : 0;
                                                @endphp
                                                @if($unusedDays > 0)
                                                    <strong>Potential refund: Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong><br>
                                                    <small>
                                                        ({{ $unusedDays }} unused days × Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }} × 80% refund)
                                                    </small><br>
                                                    <small class="text-xs">
                                                        Used: {{ $usedDays }} days, Remaining: {{ $unusedDays }} days of {{ $totalDays }} total days
                                                    </small>
                                                @else
                                                    No refund available. You've used all rental days.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($rental->penalty_cost < 0)
                                <!-- Refund Info -->
                                <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                                Refund Processed
                                            </h3>
                                            <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                                Refund amount: <strong>Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}</strong>
                                                will be processed within 3-5 business days.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($rental->penalty_cost > 0)
                                <!-- Penalty Info -->
                                <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                Overdue Penalty Applied
                                            </h3>
                                            <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                                <strong>Penalty: Rp {{ number_format($rental->penalty_cost, 0, ',', '.') }}</strong><br>
                                                Reason: Server returned late (Rp 5,000 per day overdue)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Pricing Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Rincian Biaya</h4>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Harga per hari</span>
                                    <span class="font-medium">Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Durasi rental awal</span>
                                    <span class="font-medium">{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} hari</span>
                                </div>
                                
                                @if($rental->status === 'active')
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Hari terpakai</span>
                                        <span class="font-medium">{{ $rental->start_date->diffInDays(\Carbon\Carbon::today()) + 1 }} hari</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Hari tersisa</span>
                                        <span class="font-medium">{{ max(0, ($rental->start_date->diffInDays($rental->end_date) + 1) - ($rental->start_date->diffInDays(\Carbon\Carbon::today()) + 1)) }} hari</span>
                                    </div>
                                @endif

                                @if($rental->status === 'overdue' || ($rental->status === 'active' && $isOverdue) || $dendaAmount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Durasi total pemakaian</span>
                                        <span class="font-medium">{{ $durasiTotal }} hari</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Hari terlambat (setelah {{ $endDate->format('d M Y') }})</span>
                                        <span class="font-medium text-red-600">{{ $hariTerlambat }} hari</span>
                                    </div>
                                @endif
                                
                                <hr class="border-gray-300 dark:border-gray-600">
                                
                                <!-- Biaya Rental (SUDAH DIBAYAR) -->
                                <div class="flex justify-between">
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        Biaya Rental 
                                        <div class="text-xs text-green-600 dark:text-green-400 font-normal">
                                            ✓ Sudah dibayar
                                        </div>
                                    </span>
                                    <span class="text-gray-900 dark:text-white font-medium">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</span>
                                </div>
                                
                                <!-- Denda Real-time (BELUM DIBAYAR jika overdue) -->
                                @if($dendaAmount > 0 && $rental->status === 'overdue')
                                    <div class="flex justify-between text-red-600">
                                        <span class="font-medium">
                                            Denda Keterlambatan
                                            <div class="text-xs text-red-500 font-normal">
                                                {{ $hariTerlambat }} hari × Rp 5.000/hari
                                                <br>
                                                ⚠️ Belum dibayar
                                            </div>
                                        </span>
                                        <span class="font-medium">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <!-- Denda/Refund yang sudah tercatat di database (SUDAH DIPROSES) -->
                                @if($rental->penalty_cost != 0)
                                    <div class="flex justify-between {{ $rental->penalty_cost < 0 ? 'text-green-600' : 'text-blue-600' }}">
                                        <span class="font-medium">
                                            {{ $rental->penalty_cost < 0 ? 'Refund Pengembalian Awal' : 'Denda Keterlambatan' }}
                                            <div class="text-xs {{ $rental->penalty_cost < 0 ? 'text-green-500' : 'text-blue-500' }} font-normal">
                                                {{ $rental->penalty_cost < 0 ? '✓ Sudah dikreditkan' : '✓ Sudah dibayar' }}
                                            </div>
                                        </span>
                                        <span class="font-medium">
                                            {{ $rental->penalty_cost < 0 ? '+' : '' }}Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Pemisah jika ada biaya yang belum dibayar -->
                                @if($dendaAmount > 0 && $rental->status === 'overdue')
                                    <hr class="border-red-300 dark:border-red-600">
                                    
                                    <!-- Total Yang Harus Dibayar (HANYA DENDA) -->
                                    <div class="flex justify-between font-bold text-lg text-red-600">
                                        <span class="text-red-700 dark:text-red-300">
                                            Yang Harus Dibayar Sekarang
                                            <div class="text-xs font-normal text-red-500">
                                                Denda keterlambatan
                                            </div>
                                        </span>
                                        <span class="text-red-700 dark:text-red-300">
                                            Rp {{ number_format($dendaAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    <hr class="border-gray-300 dark:border-gray-600">
                                    
                                    <!-- Status Pembayaran Lengkap -->
                                    <div class="flex justify-between font-bold text-lg text-green-600">
                                        <span class="text-green-700 dark:text-green-300">
                                            Status Pembayaran
                                            <div class="text-xs font-normal text-green-500">
                                                Semua biaya sudah lunas
                                            </div>
                                        </span>
                                        <span class="text-green-700 dark:text-green-300">
                                            ✓ Lunas
                                        </span>
                                    </div>
                                @endif

                                <!-- Info tambahan untuk status tertentu -->
                                @if(($rental->status === 'overdue' || $isOverdue) && $hariTerlambat > 0)
                                    <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <div class="text-xs text-red-700 dark:text-red-300">
                                            <strong>Peringatan:</strong> Denda akan terus bertambah Rp 5.000 setiap hari hingga server dikembalikan.
                                            <br>
                                            <strong>Catatan:</strong> Biaya rental sudah dibayar, hanya denda yang perlu diselesaikan.
                                            <br>
                                            Rental berakhir: {{ $endDate->format('d M Y') }}
                                            <br>
                                            Hari terlewat: {{ $hariTerlambat }} hari
                                            <br>
                                            Denda saat ini: Rp {{ number_format($dendaAmount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @elseif($rental->status === 'active' && ($isOverdue || $endDate->diffInDays($today) <= 1))
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                        <div class="text-xs text-yellow-700 dark:text-yellow-300">
                                            <strong>Perhatian:</strong> 
                                            @if($isOverdue)
                                                Server telah melewati tanggal akhir rental ({{ $endDate->format('d M Y') }}). 
                                                Denda Rp 5.000/hari akan dikenakan sebagai biaya tambahan.
                                            @elseif($today->isSameDay($endDate))
                                                Hari ini adalah hari terakhir rental Anda. Kembalikan server sebelum tengah malam untuk menghindari denda.
                                            @else
                                                Rental Anda akan berakhir pada {{ $endDate->format('d M Y') }} 
                                                ({{ $endDate->diffInDays($today) }} hari lagi). 
                                                Denda Rp 5.000/hari akan dikenakan sebagai biaya tambahan jika tidak dikembalikan tepat waktu.
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($rental->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Catatan</h4>
                            <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $rental->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Server Management Section (Only for Active Rentals) -->
            @if($rental->status === 'active')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Kelola Server
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Server Access Information -->
                        <div class="space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Informasi Akses Server
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">IP Address:</span>
                                        <div class="flex items-center space-x-2">
                                            <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100" id="server-ip">
                                                {{ $rental->unit->ip_address ?? '192.168.1.100' }}
                                            </code>
                                            <button onclick="copyToClipboard('server-ip')" class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">SSH Port:</span>
                                        <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100">
                                            {{ $rental->unit->ssh_port ?? '22' }}
                                        </code>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Username:</span>
                                        <div class="flex items-center space-x-2">
                                            <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100" id="server-username">
                                                {{ $rental->unit->username ?? 'root' }}
                                            </code>
                                            <button onclick="copyToClipboard('server-username')" class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Password:</span>
                                        <div class="flex items-center space-x-2">
                                            <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100" id="server-password">
                                                {{ $rental->unit->password ?? '••••••••' }}
                                            </code>
                                            <button onclick="copyToClipboard('server-password')" class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Quick Actions
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <button onclick="restartServer()" class="flex items-center justify-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Restart
                                    </button>
                                    <button onclick="shutdownServer()" class="flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Shutdown
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Server Status & Monitoring -->
                        <div class="space-y-4">
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-200 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 012 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Status Server
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-green-600 font-medium">Online</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Uptime:</span>
                                        <span class="font-medium">{{ rand(1, 30) }} hari</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">CPU Usage:</span>
                                        <span class="font-medium">{{ rand(10, 80) }}%</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">RAM Usage:</span>
                                        <span class="font-medium">{{ rand(2, 6) }}/{{ $rental->unit->ram ?? '8' }}GB</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Storage:</span>
                                        <span class="font-medium">{{ rand(10, 80) }}/{{ $rental->unit->storage ?? '100' }}GB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Server Links -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    Links & Tools
                                </h4>
                                <div class="space-y-2">
                                    <a href="#" class="block w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors text-center">
                                        Open Control Panel
                                    </a>
                                    <a href="#" class="block w-full px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors text-center">
                                        File Manager
                                    </a>
                                    <a href="#" class="block w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors text-center">
                                        Database Manager
                                    </a>
                                    <button type="button"
                                            id="configurationToggleButton"
                                            class="block w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors text-center"
                                            aria-controls="configurationSection"
                                            aria-expanded="false"
                                            onclick="toggleConfigurationSection()">
                                        Konfigurasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $unit = $rental->unit;
                        $configurationProfile = $unit->configurationProfile;
                        $configurationValues = $unit->configurationValues->loadMissing('field')->keyBy('configuration_field_id');
                        $fields = collect();

                        if ($configurationProfile) {
                            $fields = $configurationProfile->fields->sortBy('label')->values();
                        } elseif ($configurationValues->isNotEmpty()) {
                            $fields = $configurationValues->map(function ($value) {
                                return $value->field;
                            })->filter()->sortBy('label')->values();
                        }
                    @endphp

                    <!-- Konfigurasi -->
                    <div id="configurationSection" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 hidden">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-0 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-1.14 1.938-1.14 2.238 0a1.724 1.724 0 002.573 1.066c1.013-.588 2.223.621 1.636 1.636a1.724 1.724 0 001.066 2.572c1.14.3 1.14 1.938 0 2.238a1.724 1.724 0 00-1.066 2.573c.588 1.013-.622 2.223-1.636 1.636a1.724 1.724 0 00-2.572 1.066c-.3 1.14-1.939 1.14-2.238 0a1.724 1.724 0 00-2.573-1.066c-1.013.588-2.223-.622-1.636-1.636a1.724 1.724 0 00-1.066-2.572c-1.14-.3-1.14-1.939 0-2.238a1.724 1.724 0 001.066-2.573c-.588-1.013.622-2.223 1.636-1.636.95.552 2.175.02 2.573-1.066z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Konfigurasi Server
                            </h4>

                            @if($configurationProfile)
                                <div class="flex items-center gap-3 rounded-lg border border-indigo-200 dark:border-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-2 text-sm text-indigo-700 dark:text-indigo-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-left">
                                        <p class="font-semibold leading-tight">{{ $configurationProfile->name }}</p>
                                        @if($configurationProfile->description)
                                            <p class="text-xs text-indigo-500 dark:text-indigo-300 leading-tight">
                                                {{ $configurationProfile->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Detail konfigurasi yang diterapkan pada server ini. Gunakan tombol Konfigurasi untuk menyembunyikan atau menampilkan bagian ini.
                        </p>

                        @if($fields->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($fields as $field)
                                    @php
                                        $valueModel = $configurationValues->get($field->id);
                                        $rawValue = $valueModel?->value;
                                        $value = filled($rawValue) ? $rawValue : $field->default_value;

                                        if (is_array($value)) {
                                            $value = collect($value)->filter(fn ($item) => filled($item))->implode(', ');
                                        } elseif (is_bool($value)) {
                                            $value = $value ? 'Ya' : 'Tidak';
                                        }

                                        $hasValue = filled($value);
                                        $helpText = $field->meta['help'] ?? null;
                                    @endphp
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 p-4 space-y-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $field->label }}@if($field->is_required)<span class="text-rose-500">*</span>@endif
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Key: {{ $field->key }}</p>
                                        </div>

                                        <div class="rounded-md bg-white dark:bg-gray-900/60 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line break-words">
                                            {{ $hasValue ? $value : 'Belum diatur' }}
                                        </div>

                                        @if($helpText)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $helpText }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/40 p-6 text-center text-sm text-gray-600 dark:text-gray-300">
                                Konfigurasi belum tersedia untuk server ini.
                            </div>
                        @endif
                    </div>

                    <!-- SSH Connection Guide -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Panduan Koneksi SSH
                        </h4>
                        <div class="bg-gray-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                            <div class="mb-2"># Windows (menggunakan PowerShell atau Command Prompt)</div>
                            <div class="mb-4 text-white">ssh {{ $rental->unit->username ?? 'root' }}@{{ $rental->unit->ip_address ?? '192.168.1.100' }}</div>
                            
                            <div class="mb-2"># Linux/macOS (menggunakan Terminal)</div>
                            <div class="text-white">ssh {{ $rental->unit->username ?? 'root' }}@{{ $rental->unit->ip_address ?? '192.168.1.100' }}</div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Masukkan password ketika diminta. Untuk keamanan, password tidak akan terlihat saat Anda mengetik.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Server Management Section (Only for Overdue Rentals) -->
            @if($rental->status === 'overdue')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Server Terlambat - Perlu Dikembalikan
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Server Access Information -->
                        <div class="space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h4 class="font-semibold text-red-900 dark:text-red-200 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Peringatan Denda Keterlambatan
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-red-700 dark:text-red-300">Tanggal berakhir:</span>
                                        <span class="font-semibold text-red-800 dark:text-red-200">{{ $endDate->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-red-700 dark:text-red-300">Hari terlambat:</span>
                                        <span class="font-semibold text-red-800 dark:text-red-200">{{ $hariTerlambat }} hari</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-red-700 dark:text-red-300">Denda (Rp 5.000/hari):</span>
                                        <span class="font-bold text-red-800 dark:text-red-200 text-lg">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-red-300 pt-2">
                                        <span class="text-red-700 dark:text-red-300">Total yang harus dibayar:</span>
                                        <span class="font-bold text-red-800 dark:text-red-200 text-lg">
                                            Rp {{ number_format($rental->total_cost + $dendaAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-red-100 dark:bg-red-800 rounded-lg">
                                    <p class="text-xs text-red-800 dark:text-red-200">
                                        <strong>Perhatian:</strong> Denda akan terus bertambah setiap hari setelah tanggal berakhir rental (
                                    </p>
                                </div>
                            </div>

                            <!-- Server Access Info (masih bisa diakses) -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Akses Server (Masih Aktif)
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">IP Address:</span>
                                        <div class="flex items-center space-x-2">
                                            <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100" id="server-ip-overdue">
                                                {{ $rental->unit->ip_address ?? '192.168.1.100' }}
                                            </code>
                                            <button onclick="copyToClipboard('server-ip-overdue')" class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Username:</span>
                                        <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100">
                                            {{ $rental->unit->username ?? 'root' }}
                                        </code>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Password:</span>
                                        <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded font-mono text-gray-900 dark:text-gray-100">
                                            {{ $rental->unit->password ?? '••••••••' }}
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Server Action -->
                        <div class="space-y-4">
                            <!-- Return Server Card -->
                            <div class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <h4 class="font-semibold text-red-900 dark:text-red-200 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Kembalikan Server Sekarang
                                </h4>
                                <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                                    Server Anda sudah melewati batas waktu penyewaan. Segera kembalikan untuk menghindari denda yang lebih besar.
                                </p>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-red-600 dark:text-red-400">Saldo saat ini:</span>
                                        <span class="font-semibold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-red-600 dark:text-red-400">Denda yang akan dipotong:</span>
                                        <span class="font-semibold">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm border-t border-red-300 pt-2">
                                        <span class="text-red-700 dark:text-red-300 font-medium">Saldo setelah denda:</span>
                                        <span class="font-bold {{ (Auth::user()->balance - $dendaAmount) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            Rp {{ number_format(Auth::user()->balance - $dendaAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <button onclick="openOverdueReturnModal()" 
                                        class="w-full mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Kembalikan Server & Bayar Denda
                                </button>
                            </div>

                            <!-- Server Status -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <h4 class="font-semibold text-yellow-900 dark:text-yellow-200 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status Server
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="flex items-center">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                            <span class="text-red-600 font-medium">Overdue</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Akses:</span>
                                        <span class="text-green-600 font-medium">Masih Aktif</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Uptime:</span>
                                        <span class="font-medium">{{ $durasiTotal }} hari</span>
                                    </div>
                                </div>
                                <div class="mt-3 p-2 bg-yellow-100 dark:bg-yellow-800 rounded text-xs text-yellow-800 dark:text-yellow-200">
                                    <strong>Catatan:</strong> Server masih dapat diakses hingga dikembalikan, namun denda akan terus bertambah setiap hari setelah {{ $endDate->format('d M Y') }}.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SSH Connection Guide (masih tersedia) -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Panduan Koneksi SSH (Masih Aktif)
                        </h4>
                        <div class="bg-gray-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-x-auto">
                            <div class="mb-2"># Koneksi masih tersedia hingga server dikembalikan</div>
                            <div class="text-white">ssh {{ $rental->unit->username ?? 'root' }}@{{ $rental->unit->ip_address ?? '192.168.1.100' }}</div>
                        </div>
                        <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
                            <p class="text-sm text-red-700 dark:text-red-300">
                                <strong>Peringatan:</strong> Meskipun server masih dapat diakses, Anda tetap dikenakan denda keterlambatan sejak {{ $endDate->format('d M Y') }}. 
                                Segera kembalikan server untuk menghentikan penambahan denda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Early Return Modal -->
    <div id="earlyReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Early Return Confirmation
                    </h3>
                    <button onclick="closeEarlyReturnModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('rentals.early-return', $rental) }}" id="earlyReturnForm">
                    @csrf
                    @method('PATCH')  {{-- Tambahkan ini --}}
                    
                    <div class="mb-4">
                        <label for="return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian lebih awal *
                        </label>
                        <textarea id="return_reason" name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan Anda ingin mengembalikan server lebih awal..."></textarea>
                    </div>

                    {{-- Perbaikan modal early return --}}
                    @php
                        $today = \Carbon\Carbon::today();
                        $totalDays = $rental->start_date->diffInDays($rental->end_date) + 1;
                        $usedDays = $rental->start_date->diffInDays($today) + 1;
                        $unusedDays = max(0, $totalDays - $usedDays);
                        $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.8 : 0;
                        $processingFee = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.2 : 0;
                    @endphp

                    <div class="mb-4 p-3 bg-orange-50 dark:bg-orange-900 rounded-lg">
                        <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Refund Information</h4>
                        <div class="text-sm text-orange-700 dark:text-orange-300">
                            @if($unusedDays > 0)
                                <p>• Total rental days: {{ $totalDays }} days</p>
                                <p>• Days used (including today): {{ $usedDays }} days</p>
                                <p>• Unused days: {{ $unusedDays }} days</p>
                                <p>• Original cost for unused days: Rp {{ number_format($rental->unit->price_per_day * $unusedDays, 0, ',', '.') }}</p>
                                <p>• Processing fee (20%): Rp {{ number_format($processingFee, 0, ',', '.') }}</p>
                                <p>• <strong>Refund amount (80%): Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong>
                                </p>
                                <p class="mt-2 text-xs bg-orange-200 dark:bg-orange-700 p-2 rounded">
                                    Refund will be processed within 3-5 business days.
                                </p>
                            @else
                                <p>No refund available. You've used all rental days.</p>
                                <p class="text-xs">Rental period: {{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_return" value="1" required
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                Saya konfirmasi untuk mengembalikan server ini lebih awal dan menyetujui kebijakan refund.
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

    <!-- Overdue Return Modal -->
    <div id="overdueReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Kembalikan Server dengan Denda
                    </h3>
                    <button onclick="closeOverdueReturnModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('rentals.return-with-penalty', $rental) }}" id="overdueReturnForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label for="overdue_return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian *
                        </label>
                        <textarea id="overdue_return_reason" name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan pengembalian server..."></textarea>
                    </div>

                    @php
                        $today = \Carbon\Carbon::today();
                        $endDate = \Carbon\Carbon::parse($rental->end_date);
                        // Gunakan method model untuk konsistensi
                        $hariTerlambat = $rental->daysOverdue();
                        $dendaAmount = $rental->calculatePenalty();
                    @endphp

                    <!-- Penalty Info Display -->
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Informasi Denda</h4>
                        <div class="text-sm text-red-700 dark:text-red-300 space-y-2">
                            <div class="flex justify-between">
                                <span>Tanggal berakhir rental:</span>
                                <span class="font-semibold">{{ $endDate->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Hari terlambat:</span>
                                <span class="font-semibold">{{ $hariTerlambat }} hari</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Denda keterlambatan:</span>
                                <span class="font-semibold">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                            </div>
                            
                            <hr class="border-red-300">
                            
                            <div class="flex justify-between">
                                <span>Saldo saat ini:</span>
                                <span class="font-semibold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-red-300 pt-2">
                                <span>Saldo setelah bayar denda:</span>
                                <span class="font-bold {{ (Auth::user()->balance - $dendaAmount) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format(Auth::user()->balance - $dendaAmount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                            <strong>Catatan:</strong> Biaya rental (Rp {{ number_format($rental->total_cost, 0, ',', '.') }}) sudah dibayar saat penyewaan.
                            <br>
                            Hanya denda keterlambatan yang akan dipotong dari saldo.
                            @if((Auth::user()->balance - $dendaAmount) < 0)
                                <br><strong>Perhatian: Saldo Anda akan menjadi minus!</strong>
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_penalty" value="1" required
                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                Saya memahami dan menyetujui pemotongan denda dari saldo saya.
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
                            Kembalikan & Bayar Denda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.remove('hidden');
        }

        function closeEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.add('hidden');
            document.getElementById('earlyReturnForm').reset();
        }

        function openOverdueReturnModal() {
            document.getElementById('overdueReturnModal').classList.remove('hidden');
        }

        function closeOverdueReturnModal() {
            document.getElementById('overdueReturnModal').classList.add('hidden');
            document.getElementById('overdueReturnForm').reset();
        }

        function toggleConfigurationSection() {
            const section = document.getElementById('configurationSection');
            const trigger = document.getElementById('configurationToggleButton');

            if (!section) {
                return;
            }

            const willShow = section.classList.contains('hidden');

            section.classList.toggle('hidden');

            if (trigger) {
                trigger.setAttribute('aria-expanded', willShow ? 'true' : 'false');
            }

            if (willShow) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            navigator.clipboard.writeText(text).then(() => {
                // Show temporary feedback
                const button = element.nextElementSibling;
                const originalContent = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                setTimeout(() => {
                    button.innerHTML = originalContent;
                }, 2000);
            });
        }

        function restartServer() {
            if (confirm('Are you sure you want to restart the server? This may cause temporary downtime.')) {
                // Simulate server restart
                alert('Server restart initiated. The server will be back online shortly.');
            }
        }

        function shutdownServer() {
            if (confirm('Are you sure you want to shutdown the server? You will need to contact support to restart it.')) {
                // Simulate server shutdown
                alert('Server shutdown initiated. Contact support if you need to restart the server.');
            }
        }

        // Close modal when clicking outside
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