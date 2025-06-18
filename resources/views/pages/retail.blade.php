@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Retail Management</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        New Sale
                    </button>
                </div>

                <!-- Sales Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Today's Sales</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format($todaySales ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="{{ ($salesGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($salesGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($salesGrowth ?? 0, 1) }}%
                            </span>
                            vs yesterday
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                        <p class="text-sm text-gray-500 mt-1">Today's transactions</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Average Order Value</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format($averageOrderValue ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Per transaction</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Customer Satisfaction</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($customerSatisfaction ?? 0, 1) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Out of 5.0</p>
                    </div>
                </div>

                <!-- Recent Sales -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Recent Sales</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentSales ?? [] as $sale)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $sale->order_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $sale->customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $sale->item_count }} items
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($sale->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($sale->status === 'completed') bg-green-100 text-green-800
                                                @elseif($sale->status === 'processing') bg-yellow-100 text-yellow-800
                                                @elseif($sale->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $sale->date->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button class="text-primary hover:text-primary-dark">View</button>
                                                <button class="text-primary hover:text-primary-dark">Print</button>
                                                <button class="text-red-600 hover:text-red-900">Refund</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No recent sales
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Product Performance -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Product Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Top Selling Products</h4>
                            <div class="space-y-4">
                                @forelse($topProducts ?? [] as $product)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $product->name }}</span>
                                            <span class="text-sm font-medium text-gray-700">{{ $product->quantity }} sold</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $product->percentage }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No product data available</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Inventory Status</h4>
                            <div class="space-y-4">
                                @forelse($inventoryStatus ?? [] as $item)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $item->name }}</span>
                                            <span class="text-sm font-medium text-gray-700">{{ $item->quantity }} in stock</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $item->percentage }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No inventory data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Insights -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Customer Insights</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Customer Segments</h4>
                            <div class="space-y-2">
                                @forelse($customerSegments ?? [] as $segment)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $segment->name }}</span>
                                        <span class="text-sm text-gray-500">{{ number_format($segment->percentage, 1) }}%</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No segment data available</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Purchase Frequency</h4>
                            <div class="space-y-2">
                                @forelse($purchaseFrequency ?? [] as $frequency)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $frequency->range }}</span>
                                        <span class="text-sm text-gray-500">{{ $frequency->count }} customers</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No frequency data available</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Recent Reviews</h4>
                            <div class="space-y-4">
                                @forelse($recentReviews ?? [] as $review)
                                    <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex items-center mb-1">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">{{ $review->customer_name }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $review->date->format('M d, Y') }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No recent reviews</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 