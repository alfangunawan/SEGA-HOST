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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Durasi</div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $rental->start_date->diffInDays($rental->end_date) }} hari
                                    </div>
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
                                                    $unusedDays = \Carbon\Carbon::today()->diffInDays($rental->end_date, false);
                                                    $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.8 : 0;
                                                @endphp
                                                @if($unusedDays > 0)
                                                    Potential refund: <strong>Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong> 
                                                    ({{ $unusedDays }} unused days)
                                                @else
                                                    No refund available for today's return.
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
                                    <span class="text-gray-600 dark:text-gray-400">Durasi</span>
                                    <span class="font-medium">{{ $rental->start_date->diffInDays($rental->end_date) }} hari</span>
                                </div>
                                <hr class="border-gray-300 dark:border-gray-600">
                                <div class="flex justify-between font-semibold text-lg">
                                    <span class="text-gray-900 dark:text-white">Total</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($rental->penalty_cost != 0)
                                    <div class="flex justify-between {{ $rental->penalty_cost < 0 ? 'text-green-600' : 'text-red-600' }}">
                                        <span>{{ $rental->penalty_cost < 0 ? 'Refund' : 'Penalty' }}</span>
                                        <span class="font-medium">
                                            {{ $rental->penalty_cost < 0 ? '+' : '-' }}Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}
                                        </span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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
                                </div>
                            </div>
                        </div>
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
                    
                    <div class="mb-4">
                        <label for="return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian lebih awal *
                        </label>
                        <textarea id="return_reason" name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan Anda ingin mengembalikan server lebih awal..."></textarea>
                    </div>

                    @php
                        $unusedDays = \Carbon\Carbon::today()->diffInDays($rental->end_date, false);
                        $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.8 : 0;
                    @endphp

                    <div class="mb-4 p-3 bg-orange-50 dark:bg-orange-900 rounded-lg">
                        <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Refund Information</h4>
                        <div class="text-sm text-orange-700 dark:text-orange-300">
                            @if($unusedDays > 0)
                                <p>• Unused days: {{ $unusedDays }} days</p>
                                <p>• Refund amount (80%): <strong>Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong></p>
                                <p>• Processing fee (20%): Rp {{ number_format(($rental->unit->price_per_day * $unusedDays) * 0.2, 0, ',', '.') }}</p>
                                <p class="mt-2 text-xs">Refund will be processed within 3-5 business days.</p>
                            @else
                                <p>No refund available for today's return.</p>
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

    <script>
        function openEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.remove('hidden');
        }

        function closeEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.add('hidden');
            document.getElementById('earlyReturnForm').reset();
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
    </script>
</x-app-layout>