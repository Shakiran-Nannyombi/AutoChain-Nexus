@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Supply Chain Management</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        New Purchase Order
                    </button>
                </div>

                <!-- Supply Chain Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Orders</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeOrders ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Pending Deliveries</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $pendingDeliveries ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">On-Time Delivery</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($onTimeDelivery ?? 0, 1) }}%</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Supplier Performance</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($supplierPerformance ?? 0, 1) }}%</p>
                    </div>
                </div>

                <!-- Purchase Orders -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Recent Purchase Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Delivery</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchaseOrders ?? [] as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->po_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->supplier_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->item_count }} items
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                                @elseif($order->status === 'in_transit') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->expected_delivery->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button class="text-primary hover:text-primary-dark">View</button>
                                                <button class="text-primary hover:text-primary-dark">Track</button>
                                                <button class="text-red-600 hover:text-red-900">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No purchase orders found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Supplier Performance -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Supplier Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Top Suppliers</h4>
                            <div class="space-y-4">
                                @forelse($topSuppliers ?? [] as $supplier)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $supplier->name }}</span>
                                            <span class="text-sm font-medium text-gray-700">{{ number_format($supplier->performance, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $supplier->performance }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No supplier data available</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Performance Metrics</h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">On-Time Delivery</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($onTimeDelivery ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $onTimeDelivery ?? 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">Quality Acceptance</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($qualityAcceptance ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $qualityAcceptance ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logistics Tracking -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Logistics Tracking</h3>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="space-y-4">
                            @forelse($logisticsUpdates ?? [] as $update)
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $update->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $update->description }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $update->timestamp->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No recent logistics updates</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 