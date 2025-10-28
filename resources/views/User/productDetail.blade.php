<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Server Details') }}
            </h2>
            <a href="{{ route('products.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Rental Limit Warning -->
            @if(!Auth::user()->canRentMoreServers())
                <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Server Rental Limit Reached
                            </h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                You have reached the maximum limit of 2 active server rentals. To rent this server, please 
                                <a href="{{ route('rentals.index') }}" class="font-medium underline">manage your existing rentals</a> first.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->remainingRentalSlots() === 1)
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                1 Server Slot Remaining
                            </h3>
                            <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                This will be your last available server slot. Maximum 2 active rentals per user.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Product Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Overview -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Header with Categories -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($unit->categories as $category)
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-semibold rounded-full">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Title and Status -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $unit->name }}
                                    </h1>
                                    <p class="text-lg text-gray-600 dark:text-gray-400">
                                        Server Code: {{ $unit->code }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 {{ $unit->status === 'available' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }} text-sm font-semibold rounded-full">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    {{ $unit->description ?: 'No description available for this server.' }}
                                </p>
                            </div>

                            <!-- Server Image/Icon -->
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-8 text-center mb-6">
                                <svg class="w-24 h-24 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                </svg>
                                <h4 class="text-xl font-semibold text-gray-700 dark:text-gray-300">{{ $unit->name }}</h4>
                                <p class="text-gray-500 dark:text-gray-400">{{ $unit->code }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Technical Specifications
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Server Info -->
                                <div class="space-y-4">
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Server Code</span>
                                            <p class="text-gray-900 dark:text-white font-semibold">{{ $unit->code }}</p>
                                        </div>
                                    </div>

                                    @if($unit->ip_address)
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c5 0 9-4 9-9m-9 9c-5 0-9-4-9-9m9 9V3m0 18V3"></path>
                                            </svg>
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">IP Address</span>
                                                <p class="text-gray-900 dark:text-white font-semibold font-mono">{{ $unit->ip_address }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($unit->location)
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Location</span>
                                                <p class="text-gray-900 dark:text-white font-semibold">{{ $unit->location }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Additional Info -->
                                <div class="space-y-4">
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uptime</span>
                                            <p class="text-gray-900 dark:text-white font-semibold">99.9% Guaranteed</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Support</span>
                                            <p class="text-gray-900 dark:text-white font-semibold">24/7 Technical Support</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Security</span>
                                            <p class="text-gray-900 dark:text-white font-semibold">DDoS Protection</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Features & Benefits
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">SSD Storage</span>
                                </div>
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Full Root Access</span>
                                </div>
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Daily Backups</span>
                                </div>
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Monitoring Dashboard</span>
                                </div>
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">API Access</span>
                                </div>
                                <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Instant Setup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Pricing & Actions -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">
                        <!-- Pricing Card -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pricing</h3>
                                
                                <div class="text-center mb-6">
                                    <div class="text-4xl font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                                    </div>
                                    <div class="text-gray-600 dark:text-gray-400">per hari</div>
                                </div>

                                <!-- Pricing Breakdown -->
                                <div class="space-y-2 mb-6 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Mingguan (7 hari)</span>
                                        <span class="font-medium">Rp {{ number_format($unit->price_per_day * 7, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Bulanan (30 hari)</span>
                                        <span class="font-medium">Rp {{ number_format($unit->price_per_day * 30, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                @if($unit->status === 'available' && Auth::user()->canRentMoreServers())
                                    <a href="{{ route('rentals.create', ['unit' => $unit->id]) }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors mb-3 block text-center">
                                        Sewa Server Ini
                                    </a>
                                @elseif($unit->status === 'available' && !Auth::user()->canRentMoreServers())
                                    <button onclick="alert('You have reached the maximum limit of 2 active server rentals. Please manage your existing rentals first.')"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg font-semibold transition-colors mb-3">
                                        Rental Limit Reached
                                    </button>
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-semibold cursor-not-allowed mb-3">
                                        Saat Ini Tidak Tersedia
                                    </button>
                                @endif

                                <a href="{{ route('rentals.index') }}" 
                                   class="w-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-500 py-2 px-4 rounded-lg font-medium transition-colors block text-center">
                                    Lihat Rental Saya
                                </a>
                            </div>
                        </div>

                        <!-- Server Status Info -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Info</h3>
                                
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Server Status:</span>
                                        <span class="px-2 py-1 {{ $unit->status === 'available' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }} text-xs font-semibold rounded-full">
                                            {{ ucfirst($unit->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Your Active Rentals:</span>
                                        <span class="font-semibold {{ Auth::user()->canRentMoreServers() ? 'text-green-600' : 'text-red-600' }}">
                                            {{ Auth::user()->activeRentalsCount() }}/2
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Available Slots:</span>
                                        <span class="font-semibold {{ Auth::user()->remainingRentalSlots() > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ Auth::user()->remainingRentalSlots() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Info</h3>
                                
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Aktivasi instan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">99.9% uptime SLA</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Support 24/7 termasuk</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Garansi uang kembali</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if($unit->categories->count() > 0)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categories</h3>
                                    
                                    <div class="space-y-2">
                                        @foreach($unit->categories as $category)
                                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                               class="block p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</div>
                                                @if($category->description)
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $category->description }}</div>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>