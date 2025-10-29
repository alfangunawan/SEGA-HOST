<x-app-layout>
    @php
        $configurationProfile = $rental->unit->configurationProfile;
        $configurationValues = $rental->unit->configurationValues->keyBy('configuration_field_id');
        $hasConfiguration = $configurationProfile && $configurationProfile->fields->isNotEmpty();
    @endphp
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Penyewaan
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('rentals.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Kembali
                </a>
                
                @if($rental->status === 'pending')
                    <form method="POST" action="{{ route('rentals.cancel', $rental) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('Batalkan pengajuan ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Batalkan Pengajuan
                        </button>
                    </form>
                @endif

                @if($rental->status === 'active')
                    <button onclick="openEarlyReturnModal()" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Kembalikan Lebih Awal
                    </button>
                @endif

                @if($rental->status === 'overdue')
                    <button onclick="openOverdueReturnModal()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Kembalikan Server
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @php
                $today = \Carbon\Carbon::today();
                $startDate = \Carbon\Carbon::parse($rental->start_date);
                $endDate = \Carbon\Carbon::parse($rental->end_date);
                $isOverdue = $today->greaterThan($endDate);
                $hariTerlambat = $rental->daysOverdue();
                $dendaAmount = $rental->calculatePenalty();
                
                // Perbaiki perhitungan durasi rental
                $durasiRental = $startDate->diffInDays($endDate) ;
                
                // Perbaiki perhitungan sisa hari
                $sisaHari = $rental->status === 'active' ? max(0, $endDate->diffInDays($today, false)) : 0;
            @endphp

            <!-- Main Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Server & Status Info -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Server Header -->
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rental->unit->name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $rental->unit->code }}</p>
                                </div>
                                <div class="flex flex-wrap items-center justify-end gap-3">
                                    @if($hasConfiguration)
                                        <button type="button" onclick="toggleConfigurationSection()"
                                                data-config-button
                                                aria-expanded="false"
                                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h18M3 12h18M3 16.5h10" />
                                            </svg>
                                            <span data-config-button-label>Konfigurasi Server</span>
                                        </button>
                                    @endif
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                                        @switch($rental->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('active') bg-green-100 text-green-800 @break
                                            @case('completed') bg-blue-100 text-blue-800 @break
                                            @case('overdue') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($rental->status)
                                            @case('completed') Selesai @break
                                            @case('pending') Menunggu Persetujuan @break
                                            @case('active') Aktif @break
                                            @case('overdue') Terlambat @break
                                            @default {{ ucfirst($rental->status) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>

                            <!-- Basic Info Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Mulai</div>
                                    <div class="font-semibold">{{ $rental->start_date->format('d M Y') }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Berakhir</div>
                                    <div class="font-semibold">{{ $rental->end_date->format('d M Y') }}</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Durasi</div>
                                    <div class="font-semibold">{{ $durasiRental }} hari</div>
                                </div>
                            </div>

                            <!-- Server Access (Active Only) -->
                            @if($rental->status === 'active' || $rental->status === 'overdue')
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-3">Akses Server</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-blue-600 dark:text-blue-400">IP Address:</span>
                                            <code class="ml-2 bg-white dark:bg-gray-800 px-2 py-1 rounded font-mono">
                                                {{ $rental->unit->ip_address ?? '192.168.1.100' }}
                                            </code>
                                        </div>
                                        <div>
                                            <span class="text-blue-600 dark:text-blue-400">Username:</span>
                                            <code class="ml-2 bg-white dark:bg-gray-800 px-2 py-1 rounded font-mono">
                                                {{ $rental->unit->username ?? 'root' }}
                                            </code>
                                        </div>
                                        <div>
                                            <span class="text-blue-600 dark:text-blue-400">Password:</span>
                                            <code class="ml-2 bg-white dark:bg-gray-800 px-2 py-1 rounded font-mono">
                                                {{ $rental->unit->password ?? '••••••••' }}
                                            </code>
                                        </div>
                                        <div>
                                            <span class="text-blue-600 dark:text-blue-400">SSH Port:</span>
                                            <code class="ml-2 bg-white dark:bg-gray-800 px-2 py-1 rounded font-mono">
                                                {{ $rental->unit->ssh_port ?? '22' }}
                                            </code>
                                        </div>
                                    </div>
                                    
                                    <!-- SSH Command -->
                                    <div class="mt-4">
                                        <div class="text-sm text-blue-700 dark:text-blue-300 mb-2">Koneksi SSH:</div>
                                        <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm">
                                            ssh {{ $rental->unit->username ?? 'root' }}@{{ $rental->unit->ip_address ?? '192.168.1.100' }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Alerts -->
                            @if($rental->status === 'overdue')
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">⚠️ Server Terlambat</h4>
                                    <p class="text-red-700 dark:text-red-300 text-sm">
                                        Server sudah melewati batas waktu {{ $hariTerlambat }} hari. 
                                        Denda Rp {{ number_format($dendaAmount, 0, ',', '.') }} akan dipotong saat pengembalian.
                                    </p>
                                </div>
                            @endif

                            @if($rental->status === 'active')
                                @if($sisaHari <= 1 && $sisaHari >= 0)
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                        <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">⚡ Perhatian</h4>
                                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                                            @if($sisaHari == 0)
                                                Server akan berakhir pada {{ $rental->end_date->format('d M Y') }} pukul 23:59.
                                            @elseif($sisaHari == 1)
                                                Tersisa 1 hari lagi. Server akan berakhir pada {{ $rental->end_date->format('d M Y') }}.
                                            @endif
                                        </p>
                                    </div>
                                @elseif($sisaHari > 1)
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                        <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">📅 Info Rental</h4>
                                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                                            Tersisa {{ $sisaHari }} hari lagi. Server akan berakhir pada {{ $rental->end_date->format('d M Y') }}.
                                        </p>
                                    </div>
                                @endif
                            @endif

                            @if($rental->penalty_cost < 0)
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <h4 class="font-semibold text-green-800 dark:text-green-200 mb-2">✅ Refund Diproses</h4>
                                    <p class="text-green-700 dark:text-green-300 text-sm">
                                        Refund sebesar Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }} akan diproses dalam 3-5 hari kerja.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Pricing Sidebar -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Rincian Biaya</h4>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Harga per hari</span>
                                    <span class="font-medium">Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Durasi rental</span>
                                    <span class="font-medium">{{ $durasiRental }} hari</span>
                                </div>
                                
                                <hr class="border-gray-300 dark:border-gray-600">
                                
                                <div class="flex justify-between">
                                    <span class="font-medium">Biaya Rental <span class="text-xs text-green-600">✓</span></span>
                                    <span class="font-medium">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($dendaAmount > 0 && $rental->status === 'overdue')
                                    <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm dark:border-red-800 dark:bg-red-900/20 mb-2">
                                        <div class="flex justify-between text-red-700 dark:text-red-300">
                                            <span class="font-medium">Denda keterlambatan ({{ $hariTerlambat }} hari)</span>
                                            <span class="font-semibold">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">Belum terpotong dari saldo. Akan diproses ketika pengembalian disetujui.</p>
                                    </div>
                                @endif

                                @if($rental->penalty_cost != 0)
                                    <div class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800/60">
                                        <div class="flex justify-between {{ $rental->penalty_cost < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                            <span class="font-medium">
                                                {{ $rental->penalty_cost < 0 ? 'Refund diproses' : 'Denda dibayar' }}
                                            </span>
                                            <span class="font-semibold">
                                                {{ $rental->penalty_cost < 0 ? '+' : '' }}Rp {{ number_format(abs($rental->penalty_cost), 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            ✓ {{ $rental->penalty_cost < 0 ? 'Dana dikembalikan ke saldo' : 'Denda sudah dipotong dari saldo' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($rental->status === 'pending' && $rental->previous_status)
                        <div class="mt-6 p-4 rounded-lg border border-yellow-300 bg-yellow-50 dark:border-yellow-600 dark:bg-yellow-900/20">
                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-200">Menunggu Tindakan Admin</h4>
                            <p class="text-sm text-yellow-800 dark:text-yellow-300 mt-1">
                                Permintaan pengembalian sedang diproses. Anda akan melihat pembaruan keputusan di bagian catatan di bawah ini.
                            </p>
                        </div>
                    @endif

                    @if($rental->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Catatan</h4>
                            <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $rental->notes }}</p>
                        </div>
                    @endif
                    @if($hasConfiguration)
                        <div id="configurationSection" class="mt-6 hidden">
                            <div class="rounded-xl border border-indigo-100 dark:border-indigo-800 bg-indigo-50/60 dark:bg-indigo-900/20 p-5">
                                <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfigurasi Server</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Profil: {{ $configurationProfile->name ?? 'Tanpa Nama' }}
                                        </p>
                                    </div>
                                </div>

                                @if($configurationProfile->description)
                                    <p class="mb-5 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $configurationProfile->description }}
                                    </p>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($configurationProfile->fields as $field)
                                        @php
                                            $rawValue = optional($configurationValues->get($field->id))->value;
                                            $displayValue = $rawValue ?? $field->default_value;
                                            $displayValue = is_array($displayValue) ? implode(', ', $displayValue) : $displayValue;

                                            if (is_string($displayValue)) {
                                                $decoded = json_decode($displayValue, true);
                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                    if (is_array($decoded)) {
                                                        $displayValue = implode(', ', array_filter($decoded, fn ($item) => $item !== null && $item !== ''));
                                                    } elseif (is_bool($decoded)) {
                                                        $displayValue = $decoded ? 'Ya' : 'Tidak';
                                                    } elseif (!is_array($decoded)) {
                                                        $displayValue = $decoded;
                                                    }
                                                }
                                            }

                                            if (in_array($field->type, ['boolean', 'toggle'], true)) {
                                                $normalized = is_string($displayValue) ? strtolower($displayValue) : $displayValue;
                                                $truthy = ['1', 'true', 'yes', 'on', 'ya'];
                                                $falsy = ['0', 'false', 'no', 'off', 'tidak'];

                                                if (is_bool($displayValue)) {
                                                    $boolValue = $displayValue;
                                                } elseif (is_numeric($displayValue)) {
                                                    $boolValue = (float) $displayValue > 0;
                                                } elseif (is_string($normalized) && in_array($normalized, $truthy, true)) {
                                                    $boolValue = true;
                                                } elseif (is_string($normalized) && in_array($normalized, $falsy, true)) {
                                                    $boolValue = false;
                                                } else {
                                                    $boolValue = null;
                                                }

                                                $displayValue = $boolValue === null ? $displayValue : ($boolValue ? 'Ya' : 'Tidak');
                                            }

                                            $displayValue = ($displayValue === null || $displayValue === '') ? '-' : $displayValue;
                                        @endphp
                                        <div class="bg-white/70 dark:bg-gray-800/70 border border-white/40 dark:border-gray-700/60 backdrop-blur-sm p-4 rounded-lg">
                                            <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-300 mb-1">
                                                {{ $field->label }}
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white break-words">
                                                {!! nl2br(e($displayValue)) !!}
                                            </div>
                                            @if($field->description ?? false)
                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $field->description }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if($configurationProfile->fields->isEmpty())
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada konfigurasi yang ditetapkan untuk server ini.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Early Return Modal -->
    <div id="earlyReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kembalikan Lebih Awal</h3>

                <form method="POST" action="{{ route('rentals.early-return', $rental) }}">
                    @csrf @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian *
                        </label>
                        <textarea name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600"
                                  placeholder="Jelaskan alasan pengembalian..."></textarea>
                    </div>

                    @php
                        // Perbaiki perhitungan refund
                        $totalDays = $durasiRental;
                        $usedDays = $startDate->diffInDays($today) + 1;
                        $unusedDays = max(0, $totalDays - $usedDays);
                        $refundAmount = $unusedDays > 0 ? ($rental->unit->price_per_day * $unusedDays) * 0.8 : 0;
                    @endphp

                    <div class="mb-4 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <h4 class="font-medium text-orange-800 dark:text-orange-200 mb-2">Info Refund</h4>
                        <div class="text-sm text-orange-700 dark:text-orange-300">
                            <p>• Total durasi: {{ $totalDays }} hari</p>
                            <p>• Hari terpakai: {{ $usedDays }} hari</p>
                            @if($unusedDays > 0)
                                <p>• Hari tersisa: {{ $unusedDays }} hari</p>
                                <p>• Refund (80%): <strong>Rp {{ number_format($refundAmount, 0, ',', '.') }}</strong></p>
                            @else
                                <p>• <strong>Tidak ada refund</strong> - semua hari rental sudah terpakai.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="confirm_return" value="1" required class="rounded">
                            <span class="ml-2 text-sm">Saya setuju untuk mengembalikan server ini lebih awal</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEarlyReturnModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 bg-orange-600 text-white rounded-md">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Overdue Return Modal -->
    <div id="overdueReturnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kembalikan Server dengan Denda</h3>

                <form method="POST" action="{{ route('rentals.return-with-penalty', $rental) }}">
                    @csrf @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan pengembalian *
                        </label>
                        <textarea name="return_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600"
                                  placeholder="Jelaskan alasan pengembalian..."></textarea>
                    </div>

                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Info Denda</h4>
                        <div class="text-sm text-red-700 dark:text-red-300 space-y-1">
                            <div class="flex justify-between">
                                <span>Hari terlambat:</span>
                                <span class="font-semibold">{{ $hariTerlambat }} hari</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Denda:</span>
                                <span class="font-semibold">Rp {{ number_format($dendaAmount, 0, ',', '.') }}</span>
                            </div>
                            
                            <hr class="border-red-300">
                            
                            <div class="flex justify-between">
                                <span>Saldo saat ini:</span>
                                <span class="font-semibold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-red-300 pt-1">
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
                            <input type="checkbox" name="confirm_penalty" value="1" required class="rounded">
                            <span class="ml-2 text-sm">Saya setuju membayar denda keterlambatan</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeOverdueReturnModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md">Bayar & Kembalikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleConfigurationSection() {
            const section = document.getElementById('configurationSection');
            const button = document.querySelector('[data-config-button]');
            const label = button ? button.querySelector('[data-config-button-label]') : null;
            if (!section) {
                return;
            }

            const isHidden = section.classList.toggle('hidden');
            if (button) {
                button.setAttribute('aria-expanded', (!isHidden).toString());
                if (label) {
                    label.textContent = isHidden ? 'Konfigurasi Server' : 'Sembunyikan Konfigurasi';
                }
            }

            if (!isHidden) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function openEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.remove('hidden');
        }
        function closeEarlyReturnModal() {
            document.getElementById('earlyReturnModal').classList.add('hidden');
        }
        function openOverdueReturnModal() {
            document.getElementById('overdueReturnModal').classList.remove('hidden');
        }
        function closeOverdueReturnModal() {
            document.getElementById('overdueReturnModal').classList.add('hidden');
        }
    </script>
</x-app-layout>