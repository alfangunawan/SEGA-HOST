<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 mb-6">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Profile Settings') }}
            </h2>
            <p class="text-blue-100 mt-2">Manage your account settings and preferences</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Overview Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-xl mb-8 border-0">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                    <div class="flex items-center space-x-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ Auth::user()->profile_photo_url }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="h-20 w-20 rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                            <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-2xl font-bold text-gray-600">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">{{ Auth::user()->name }}</h3>
                            <p class="text-blue-100">{{ Auth::user()->email }}</p>
                            <p class="text-blue-200 text-sm mt-1">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border-0">
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Profile Information
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your account's profile information and email address</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Sidebar -->
                <div class="space-y-6">
                    <!-- Account Security -->
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border-0">
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Security
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Account Stats -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Quick Stats
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Account Status</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Email Verified</span>
                                <span class="px-2 py-1 {{ Auth::user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs rounded-full">
                                    {{ Auth::user()->email_verified_at ? 'Yes' : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Profile Photo</span>
                                <span class="px-2 py-1 {{ Auth::user()->profile_photo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-xs rounded-full">
                                    {{ Auth::user()->profile_photo ? 'Set' : 'Not Set' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-8">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 shadow-xl rounded-xl overflow-hidden">
                    <div class="bg-red-100 dark:bg-red-900/30 px-6 py-4 border-b border-red-200 dark:border-red-800">
                        <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Danger Zone
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">Irreversible and destructive actions</p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .gradient-border {
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            padding: 2px;
            border-radius: 0.75rem;
        }
        
        .gradient-border > div {
            background: white;
            border-radius: calc(0.75rem - 2px);
        }
        
        @media (prefers-color-scheme: dark) {
            .gradient-border > div {
                background: #1f2937;
            }
        }
    </style>
</x-app-layout>
