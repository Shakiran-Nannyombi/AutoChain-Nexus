@extends('layouts.app')

@section('headerTitle', 'Communications')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'messages' }">
    <div class="flex justify-between items-center mb-6">
        <x-ui.button variant="primary">
            New Message
        </x-ui.button>
    </div>

    <!-- Communication Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-ui.card class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Unread Messages</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ $unreadMessages }}</p>
        </x-ui.card>
        <x-ui.card class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Pending Notifications</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ $pendingNotifications }}</p>
        </x-ui.card>
        <x-ui.card class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Active Alerts</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ $activeAlerts }}</p>
        </x-ui.card>
        <x-ui.card class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Response Rate</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ $responseRate }}</p>
        </x-ui.card>
    </div>

    <!-- Communication Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-4" aria-label="Tabs">
            <button @click="activeTab = 'messages'" :class="{ 'text-primary border-b-2 border-primary': activeTab === 'messages', 'text-gray-500 hover:text-gray-700': activeTab !== 'messages' }" class="px-3 py-2 text-sm font-medium">
                Messages
            </button>
            <button @click="activeTab = 'notifications'" :class="{ 'text-primary border-b-2 border-primary': activeTab === 'notifications', 'text-gray-500 hover:text-gray-700': activeTab !== 'notifications' }" class="px-3 py-2 text-sm font-medium">
                Notifications
            </button>
            <button @click="activeTab = 'alerts'" :class="{ 'text-primary border-b-2 border-primary': activeTab === 'alerts', 'text-gray-500 hover:text-gray-700': activeTab !== 'alerts' }" class="px-3 py-2 text-sm font-medium">
                Alerts
            </button>
            <button @click="activeTab = 'templates'" :class="{ 'text-primary border-b-2 border-primary': activeTab === 'templates', 'text-gray-500 hover:text-gray-700': activeTab !== 'templates' }" class="px-3 py-2 text-sm font-medium">
                Templates
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Messages Section -->
        <div x-show="activeTab === 'messages'" class="space-y-6">
            <!-- Message Filters -->
            <div class="flex flex-wrap gap-4">
                <div class="flex-1">
                    <x-ui.input type="text" placeholder="Search messages..." class="w-full" />
                </div>
                <x-ui.select>
                    <option>All Messages</option>
                    <option>Unread</option>
                    <option>Important</option>
                    <option>Archived</option>
                </x-ui.select>
                <x-ui.select>
                    <option>All Senders</option>
                    <option>Internal</option>
                    <option>Suppliers</option>
                    <option>Customers</option>
                </x-ui.select>
            </div>

            <!-- Messages List -->
            <div class="bg-white rounded-lg shadow">
                <div class="divide-y divide-gray-200">
                    @forelse($chats as $chat)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary">
                                        <span class="text-sm font-medium leading-none text-white">{{ substr($chat->sender->name, 0, 1) }}{{ substr($chat->receiver->name, 0, 1) }}</span>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $chat->sender->name }}</p>
                                    <p class="text-sm text-gray-500">Regarding: Order #{{ $chat->order_id }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $chat->timestamp->diffForHumans() }}</span>
                                <x-ui.button variant="ghost" class="p-1">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </x-ui.button>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">
                            {{ $chat->message }}
                        </p>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">
                        No messages found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Notifications Section -->
        <div x-show="activeTab === 'notifications'" class="space-y-6 hidden">
            <div class="p-4 text-gray-500 bg-white rounded-lg shadow">
                Notifications content will go here.
            </div>
        </div>

        <!-- Alerts Section -->
        <div x-show="activeTab === 'alerts'" class="space-y-6 hidden">
            <div class="p-4 text-gray-500 bg-white rounded-lg shadow">
                Alerts content will go here.
            </div>
        </div>

        <!-- Templates Section -->
        <div x-show="activeTab === 'templates'" class="space-y-6 hidden">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Templates</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-ui.button variant="outline" class="p-3 text-left h-auto flex-col items-start">
                        <h4 class="font-medium text-gray-900">Order Confirmation</h4>
                        <p class="text-sm text-gray-500 text-left">Standard order confirmation template</p>
                    </x-ui.button>
                    <x-ui.button variant="outline" class="p-3 text-left h-auto flex-col items-start">
                        <h4 class="font-medium text-gray-900">Delivery Update</h4>
                        <p class="text-sm text-gray-500 text-left">Template for delivery status updates</p>
                    </x-ui.button>
                    <x-ui.button variant="outline" class="p-3 text-left h-auto flex-col items-start">
                        <h4 class="font-medium text-gray-900">Inventory Alert</h4>
                        <p class="text-sm text-gray-500 text-left">Low stock notification template</p>
                    </x-ui.button>
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
@endsection 