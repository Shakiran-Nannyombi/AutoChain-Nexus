@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Manufacturer Dashboard</h2>
                    <div class="flex space-x-2">
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            New Work Order
                        </button>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            Quality Report
                        </button>
                    </div>
                </div>

                <!-- Production Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Work Orders</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeWorkOrders ?? 0 }}</p>
                        <p class="text-sm text-gray-500">In production</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Completed Today</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedToday ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Units produced</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Quality Rate</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $qualityRate ?? '0%' }}</p>
                        <p class="text-sm text-gray-500">First pass yield</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Efficiency</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $efficiency ?? '0%' }}</p>
                        <p class="text-sm text-gray-500">Production efficiency</p>
                    </div>
                </div>

                <!-- Active Work Orders -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Active Work Orders</h3>
                        <button class="text-primary hover:text-primary-dark text-sm font-medium">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        WO-{{ 1000 + $i }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Product {{ $i + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ 100 + ($i * 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            In Progress
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ now()->addDays($i + 1)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-primary hover:text-primary-dark mr-3">View</button>
                                        <button class="text-gray-500 hover:text-gray-700">Update</button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quality Control -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quality Control</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900 mb-4">Recent Inspections</h4>
                            <div class="space-y-4">
                                @for ($i = 0; $i < 3; $i++)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Batch #{{ 1000 + $i }}</p>
                                        <p class="text-sm text-gray-500">Product {{ $i + 1 }}</p>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Passed
                                    </span>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900 mb-4">Quality Metrics</h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">First Pass Yield</span>
                                        <span class="text-sm font-medium text-gray-700">95%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: 95%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">Defect Rate</span>
                                        <span class="text-sm font-medium text-gray-700">2%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 2%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Schedule -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Production Schedule</h3>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                        <div class="p-2 border rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">{{ $day }}</h4>
                            <div class="space-y-2">
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-primary rounded-full" style="width: {{ rand(30, 90) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500">{{ rand(1, 5) }} orders</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 