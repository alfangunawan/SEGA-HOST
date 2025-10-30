<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Sewa Server') }}
            </h2>
            <a href="{{ route('products.show', $unit) }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-8">
                    <!-- Server Info Header -->
                    <div
                        class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-900 dark:text-blue-100">{{ $unit->name }}</h3>
                                <p class="text-blue-700 dark:text-blue-300">{{ $unit->code }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @if($unit->location)
                                <div>
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">Location:</span>
                                    <span class="text-blue-800 dark:text-blue-200 ml-2">{{ $unit->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rental Form -->
                    <form method="POST" action="{{ route('rentals.store') }}" id="rentalForm">
                        @csrf
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Rental Period -->
                            <div>
                                <label for="rental_days"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Periode Sewa <span class="text-red-500">*</span>
                                </label>
                                <select name="rental_days" id="rental_days"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 py-3">
                                    <option value="">Pilih periode sewa</option>
                                    <option value="1" {{ old('rental_days') == 1 ? 'selected' : '' }}>1 Hari</option>
                                    <option value="2" {{ old('rental_days') == 2 ? 'selected' : '' }}>2 Hari</option>
                                    <option value="3" {{ old('rental_days') == 3 ? 'selected' : '' }}>3 Hari</option>
                                    <option value="4" {{ old('rental_days') == 4 ? 'selected' : '' }}>4 Hari</option>
                                    <option value="5" {{ old('rental_days') == 5 ? 'selected' : '' }}>5 Hari (Maksimal)
                                    </option>
                                </select>
                                @error('rental_days')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 py-3">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cost Summary -->
                            <div
                                class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Biaya</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Harga per hari</span>
                                        <span class="font-medium">Rp
                                            {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Periode sewa</span>
                                        <span class="font-medium" id="displayDays">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Saldo tersedia</span>
                                        <span class="font-medium text-emerald-600" id="availableBalance">Rp
                                            {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                                    </div>
                                    <hr class="border-gray-300 dark:border-gray-600">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900 dark:text-white">Total Biaya</span>
                                        <span class="font-bold text-xl text-blue-600 dark:text-blue-400"
                                            id="totalCost">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Saldo setelah transaksi</span>
                                        <span class="font-medium" id="balanceAfter">-</span>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hidden"
                                    id="balanceWarning">
                                    <p class="text-sm text-red-700 dark:text-red-300" id="warningText"></p>
                                </div>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" name="terms_accepted" id="terms_accepted"
                                    class="mt-1 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-500">
                                <label for="terms_accepted"
                                    class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    Saya menyetujui syarat dan ketentuan sewa server termasuk kebijakan pembayaran dan
                                    denda keterlambatan
                                    <span class="text-red-500">*</span>
                                </label>
                            </div>
                            @error('terms_accepted')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" id="submitBtn"
                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white py-4 px-6 rounded-xl font-semibold text-lg transition-colors duration-200 opacity-50 cursor-not-allowed"
                                disabled>
                                Konfirmasi Sewa Server
                            </button>
                        </div>
                    </form>

                    <!-- Important Notes -->
                    <div
                        class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">Penting untuk
                                    Diketahui:</h4>
                                <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                                    <li>• Maksimal periode sewa 5 hari per server</li>
                                    <li>• Denda Rp 5.000/hari untuk keterlambatan pengembalian</li>
                                    <li>• Server akan aktif setelah pembayaran dikonfirmasi</li>
                                    <li>• Support teknis tersedia 24/7</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerDay = @json((float) $unit->price_per_day);
        const userBalance = @json((float) auth()->user()->balance);
        const rentalDaysSelect = document.getElementById('rental_days');
        const displayDays = document.getElementById('displayDays');
        const totalCost = document.getElementById('totalCost');
        const submitButton = document.getElementById('submitBtn');
        const form = document.getElementById('rentalForm');
        const balanceAfter = document.getElementById('balanceAfter');
        const balanceWarning = document.getElementById('balanceWarning');
        const warningText = document.getElementById('warningText');

        function updateCostCalculation() {
            const days = parseInt(rentalDaysSelect.value) || 0;
            const total = days * pricePerDay;
            const remainingBalance = userBalance - total;

            displayDays.textContent = days > 0 ? days + ' hari' : '-';
            totalCost.textContent = 'Rp ' + total.toLocaleString('id-ID');

            if (days > 0) {
                balanceAfter.textContent = 'Rp ' + remainingBalance.toLocaleString('id-ID');

                if (remainingBalance < 0) {
                    balanceAfter.classList.add('text-red-600', 'dark:text-red-400');
                    balanceWarning.classList.remove('hidden');
                    warningText.textContent = 'Saldo kurang Rp ' + Math.abs(remainingBalance).toLocaleString('id-ID');
                } else {
                    balanceAfter.classList.remove('text-red-600', 'dark:text-red-400');
                    balanceAfter.classList.add('text-emerald-600', 'dark:text-emerald-400');
                    balanceWarning.classList.add('hidden');
                }
            } else {
                balanceAfter.textContent = '-';
                balanceAfter.classList.remove('text-red-600', 'dark:text-red-400', 'text-emerald-600', 'dark:text-emerald-400');
                balanceWarning.classList.add('hidden');
            }

            updateSubmitButton();
        }

        function updateSubmitButton() {
            const days = parseInt(rentalDaysSelect.value) || 0;
            const termsAccepted = document.getElementById('terms_accepted').checked;
            const startDate = document.getElementById('start_date').value;
            const total = days * pricePerDay;
            const hasSufficientBalance = (userBalance - total) >= 0;

            if (days > 0 && termsAccepted && startDate && hasSufficientBalance) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('hover:bg-blue-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-blue-700');
            }
        }

        // Event listeners
        rentalDaysSelect.addEventListener('change', updateCostCalculation);
        document.getElementById('terms_accepted').addEventListener('change', updateSubmitButton);
        document.getElementById('start_date').addEventListener('change', updateSubmitButton);

        // Form submission
        form.addEventListener('submit', function (e) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses Pesanan...
            `;
        });

        // Initial calculation
        updateCostCalculation();
    </script>
</x-app-layout>