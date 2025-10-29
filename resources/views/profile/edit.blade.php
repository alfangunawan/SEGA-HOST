<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pengaturan Profil
            </h2>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Kartu Ringkasan Profil -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl mb-8 border border-gray-200 dark:border-gray-700">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Auth::user()->profile_photo_url }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="h-16 w-16 rounded-full border-3 border-blue-200 shadow-sm object-cover">
                        @else
                            <div class="h-16 w-16 rounded-full bg-blue-600 flex items-center justify-center border-3 border-blue-200 shadow-sm">
                                <span class="text-xl font-bold text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">
                                Anggota sejak {{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Cepat -->
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status Akun</div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-medium">Aktif</span>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Saldo</div>
                            <div class="text-lg font-bold text-blue-600">
                                Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Utama -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                <!-- Informasi Profil -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Pengaturan Keamanan -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Zona Berbahaya - Lebar Penuh -->
            <div class="w-full">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 shadow-sm rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-200 dark:border-red-800">
                        <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Zona Berbahaya
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                            Tindakan ini tidak dapat dibatalkan dan bersifat permanen
                        </p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
