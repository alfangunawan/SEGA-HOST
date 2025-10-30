<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Server Details</h1>
                        <p class="text-gray-600 dark:text-gray-400">Complete server specifications and pricing</p>
                    </div>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Rental Limit Alerts -->
            @if(!Auth::user()->canRentMoreServers())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-red-800 dark:text-red-200">Server Rental Limit Reached</h3>
                            <p class="text-red-700 dark:text-red-300 text-sm mt-1">
                                You have reached the maximum limit of 2 active server rentals. 
                                <a href="{{ route('rentals.index') }}" class="underline font-medium">Manage your existing rentals</a> first.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->remainingRentalSlots() === 1)
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-800 dark:text-yellow-200">1 Server Slot Remaining</h3>
                            <p class="text-yellow-700 dark:text-yellow-300 text-sm mt-1">
                                This will be your last available server slot. Maximum 2 active rentals per user.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Server Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-8">
                            <!-- Categories -->
                            @if($unit->categories->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach($unit->categories as $category)
                                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 text-sm font-medium rounded-full border border-blue-200 dark:border-blue-800">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Title & Status -->
                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $unit->name }}</h2>
                                    <p class="text-lg text-gray-600 dark:text-gray-400">Server Code: {{ $unit->code }}</p>
                                </div>
                                <span class="px-4 py-2 {{ $unit->status === 'available' ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800' }} border rounded-full font-medium">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </div>

                            <!-- Server Visual -->
                            <div class="bg-blue-50 dark:bg-gray-700 rounded-2xl p-12 text-center mb-8">
                                <div class="w-24 h-24 mx-auto mb-4 bg-blue-600 rounded-2xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ $unit->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 font-mono">{{ $unit->code }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                    {{ $unit->description ?: 'High-performance server solution with enterprise-grade reliability and 24/7 support.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Technical Specifications
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    @if($unit->location)
                                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Location</p>
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $unit->location }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Uptime</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">99.9% Guaranteed</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">

                                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Support</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">24/7 Technical</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Security</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">DDoS Protection</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Features & Benefits
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $features = [
                                        'SSD Storage', 'Full Root Access', 'Daily Backups', 
                                        'Monitoring Dashboard', 'API Access', 'Instant Setup'
                                    ];
                                @endphp
                                
                                @foreach($features as $feature)
                                    <div class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        <!-- Pricing Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Pricing</h3>
                                
                                <div class="text-center mb-6">
                                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400">per day</div>
                                </div>

                                <!-- Pricing Options -->
                                <div class="space-y-3 mb-6 text-sm">
                                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-600 dark:text-gray-400">Weekly (7 days)</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($unit->price_per_day * 7, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-2">
                                        <span class="text-gray-600 dark:text-gray-400">Monthly (30 days)</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($unit->price_per_day * 30, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                    @if($unit->status === 'available' && Auth::user()->canRentMoreServers())
                                        <button onclick="rentThisServer('{{ $unit->id }}')" 
                                               class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-semibold transition-colors duration-200">
                                            Rent This Server
                                        </button>
                                    @elseif($unit->status === 'available' && !Auth::user()->canRentMoreServers())
                                        <button onclick="showLimitAlert()"
                                                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-semibold transition-colors duration-200">
                                            Rental Limit Reached
                                        </button>
                                    @else
                                        <button disabled 
                                                class="w-full bg-gray-400 text-white py-3 px-4 rounded-xl font-semibold cursor-not-allowed">
                                            Currently Unavailable
                                        </button>
                                    @endif

                                    <a href="{{ route('rentals.index') }}" 
                                       class="w-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-500 py-3 px-4 rounded-xl font-medium transition-colors duration-200 block text-center">
                                        View My Rentals
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Information</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Server Status</span>
                                        <span class="px-3 py-1 {{ $unit->status === 'available' ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400' }} rounded-full text-sm font-medium">
                                            {{ ucfirst($unit->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Your Active Rentals</span>
                                        <span class="font-semibold {{ Auth::user()->canRentMoreServers() ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ Auth::user()->activeRentalsCount() }}/2
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Available Slots</span>
                                        <span class="font-semibold {{ Auth::user()->remainingRentalSlots() > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ Auth::user()->remainingRentalSlots() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Benefits -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Why Choose Us</h3>
                                
                                <div class="space-y-3">
                                    @php
                                        $benefits = [
                                            'Instant activation',
                                            '99.9% uptime SLA', 
                                            '24/7 support included',
                                            'Money back guarantee'
                                        ];
                                    @endphp
                                    
                                    @foreach($benefits as $benefit)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-gray-600 dark:text-gray-400 text-sm">{{ $benefit }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if($unit->categories->count() > 0)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Related Categories</h3>
                                    
                                    <div class="space-y-2">
                                        @foreach($unit->categories as $category)
                                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                               class="block p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-xl transition-colors duration-200">
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

    <!-- Simple JavaScript -->
    <script>
        function rentThisServer(unitId) {
            const button = event.target;
            
            // Simple loading effect
            const originalText = button.textContent;
            button.textContent = 'Loading...';
            button.disabled = true;
            
            // Navigate after short delay
            setTimeout(() => {
                window.location.href = '/rentals/create?unit=' + unitId;
            }, 500);
        }

        function showLimitAlert() {
            if (confirm('You have reached the maximum limit of 2 active server rentals. Would you like to manage your existing rentals?')) {
                window.location.href = '{{ route("rentals.index") }}';
            }
        }

        // Simple hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.hover\\:bg-gray-50, .hover\\:bg-gray-100');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</x-app-layout>