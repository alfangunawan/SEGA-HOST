<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}" 
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
                                <p class="text-sm text-gray-600 dark:text-gray-400">Manage your server hosting services</p>
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
                                    <div class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs rounded-full">
                                        Limit Reached
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats - Update section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Active Servers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Servers</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $activeServers }}
                                    <span class="text-sm font-normal text-gray-500">/2 max</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders (excluding cancelled) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Valid Orders
                                    @if($cancelledOrders > 0)
                                        <span class="text-xs text-gray-500">({{ $cancelledOrders }} cancelled)</span>
                                    @endif
                                </p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Spending (excluding cancelled) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Spending</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($monthlySpending, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Excluding cancelled orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Tickets -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Support Tickets</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $supportTickets }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row (Optional) -->
            @if($completedOrders > 0 || $pendingOrders > 0 || $cancelledOrders > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Completed Orders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                                <p class="text-xl font-semibold text-green-600">{{ $completedOrders }}</p>
                            </div>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                                <p class="text-xl font-semibold text-yellow-600">{{ $pendingOrders }}</p>
                            </div>
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Orders -->
                @if($cancelledOrders > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cancelled</p>
                                <p class="text-xl font-semibold text-red-600">{{ $cancelledOrders }}</p>
                            </div>
                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div></div>
                @endif
            </div>
            @endif

            <!-- Recent Activity & Main Action Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Recent Rentals -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                                <a href="{{ route('rentals.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View All
                                </a>
                            </div>
                            
                            @if($recentRentals->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentRentals as $rental)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-600 dark:to-gray-500 rounded-lg">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $rental->unit->name }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $rental->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
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
                                                            bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                            @break
                                                        @case('overdue')
                                                            bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                            @break
                                                        @default
                                                            bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200
                                                    @endswitch">
                                                    {{ ucfirst(str_replace('_', ' ', $rental->status)) }}
                                                </span>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white 
                                                    {{ $rental->status === 'cancelled' ? 'line-through text-gray-500' : '' }}">
                                                    Rp {{ number_format($rental->total_cost, 0, ',', '.') }}
                                                    @if($rental->status === 'cancelled')
                                                        <span class="text-xs text-red-500">(Refunded)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">No recent activity</p>
                                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                        Start by ordering a server
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-6">
                    <!-- Server Products -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Server Products</h3>
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                                Browse and order hosting packages
                                @if(!Auth::user()->canRentMoreServers())
                                    <span class="text-red-600 dark:text-red-400">(Rental limit reached)</span>
                                @endif
                            </p>
                            <a href="{{ route('products.index') }}" 
                               class="w-full {{ Auth::user()->canRentMoreServers() ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }} text-white font-medium py-2 px-4 rounded-lg transition-colors block text-center"
                               @if(!Auth::user()->canRentMoreServers()) onclick="event.preventDefault(); alert('You have reached the maximum limit of 2 active server rentals.');" @endif>
                                Browse Products
                            </a>
                        </div>
                    </div>

                    <!-- Order History -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order History</h3>
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">View your past and current orders</p>
                            <a href="{{ route('rentals.index') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors block text-center">
                                View Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Limit Warning (if approaching limit) -->
            @if(Auth::user()->activeRentalsCount() >= 1)
                <div class="mb-6">
                    @if(Auth::user()->remainingRentalSlots() === 0)
                        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Server Rental Limit Reached
                                    </h3>
                                    <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                        You have reached the maximum limit of 2 active server rentals. To rent a new server, please return or cancel an existing rental first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->remainingRentalSlots() === 1)
                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        1 Server Slot Remaining
                                    </h3>
                                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                        You can rent 1 more server. Each user is limited to a maximum of 2 active server rentals.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full w-12 h-12 mx-auto mb-3">
                            <svg class="w-6 h-6 text-orange-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-2">Support Center</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Get help when you need it</p>
                        <button class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                            Contact Support
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full w-12 h-12 mx-auto mb-3">
                            <svg class="w-6 h-6 text-indigo-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-2">Documentation</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Learn how to use our services</p>
                        <button class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                            Read Docs
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full w-12 h-12 mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-2">Account Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Manage your account preferences</p>
                        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium text-sm">
                            Manage Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
