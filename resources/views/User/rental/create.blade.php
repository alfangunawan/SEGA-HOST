<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sewa Server') }}
            </h2>
            <a href="{{ route('products.show', $unit) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Rental Form -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Detail Penyewaan</h3>

                        <form method="POST" action="{{ route('rentals.store') }}" id="rentalForm">
                            @csrf
                            <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                            <!-- Rental Period -->
                            <div class="mb-6">
                                <label for="rental_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Periode Sewa (Hari) <span class="text-red-500">*</span>
                                </label>
                                <select name="rental_days" id="rental_days" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Pilih periode sewa</option>
                                    <option value="1" {{ old('rental_days') == 1 ? 'selected' : '' }}>1 Hari</option>
                                    <option value="2" {{ old('rental_days') == 2 ? 'selected' : '' }}>2 Hari</option>
                                    <option value="3" {{ old('rental_days') == 3 ? 'selected' : '' }}>3 Hari</option>
                                    <option value="4" {{ old('rental_days') == 4 ? 'selected' : '' }}>4 Hari</option>
                                    <option value="5" {{ old('rental_days') == 5 ? 'selected' : '' }}>5 Hari (Maksimal)</option>
                                </select>
                                @error('rental_days')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div class="mb-6">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" 
                                       value="{{ old('start_date', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cost Calculation -->
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Kalkulasi Biaya</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Harga per hari:</span>
                                        <span class="font-medium">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Hari sewa:</span>
                                        <span class="font-medium" id="displayDays">-</span>
                                    </div>
                                    <div class="border-t border-gray-300 dark:border-gray-600 pt-2 flex justify-between">
                                        <span class="font-semibold text-gray-900 dark:text-white">Total Biaya:</span>
                                        <span class="font-bold text-blue-600 dark:text-blue-400" id="totalCost">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <input type="checkbox" name="terms_accepted" id="terms_accepted" 
                                           class="mt-1 rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <label for="terms_accepted" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        Saya menyetujui <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a> <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                @error('terms_accepted')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="submitBtn"
                                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white py-3 px-4 rounded-lg font-semibold transition-colors opacity-50 cursor-not-allowed"
                                    disabled>
                                Kirim Permintaan Sewa
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Server Info & Rules -->
                <div class="space-y-6">
                    <!-- Server Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Server</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Nama Server:</span>
                                    <span class="font-medium">{{ $unit->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kode Server:</span>
                                    <span class="font-medium">{{ $unit->code }}</span>
                                </div>
                                @if($unit->ip_address)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Alamat IP:</span>
                                        <span class="font-medium font-mono">{{ $unit->ip_address }}</span>
                                    </div>
                                @endif
                                @if($unit->location)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Lokasi:</span>
                                        <span class="font-medium">{{ $unit->location }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Rental Rules -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-yellow-800 dark:text-yellow-200 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Aturan & Kebijakan Sewa
                            </h3>
                            
                            <ul class="space-y-2 text-sm text-yellow-800 dark:text-yellow-200">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span><strong>Periode sewa maksimal:</strong> 5 hari per unit</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <span><strong>Denda keterlambatan:</strong> 50% dari tarif harian per hari terlambat</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><strong>Perpanjangan:</strong> Maksimal 5 hari perpanjangan (total maksimal 10 hari)</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><strong>Pembayaran:</strong> Pembayaran penuh diperlukan sebelum aktivasi server</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><strong>Support:</strong> Dukungan teknis 24/7 termasuk</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Butuh Bantuan?</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Email: support@segahost.com</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Telepon: +1 (555) 123-4567</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">Live Chat: Tersedia 24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerDay = {{ $unit->price_per_day }};
        const rentalDaysSelect = document.getElementById('rental_days');
        const displayDays = document.getElementById('displayDays');
        const totalCost = document.getElementById('totalCost');
        const submitButton = document.getElementById('submitBtn');
        const form = document.getElementById('rentalForm');

        // Function to update cost calculation
        function updateCostCalculation() {
            const days = parseInt(rentalDaysSelect.value) || 0;
            const total = days * pricePerDay;
            
            displayDays.textContent = days > 0 ? days + ' hari' : '-';
            totalCost.textContent = 'Rp ' + total.toLocaleString('id-ID');
            
            // Enable/disable submit button
            const termsAccepted = document.getElementById('terms_accepted').checked;
            const startDate = document.getElementById('start_date').value;
            
            if (days > 0 && termsAccepted && startDate) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Event listeners
        rentalDaysSelect.addEventListener('change', updateCostCalculation);
        document.getElementById('terms_accepted').addEventListener('change', updateCostCalculation);
        document.getElementById('start_date').addEventListener('change', updateCostCalculation);

        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            const days = parseInt(rentalDaysSelect.value);
            const termsAccepted = document.getElementById('terms_accepted').checked;
            const startDate = document.getElementById('start_date').value;
            
            if (!days || !termsAccepted || !startDate) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
        });

        // Initial calculation
        updateCostCalculation();
    </script>
</x-app-layout>