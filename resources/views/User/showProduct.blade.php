<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-50 dark:bg-gray-800"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6">
                        <span class="text-blue-600 dark:text-blue-400">
                            Premium Servers
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        High-performance server solutions with enterprise-grade reliability
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 mt-12">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Filter:</span>
                    <a href="{{ route('products.index') }}"
                        class="group px-4 py-2 {{ !request('category') ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600' }} rounded-full text-sm font-medium transition-all duration-200 border border-gray-200 dark:border-gray-600">
                        All Servers
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="group px-4 py-2 {{ request('category') === $category->slug ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600' }} rounded-full text-sm font-medium transition-all duration-200 border border-gray-200 dark:border-gray-600">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($units->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-12">
                    @foreach($units as $unit)
                        <div
                            class="group relative bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl hover:scale-[1.02] transition-all duration-300">
                            <!-- Server Visual -->
                            <div
                                class="relative h-56 bg-blue-50 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-blue-100 dark:bg-gray-600 group-hover:bg-blue-200 dark:group-hover:bg-gray-500 transition-all duration-300">
                                </div>
                                <div class="relative text-center z-10">
                                    <div
                                        class="w-20 h-20 mx-auto mb-4 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2">
                                            </path>
                                        </svg>
                                    </div>
                                    <span
                                        class="inline-block px-3 py-1 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-full shadow-sm">
                                        {{ $unit->code }}
                                    </span>
                                </div>
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                        {{ ucfirst($unit->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Categories -->
                                @if($unit->categories->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($unit->categories as $category)
                                            <span
                                                class="px-3 py-1 bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-full border border-blue-200 dark:border-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Title & Price -->
                                <div class="mb-4">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $unit->name }}
                                    </h3>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                            Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">/day</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($unit->description)
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-6 line-clamp-2">
                                        {{ $unit->description }}
                                    </p>
                                @endif

                                <!-- Quick Specs -->
                                <div class="space-y-2 mb-6">
                                    @if($unit->location)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                            <span class="font-medium">Location:</span>
                                            <span class="ml-2">{{ $unit->location }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <a href="{{ route('products.show', $unit) }}"
                                        class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center py-3 px-4 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-[1.02]">
                                        Details
                                    </a>
                                    @if(Auth::user()->canRentMoreServers())
                                        <button onclick="rentServer('{{ $unit->id }}')"
                                            class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-blue-700 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                            Rent Now
                                        </button>
                                    @else
                                        <button disabled
                                            class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed py-3 px-4 rounded-xl font-medium">
                                            Limit Reached
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($units->hasPages())
                    <div class="flex justify-center">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                            {{ $units->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- No Products Message -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-3xl flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No Servers Available</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                            @if(request('category'))
                                No servers found in this category. Try exploring other categories or view all available servers.
                            @else
                                We're currently updating our server catalog. Check back soon for new additions!
                            @endif
                        </p>
                        <div class="flex gap-4 justify-center">
                            @if(request('category'))
                                <a href="{{ route('products.index') }}"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                                    View All Servers
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 transform hover:scale-[1.02]">
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
            // Add smooth transition effect
            const button = event.target;
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                window.location.href = '/rentals/create?unit=' + unitId;
            }, 150);
        }
    </script>
</x-app-layout>