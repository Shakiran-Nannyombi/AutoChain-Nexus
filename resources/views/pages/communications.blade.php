@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Communications</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        New Message
                    </button>
                </div>

                <!-- Communication Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Unread Messages</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $unreadMessages ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Pending Notifications</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $pendingNotifications ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Alerts</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeAlerts ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Response Rate</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $responseRate ?? '0%' }}</p>
                    </div>
                </div>

                <!-- Communication Tabs -->
                <div class="mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <button class="px-3 py-2 text-sm font-medium text-primary border-b-2 border-primary">
                            Messages
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Notifications
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Alerts
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Templates
                        </button>
                    </nav>
                </div>

                <!-- Messages Section -->
                <div class="space-y-6">
                    <!-- Message Filters -->
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1">
                            <input type="text" placeholder="Search messages..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <select class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            <option>All Messages</option>
                            <option>Unread</option>
                            <option>Important</option>
                            <option>Archived</option>
                        </select>
                        <select class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                            <option>All Senders</option>
                            <option>Internal</option>
                            <option>Suppliers</option>
                            <option>Customers</option>
                        </select>
                    </div>

                    <!-- Messages List -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="divide-y divide-gray-200">
                            @for ($i = 0; $i < 5; $i++)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary">
                                                <span class="text-sm font-medium leading-none text-white">JD</span>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">John Doe</p>
                                            <p class="text-sm text-gray-500">Regarding: Order #12345</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">2 hours ago</span>
                                        <button class="text-gray-400 hover:text-gray-500">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-700">
                                    Hello, I wanted to follow up on the recent order. Could you please provide an update on the delivery status?
                                </p>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Message Templates -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Templates</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button class="p-3 text-left border rounded-md hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">Order Confirmation</h4>
                                <p class="text-sm text-gray-500">Standard order confirmation template</p>
                            </button>
                            <button class="p-3 text-left border rounded-md hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">Delivery Update</h4>
                                <p class="text-sm text-gray-500">Template for delivery status updates</p>
                            </button>
                            <button class="p-3 text-left border rounded-md hover:bg-gray-50">
                                <h4 class="font-medium text-gray-900">Inventory Alert</h4>
                                <p class="text-sm text-gray-500">Low stock notification template</p>
                            </button>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Email Notifications</h4>
                                    <p class="text-sm text-gray-500">Receive notifications via email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">SMS Notifications</h4>
                                    <p class="text-sm text-gray-500">Receive notifications via SMS</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Push Notifications</h4>
                                    <p class="text-sm text-gray-500">Receive notifications in the app</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 