@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Analytics Dashboard</h2>
                    <div class="flex space-x-2">
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            Export Data
                        </button>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            Schedule Report
                        </button>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="{{ ($revenueGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($revenueGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($revenueGrowth ?? 0, 1) }}%
                            </span>
                            vs last period
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Order Volume</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($orderVolume ?? 0) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="{{ ($orderGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($orderGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($orderGrowth ?? 0, 1) }}%
                            </span>
                            vs last period
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Average Order Value</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">${{ number_format($averageOrderValue ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="{{ ($aovGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($aovGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($aovGrowth ?? 0, 1) }}%
                            </span>
                            vs last period
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Customer Satisfaction</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($customerSatisfaction ?? 0, 1) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="{{ ($satisfactionGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($satisfactionGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($satisfactionGrowth ?? 0, 1) }}%
                            </span>
                            vs last period
                        </p>
                    </div>
                </div>

                <!-- Revenue Trends -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Revenue Trends</h3>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="h-80">
                            <!-- Chart will be rendered here -->
                            <div class="flex items-center justify-center h-full text-gray-500">
                                Revenue trend chart visualization
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Performance Metrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Top Performing Products</h4>
                            <div class="space-y-4">
                                @forelse($topProducts ?? [] as $product)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $product->name }}</span>
                                            <span class="text-sm font-medium text-gray-700">${{ number_format($product->revenue, 2) }}</span>
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
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Customer Segments</h4>
                            <div class="space-y-4">
                                @forelse($customerSegments ?? [] as $segment)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $segment->name }}</span>
                                            <span class="text-sm font-medium text-gray-700">{{ number_format($segment->percentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $segment->percentage }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No segment data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insights -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Key Insights</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($insights ?? [] as $insight)
                            <div class="bg-white p-4 rounded-lg shadow">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 rounded-lg
                                            @if($insight->type === 'positive') bg-green-100
                                            @elseif($insight->type === 'negative') bg-red-100
                                            @else bg-yellow-100
                                            @endif">
                                            <svg class="w-6 h-6
                                                @if($insight->type === 'positive') text-green-600
                                                @elseif($insight->type === 'negative') text-red-600
                                                @else text-yellow-600
                                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $insight->title }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">{{ $insight->description }}</p>
                                        <p class="mt-2 text-xs text-gray-400">{{ $insight->date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-4 text-sm text-gray-500">
                                No insights available
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Forecast -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Forecast</h3>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Revenue Forecast</h4>
                                <div class="space-y-2">
                                    @forelse($revenueForecast ?? [] as $forecast)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $forecast->period }}</span>
                                            <span class="text-sm text-gray-500">${{ number_format($forecast->amount, 2) }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No forecast data available</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Demand Forecast</h4>
                                <div class="space-y-2">
                                    @forelse($demandForecast ?? [] as $forecast)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $forecast->period }}</span>
                                            <span class="text-sm text-gray-500">{{ number_format($forecast->quantity) }} units</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No forecast data available</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Trend Analysis</h4>
                                <div class="space-y-2">
                                    @forelse($trendAnalysis ?? [] as $trend)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $trend->category }}</span>
                                            <span class="text-sm text-gray-500">{{ number_format($trend->growth, 1) }}%</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No trend data available</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 