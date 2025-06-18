@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-4">Supplier Dashboard</h2>
                
                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Active Orders</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-3xl font-bold">{{ $activeOrders ?? 0 }}</p>
                            <p class="text-sm text-gray-500">orders in progress</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Pending Deliveries</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-3xl font-bold">{{ $pendingDeliveries ?? 0 }}</p>
                            <p class="text-sm text-gray-500">scheduled deliveries</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>On-Time Delivery</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-3xl font-bold">{{ number_format($onTimeDelivery ?? 0, 1) }}%</p>
                            <p class="text-sm text-gray-500">delivery rate</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Quality Rating</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-3xl font-bold">{{ number_format($qualityRating ?? 0, 1) }}</p>
                            <p class="text-sm text-gray-500">out of 5.0</p>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <!-- Recent Orders -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Recent Orders</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->item_count }} items
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->due_date->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No recent orders
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Inventory Status -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Inventory Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Stock Levels</h4>
                            <div class="space-y-2">
                                @forelse($stockLevels ?? [] as $item)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->name }}</span>
                                        <span class="text-sm {{ $item->quantity < $item->reorder_point ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $item->quantity }} units
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No inventory items</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Upcoming Restocks</h4>
                            <div class="space-y-2">
                                @forelse($upcomingRestocks ?? [] as $restock)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $restock->item_name }}</span>
                                        <span class="text-sm text-gray-500">{{ $restock->restock_date->format('M d, Y') }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No upcoming restocks</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('supplier.orders') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                            View Orders
                        </a>
                        <a href="{{ route('supplier.inventory') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                            Manage Inventory
                        </a>
                        <a href="{{ route('supplier.deliveries') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                            Schedule Deliveries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 