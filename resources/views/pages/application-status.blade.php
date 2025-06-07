@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Application Status</h2>
                    <div class="flex space-x-2">
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            Refresh Status
                        </button>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            View Logs
                        </button>
                    </div>
                </div>

                <!-- System Health Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">System Status</h3>
                        <div class="mt-2 flex items-center">
                            <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                            <p class="text-lg font-semibold text-gray-900">Operational</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Response Time</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $responseTime ?? '120ms' }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Uptime</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $uptime ?? '99.9%' }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Users</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeUsers ?? 0 }}</p>
                    </div>
                </div>

                <!-- System Components -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Components</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">Database</span>
                            </div>
                            <span class="text-sm text-gray-500">Healthy</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">API Services</span>
                            </div>
                            <span class="text-sm text-gray-500">Healthy</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">File Storage</span>
                            </div>
                            <span class="text-sm text-gray-500">High Usage</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">Cache Service</span>
                            </div>
                            <span class="text-sm text-gray-500">Healthy</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Incidents -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Incidents</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-yellow-500 pl-4">
                            <div class="flex justify-between">
                                <h4 class="text-sm font-medium text-gray-900">High CPU Usage</h4>
                                <span class="text-sm text-gray-500">2 hours ago</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">System experienced high CPU usage, affecting response times.</p>
                        </div>
                        <div class="border-l-4 border-red-500 pl-4">
                            <div class="flex justify-between">
                                <h4 class="text-sm font-medium text-gray-900">Database Connection Issues</h4>
                                <span class="text-sm text-gray-500">1 day ago</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Temporary database connection issues resolved.</p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Schedule -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Schedule</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Database Optimization</h4>
                                <p class="text-sm text-gray-500">Scheduled for next week</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                Scheduled
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">System Update</h4>
                                <p class="text-sm text-gray-500">Planned for next month</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                Planned
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Metrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">CPU Usage</h4>
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-primary rounded-full" style="width: 65%"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">65% of capacity</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Memory Usage</h4>
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-primary rounded-full" style="width: 45%"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">45% of capacity</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Storage Usage</h4>
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-primary rounded-full" style="width: 80%"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">80% of capacity</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Network Usage</h4>
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-primary rounded-full" style="width: 30%"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">30% of capacity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 