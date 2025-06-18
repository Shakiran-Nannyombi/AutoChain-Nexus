@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Settings</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        Save Changes
                    </button>
                </div>

                <!-- Settings Navigation -->
                <div class="mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <button class="px-3 py-2 text-sm font-medium text-primary border-b-2 border-primary">
                            General
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Notifications
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Security
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Integrations
                        </button>
                    </nav>
                </div>

                <!-- General Settings -->
                <div class="space-y-6">
                    <!-- Company Information -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" value="{{ $companyName ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Business Type</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>Manufacturing</option>
                                    <option>Retail</option>
                                    <option>Wholesale</option>
                                    <option>Service</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" value="{{ $companyEmail ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" value="{{ $companyPhone ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- System Preferences -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Preferences</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time Zone</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>UTC</option>
                                    <option>EST</option>
                                    <option>CST</option>
                                    <option>PST</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date Format</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>MM/DD/YYYY</option>
                                    <option>DD/MM/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Currency</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>USD ($)</option>
                                    <option>EUR (€)</option>
                                    <option>GBP (£)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Settings -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                                <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" value="{{ $lowStockThreshold ?? 10 }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reorder Point</label>
                                <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" value="{{ $reorderPoint ?? 5 }}">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" {{ $enableAutoReorder ?? false ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900">Enable Automatic Reordering</label>
                            </div>
                        </div>
                    </div>

                    <!-- User Preferences -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">User Preferences</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" {{ $enableEmailNotifications ?? true ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900">Email Notifications</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" {{ $enableSMSNotifications ?? false ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900">SMS Notifications</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" {{ $enableDashboardAlerts ?? true ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900">Dashboard Alerts</label>
                            </div>
                        </div>
                    </div>

                    <!-- Backup Settings -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Backup Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Backup Frequency</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>Daily</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Retention Period</label>
                                <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                    <option>7 days</option>
                                    <option>30 days</option>
                                    <option>90 days</option>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" {{ $enableCloudBackup ?? true ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900">Enable Cloud Backup</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 