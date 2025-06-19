@extends('layouts.app')

@section('headerTitle', 'Settings')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-8">
        <!-- Activity Summary -->
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6">Activity Summary</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <x-ui.card class="bg-indigo-100 p-4 flex flex-col items-center justify-center">
                    <x-ui.card-content class="text-center">
                        <p class="text-2xl font-bold text-blue-700">{{ $activitySummary['ordersProcessed'] }}</p>
                        <p class="text-sm text-gray-500">Orders Processed</p>
                    </x-ui.card-content>
                </x-ui.card>
                <x-ui.card class="bg-green-100 p-4 flex flex-col items-center justify-center">
                    <x-ui.card-content class="text-center">
                        <p class="text-2xl font-bold text-green-700">{{ $activitySummary['vendorMeetings'] }}</p>
                        <p class="text-sm text-gray-500">Vendor Meetings</p>
                    </x-ui.card-content>
                </x-ui.card>
                <x-ui.card class="bg-purple-100 p-4 flex flex-col items-center justify-center">
                    <x-ui.card-content class="text-center">
                        <p class="text-2xl font-bold text-purple-700">{{ $activitySummary['reportsGenerated'] }}</p>
                        <p class="text-sm text-gray-500">Reports Generated</p>
                    </x-ui.card-content>
                </x-ui.card>
                <x-ui.card class="bg-orange-100 p-4 flex flex-col items-center justify-center">
                    <x-ui.card-content class="text-center">
                        <p class="text-2xl font-bold text-orange-700">{{ $activitySummary['taskCompletion'] }}%</p>
                        <p class="text-sm text-gray-500">Task Completion</p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>

        <!-- Notification Preferences -->
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6">Notification Preferences</h3>
            <form method="POST" action="{{ route('settings.update') }}" x-data="{
                emailNotifications: {{ $user->email_notifications ? 'true' : 'false' }},
                inventoryAlerts: {{ $user->inventory_alerts ? 'true' : 'false' }},
                productionUpdates: {{ $user->production_updates ? 'true' : 'false' }},
                vendorCommunications: {{ $user->vendor_communications ? 'true' : 'false' }},
                reportGeneration: {{ $user->report_generation ? 'true' : 'false' }}
            }">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Email Notifications</p>
                            <p class="text-sm text-gray-500">Receive notifications via email</p>
                        </div>
                        <x-ui.switch name="email_notifications" :checked="$user->email_notifications" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Inventory Alerts</p>
                            <p class="text-sm text-gray-500">Low stock warnings</p>
                        </div>
                        <x-ui.switch name="inventory_alerts" :checked="$user->inventory_alerts" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Production Updates</p>
                            <p class="text-sm text-gray-500">Manufacturing status changes</p>
                        </div>
                        <x-ui.switch name="production_updates" :checked="$user->production_updates" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Vendor Communications</p>
                            <p class="text-sm text-gray-500">Messages from suppliers</p>
                        </div>
                        <x-ui.switch name="vendor_communications" :checked="$user->vendor_communications" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Report Generation</p>
                            <p class="text-sm text-gray-500">When reports are ready</p>
                        </div>
                        <x-ui.switch name="report_generation" :checked="$user->report_generation" />
                    </div>
                    <x-ui.button type="submit" variant="primary" class="mt-4">Save Notification Preferences</x-ui.button>
                </div>
            </form>
        </div>

        <!-- Security Settings -->
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6">Security Settings</h3>
            <form method="POST" action="{{ route('settings.update') }}" x-data="{
                twoFactorAuthentication: {{ $user->two_factor_authentication ? 'true' : 'false' }}
            }">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <x-ui.input type="password" name="current_password" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <x-ui.input type="password" name="new_password" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <x-ui.input type="password" name="new_password_confirmation" class="mt-1 block w-full" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Two-Factor Authentication</p>
                            <p class="text-sm text-gray-500">Enable 2FA for extra security</p>
                        </div>
                        <x-ui.switch name="two_factor_authentication" :checked="$user->two_factor_authentication" />
                    </div>
                    <x-ui.button type="submit" variant="primary" class="mt-4">Change Password</x-ui.button>
                </div>
            </form>
        </div>

        <!-- System Preferences -->
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6">System Preferences</h3>
            <form method="POST" action="{{ route('settings.update') }}" x-data="{
                timeZone: '{{ $user->time_zone ?? 'UTC-5 (Eastern Time)' }}',
                language: '{{ $user->language ?? 'English (US)' }}',
                dateFormat: '{{ $user->date_format ?? 'MM/DD/YYYY' }}',
                darkMode: {{ $user->dark_mode ? 'true' : 'false' }},
                autoRefreshDashboard: {{ $user->auto_refresh_dashboard ? 'true' : 'false' }}
            }">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Time Zone</label>
                        <x-ui.select name="time_zone" class="mt-1 block w-full" x-model="timeZone">
                            <option value="UTC-5 (Eastern Time)">UTC-5 (Eastern Time)</option>
                            <option value="UTC">UTC</option>
                            <option value="EST">EST</option>
                            <option value="CST">CST</option>
                            <option value="PST">PST</option>
                        </x-ui.select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Language</label>
                        <x-ui.select name="language" class="mt-1 block w-full" x-model="language">
                            <option value="English (US)">English (US)</option>
                            <option value="Spanish (ES)">Spanish (ES)</option>
                            <option value="French (FR)">French (FR)</option>
                        </x-ui.select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Format</label>
                        <x-ui.select name="date_format" class="mt-1 block w-full" x-model="dateFormat">
                            <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                            <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                            <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                        </x-ui.select>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Dark Mode</p>
                            <p class="text-sm text-gray-500">Use dark theme</p>
                        </div>
                        <x-ui.switch name="dark_mode" :checked="$user->dark_mode" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Auto-refresh Dashboard</p>
                            <p class="text-sm text-gray-500">Update data automatically</p>
                        </div>
                        <x-ui.switch name="auto_refresh_dashboard" :checked="$user->auto_refresh_dashboard" />
                    </div>
                    <x-ui.button type="submit" variant="primary" class="mt-4">Save Preferences</x-ui.button>
                </div>
            </form>
        </div>

        <!-- Advanced Settings -->
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-6">Advanced Settings</h3>
            <div class="space-y-4">
                <x-ui.button variant="outline" size="sm" class="px-1 text-xs">Export Data</x-ui.button>
                <x-ui.button variant="outline" size="sm" class="px-1 text-xs">Import Settings</x-ui.button>
                <x-ui.button variant="outline" size="sm" class="px-1 text-xs text-red-600 border-red-200 hover:bg-red-50 hover:text-red-700">Reset to Defaults</x-ui.button>
            </div>
        </div>
    </div>
</div>
@endsection