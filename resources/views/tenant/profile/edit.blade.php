@extends('layouts.tenant')

@section('title', 'Profile Settings')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 font-dm">Profile Settings</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">Manage your account information and preferences</p>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-royal-purple to-tiffany-blue rounded-xl flex items-center justify-center flex-shrink-0 ml-4">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Profile Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Profile Information</h2>
                <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
            </div>

            <form method="post" action="{{ route('tenant.profile.update', $tenant->slug) }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('name') border-red-500 @enderror"
                               required 
                               autofocus 
                               autocomplete="name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('email') border-red-500 @enderror"
                               required 
                               autocomplete="username">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.726-1.36 3.491 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    Your email address is unverified.
                                    <button form="send-verification" class="underline text-sm text-yellow-600 hover:text-yellow-500">
                                        Click here to re-send the verification email.
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-royal-purple text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-purple transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>

                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 3000)"
                             class="text-sm text-green-600 font-medium">
                            Profile updated successfully!
                        </div>
                    @endif
                </div>
            </form>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>
        </div>
    </div>

    {{-- Password Update --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Update Password</h2>
                <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
            </div>

            <form method="post" action="{{ route('tenant.profile.password.update', $tenant->slug) }}" class="space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('current_password') border-red-500 @enderror"
                           autocomplete="current-password">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('password') border-red-500 @enderror"
                               autocomplete="new-password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent"
                               autocomplete="new-password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-royal-purple text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-purple transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Update Password
                    </button>

                    @if (session('status') === 'password-updated')
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 3000)"
                             class="text-sm text-green-600 font-medium">
                            Password updated successfully!
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Account Actions --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Account Actions</h2>
                <p class="text-sm text-gray-600">Manage your account and view additional options.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                {{-- Activity --}}
                <a href="{{ route('tenant.profile.activity', $tenant->slug) }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Activity Log</h3>
                        <p class="text-xs text-gray-500">View your recent activity</p>
                    </div>
                </a>

                {{-- Preferences --}}
                <a href="{{ route('tenant.profile.preferences', $tenant->slug) }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Preferences</h3>
                        <p class="text-xs text-gray-500">Customize your experience</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h2>
                <p class="text-sm text-red-600">Once you delete your account, there is no going back. Please be certain.</p>
            </div>

            <form method="post" action="{{ route('tenant.profile.destroy', $tenant->slug) }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-sm font-medium text-red-700 mb-2">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Enter your password to confirm deletion"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
