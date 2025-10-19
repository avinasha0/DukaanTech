@extends('layouts.tenant')

@section('title', 'Preferences')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 font-dm">Preferences</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">Customize your experience and notification settings</p>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 ml-4">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- General Preferences --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">General Preferences</h2>
                <p class="text-sm text-gray-600">Customize your general application settings.</p>
            </div>

            <form method="post" action="{{ route('tenant.profile.preferences.update', $tenant->slug) }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select id="timezone" 
                                name="timezone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('timezone') border-red-500 @enderror">
                            <option value="">Select timezone</option>
                            <option value="UTC" {{ old('timezone', $user->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ old('timezone', $user->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                            <option value="America/Chicago" {{ old('timezone', $user->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                            <option value="America/Denver" {{ old('timezone', $user->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                            <option value="America/Los_Angeles" {{ old('timezone', $user->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                            <option value="Europe/London" {{ old('timezone', $user->timezone) == 'Europe/London' ? 'selected' : '' }}>London</option>
                            <option value="Europe/Paris" {{ old('timezone', $user->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                            <option value="Asia/Tokyo" {{ old('timezone', $user->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                            <option value="Asia/Shanghai" {{ old('timezone', $user->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                            <option value="Australia/Sydney" {{ old('timezone', $user->timezone) == 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                        <select id="language" 
                                name="language" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-royal-purple focus:border-transparent @error('language') border-red-500 @enderror">
                            <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>French</option>
                            <option value="de" {{ old('language', $user->language) == 'de' ? 'selected' : '' }}>German</option>
                            <option value="it" {{ old('language', $user->language) == 'it' ? 'selected' : '' }}>Italian</option>
                            <option value="pt" {{ old('language', $user->language) == 'pt' ? 'selected' : '' }}>Portuguese</option>
                            <option value="zh" {{ old('language', $user->language) == 'zh' ? 'selected' : '' }}>Chinese</option>
                            <option value="ja" {{ old('language', $user->language) == 'ja' ? 'selected' : '' }}>Japanese</option>
                        </select>
                        @error('language')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="theme" class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('theme', $user->theme) == 'light' ? 'border-royal-purple bg-purple-50' : '' }}">
                            <input type="radio" name="theme" value="light" class="sr-only" {{ old('theme', $user->theme) == 'light' ? 'checked' : '' }}>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <div class="text-sm font-medium text-gray-900">Light</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('theme', $user->theme) == 'dark' ? 'border-royal-purple bg-purple-50' : '' }}">
                            <input type="radio" name="theme" value="dark" class="sr-only" {{ old('theme', $user->theme) == 'dark' ? 'checked' : '' }}>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <div class="text-sm font-medium text-gray-900">Dark</div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('theme', $user->theme) == 'auto' ? 'border-royal-purple bg-purple-50' : '' }}">
                            <input type="radio" name="theme" value="auto" class="sr-only" {{ old('theme', $user->theme) == 'auto' ? 'checked' : '' }}>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-sm font-medium text-gray-900">Auto</div>
                            </div>
                        </label>
                    </div>
                    @error('theme')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-royal-purple text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-purple transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Preferences
                    </button>

                    @if (session('status') === 'preferences-updated')
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 3000)"
                             class="text-sm text-green-600 font-medium">
                            Preferences updated successfully!
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Notification Preferences --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Notification Preferences</h2>
                <p class="text-sm text-gray-600">Choose how you want to be notified about important events.</p>
            </div>

            <form method="post" action="{{ route('tenant.profile.preferences.update', $tenant->slug) }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Email Notifications</h3>
                            <p class="text-sm text-gray-500">Receive notifications via email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="notifications[email]" 
                                   value="1" 
                                   class="sr-only peer" 
                                   {{ old('notifications.email', $user->getNotificationPreference('email', true)) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-royal-purple/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-purple"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Order Updates</h3>
                            <p class="text-sm text-gray-500">Get notified about new orders and order status changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="notifications[orders]" 
                                   value="1" 
                                   class="sr-only peer" 
                                   {{ old('notifications.orders', $user->getNotificationPreference('orders', true)) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-royal-purple/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-purple"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">System Alerts</h3>
                            <p class="text-sm text-gray-500">Receive alerts about system maintenance and updates</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="notifications[system]" 
                                   value="1" 
                                   class="sr-only peer" 
                                   {{ old('notifications.system', $user->getNotificationPreference('system', true)) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-royal-purple/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-purple"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Marketing Emails</h3>
                            <p class="text-sm text-gray-500">Receive promotional emails and product updates</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="notifications[marketing]" 
                                   value="1" 
                                   class="sr-only peer" 
                                   {{ old('notifications.marketing', $user->getNotificationPreference('marketing', false)) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-royal-purple/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-purple"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-royal-purple text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-purple transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"/>
                        </svg>
                        Save Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Back to Profile --}}
    <div class="flex justify-end">
        <a href="{{ route('tenant.profile.edit', $tenant->slug) }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Profile
        </a>
    </div>
</div>
@endsection
