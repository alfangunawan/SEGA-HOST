<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Our Server Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            Choose Your Perfect Server
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Premium server hosting solutions designed for your business needs
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="flex flex-wrap gap-4 items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by
                                category:</span>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('products.index') }}"
                                    class="px-4 py-2 {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-blue-700 hover:text-white transition-colors">
                                    All Products
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                        class="px-4 py-2 {{ request('category') === $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg text-sm font-medium hover:bg-blue-700 hover:text-white transition-colors">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($units->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($units as $unit)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow">
                            <!-- Server Icon -->
                            <div
                                class="h-48 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $unit->code }}</p>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Categories -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($unit->categories as $category)
                                        <span
                                            class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Price -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                                        <span class="text-sm font-normal text-gray-600 dark:text-gray-400">/hari</span>
                                    </span>
                                    <span
                                        class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                                        {{ ucfirst($unit->status) }}
                                    </span>
                                </div>

                                <!-- Unit Name -->
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $unit->name }}
                                </h3>

                                <!-- Description -->
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                    {{ $unit->description }}
                                </p>

                                <!-- Specifications -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Server Code: {{ $unit->code }}
                                    </div>
                                    @if($unit->ip_address)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            IP: {{ $unit->ip_address }}
                                        </div>
                                    @endif
                                    @if($unit->location)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Location: {{ $unit->location }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <a href="{{ route('products.show', $unit) }}"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                        View Details
                                    </a>
                                    @if(Auth::user()->canRentMoreServers())
                                        <button onclick="rentServer('{{ $unit->id }}')"
                                            class="flex-1 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                            Rent Now
                                        </button>
                                    @else
                                        <button onclick="alert('You have reached the maximum limit of 2 active server rentals.')"
                                            class="flex-1 border-2 border-gray-300 text-gray-400 cursor-not-allowed py-2 px-4 rounded-lg font-medium">
                                            Limit Reached
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{ $units->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <!-- No Products Message -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Servers Available</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request('category'))
                                No servers found in this category. Try browsing other categories.
                            @else
                                We're currently updating our server catalog. Please check back soon!
                            @endif
                        </p>
                        <div class="flex gap-4 justify-center">
                            @if(request('category'))
                                <a href="{{ route('products.index') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    View All Servers
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for rent functionality -->
    <script>
        function rentServer(unitId) {
            window.location.href = '/rentals/create?unit=' + unitId;
        }
    </script>
</x-app-layout>