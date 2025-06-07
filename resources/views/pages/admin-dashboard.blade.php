@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Admin Dashboard</h2>
                    <div class="flex space-x-2">
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            System Settings
                        </button>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            View Logs
                        </button>
                    </div>
                </div>

                <!-- System Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Active accounts</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">System Health</h3>
                        <div class="mt-2 flex items-center">
                            <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                            <p class="text-lg font-semibold text-gray-900">Healthy</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Storage Usage</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $storageUsage ?? '0%' }}</p>
                        <p class="text-sm text-gray-500">Of total capacity</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Sessions</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeSessions ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Current users</p>
                    </div>
                </div>

                <!-- User Management -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">User Management</h3>
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            Add User
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary">
                                                    <span class="text-sm font-medium leading-none text-white">JD</span>
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">John Doe</div>
                                                <div class="text-sm text-gray-500">john@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Admin
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        2 hours ago
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-primary hover:text-primary-dark mr-3">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900">Security Settings</h4>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Two-Factor Authentication</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Session Timeout</span>
                                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                        <option>30 minutes</option>
                                        <option>1 hour</option>
                                        <option>2 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900">System Maintenance</h4>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Backup Frequency</span>
                                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                        <option>Daily</option>
                                        <option>Weekly</option>
                                        <option>Monthly</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Log Retention</span>
                                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                        <option>7 days</option>
                                        <option>30 days</option>
                                        <option>90 days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Log -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity Log</h3>
                    <div class="space-y-4">
                        @for ($i = 0; $i < 5; $i++)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary">
                                        <span class="text-sm font-medium leading-none text-white">JD</span>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">John Doe</p>
                                    <p class="text-sm text-gray-500">Updated system settings</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">2 hours ago</span>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 