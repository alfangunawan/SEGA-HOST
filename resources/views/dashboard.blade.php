<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $userBalance = (float) Auth::user()->balance;
            @endphp
            <!-- Rental Limit Warning (if approaching limit) -->
            @if(Auth::user()->activeRentalsCount() >= 1)
                <div class="mb-6">
                    @if(Auth::user()->remainingRentalSlots() === 0)
                        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Server Rental Limit Reached
                                    </h3>
                                    <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                        You have reached the maximum limit of 2 active server rentals. To rent a new server,
                                        please return an existing rental first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->remainingRentalSlots() === 1)
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        1 Server Slot Remaining
                                    </h3>
                                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                        You can rent 1 more server. Each user is limited to a maximum of 2 active server
                                        rentals.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                    class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-semibold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold">Welcome back, {{ Auth::user()->name }}!</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Manage your server hosting services
                                </p>
                            </div>
                        </div>

                        <!-- Rental Limit Info -->
                        <div class="text-right">
                            <div class="flex items-center space-x-2">
                                <div class="text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Server Slots:</span>
                                    <span class="font-semibold 
                                        @if(Auth::user()->remainingRentalSlots() > 0) 
                                            text-green-600 dark:text-green-400
                                        @else 
                                            text-red-600 dark:text-red-400
                                        @endif">
                                        {{ Auth::user()->remainingRentalSlots() }}/2 Available
                                    </span>
                                </div>
                                @if(Auth::user()->remainingRentalSlots() === 0)
                                    <div
                                        class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs rounded-full">
                                        Limit Reached
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats - Update section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Available Balance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-teal-100 dark:bg-teal-900 rounded-lg">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Saldo Tersedia</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($userBalance, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <a href="{{ route('rentals.index') }}"
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 focus:outline-none transition hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <span>Valid Orders</span>
                                </p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $totalOrders }}
                                </p>
                            </div>
                            <div class="ml-auto text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Monthly Spending -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Spending</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($monthlySpending, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Tickets -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Support Tickets</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $supportTickets }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan penyewaan server terbaru Anda
                            </p>
                        </div>
                        <a href="{{ route('rentals.index') }}"
                            class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                            Lihat Semua
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    @if($recentRentals->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentRentals as $rental)
                                <a href="{{ route('rentals.show', $rental) }}"
                                    class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/60 hover:border-indigo-300 dark:hover:border-indigo-500 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 rounded-lg bg-indigo-100 dark:bg-indigo-900/60">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                                {{ $rental->unit->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ $rental->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                    @php
                                        $statusLabel = \App\Models\Rental::statusLabel($rental->status);
                                        $statusClasses = \App\Models\Rental::statusBadgeClasses($rental->status);
                                    @endphp

                                    <div class="flex flex-col items-start md:items-end gap-1">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($rental->total_cost, 0, ',', '.') }}
                                            @if($rental->status === \App\Models\Rental::STATUS_PENDING && $rental->previous_status)
                                                <span class="text-xs text-amber-500">(Menunggu persetujuan admin)</span>
                                            @endif
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-3">Belum ada aktivitas terbaru.</p>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                Pesan server pertama Anda
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>