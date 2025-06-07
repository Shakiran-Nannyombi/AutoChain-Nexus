@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Manufacturing Management</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        Create Work Order
                    </button>
                </div>

                <!-- Production Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Work Orders</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeWorkOrders ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Completed Today</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $completedToday ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Quality Rate</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($qualityRate ?? 0, 1) }}%</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Efficiency</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($efficiency ?? 0, 1) }}%</p>
                    </div>
                </div>

                <!-- Work Orders Table -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Active Work Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($workOrders ?? [] as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->product_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'on_hold') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->start_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->due_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button class="text-primary hover:text-primary-dark">View</button>
                                                <button class="text-primary hover:text-primary-dark">Update</button>
                                                <button class="text-red-600 hover:text-red-900">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No active work orders
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quality Control -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Quality Control</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Recent Inspections</h4>
                            <div class="space-y-2">
                                @forelse($recentInspections ?? [] as $inspection)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $inspection->product_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $inspection->date->format('M d, Y H:i') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($inspection->result === 'passed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($inspection->result) }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No recent inspections</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Quality Metrics</h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">First Pass Yield</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($firstPassYield ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $firstPassYield ?? 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">Defect Rate</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($defectRate ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $defectRate ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Schedule -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Production Schedule</h3>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="grid grid-cols-7 gap-2">
                            @foreach($schedule ?? [] as $day)
                                <div class="text-center">
                                    <div class="text-sm font-medium text-gray-500">{{ $day->date->format('D') }}</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $day->date->format('M d') }}</div>
                                    <div class="mt-2">
                                        <div class="text-xs text-gray-500">Capacity: {{ $day->capacity }}%</div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $day->capacity }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 