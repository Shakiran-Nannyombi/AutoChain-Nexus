@extends('layouts.app')

@section('headerTitle', 'Retail Management')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Monthly Sales Card -->
        <x-ui.card class="p-4 flex items-start space-x-4 bg-white rounded-lg shadow">
            <div class="bg-blue-100 p-3 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Monthly Sales</h3>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $monthlySalesCount }}</p>
                <p class="text-sm mt-1 @if($monthlySalesGrowth >= 0) text-green-600 @else text-red-600 @endif">
                    {{ ($monthlySalesGrowth >= 0 ? '+' : '') }}{{ number_format($monthlySalesGrowth, 1) }}% vs last month
                </p>
            </div>
        </x-ui.card>

        <!-- Total Revenue Card -->
        <x-ui.card class="p-4 flex items-start space-x-4 bg-white rounded-lg shadow">
            <div class="bg-green-100 p-3 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1L17 11.2V10a2 2 0 00-2-2h-2a2 2 0 00-2 2v.2L9.401 11C9.919 10.402 10.89 10 12 10zm0 0H5.25v2m0 4h.086l.607 1.25M7.75 8h.008M16.25 8h.008M13.75 8h.008"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                <p class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($totalRevenue / 1000000, 1) }}M</p>
                <p class="text-sm mt-1 @if($totalRevenueGrowth >= 0) text-green-600 @else text-red-600 @endif">
                    {{ ($totalRevenueGrowth >= 0 ? '+' : '') }}{{ number_format($totalRevenueGrowth, 0) }}% growth
                </p>
            </div>
        </x-ui.card>

        <!-- Active Dealers Card -->
        <x-ui.card class="p-4 flex items-start space-x-4 bg-white rounded-lg shadow">
            <div class="bg-purple-100 p-3 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2m2 0h11.242M10 13a6 6 0 00-6 6v1a2 2 0 002 2h8a2 2 0 002-2v-1a6 6 0 00-6-6zm-3-2a3 3 0 11-6 0 3 3 0 016 0zm10-4a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Active Dealers</h3>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $activeDealers }}</p>
                <p class="text-sm mt-1 text-gray-500">Nationwide</p>
            </div>
        </x-ui.card>

        <!-- Customer Satisfaction Card -->
        <x-ui.card class="p-4 flex items-start space-x-4 bg-white rounded-lg shadow">
            <div class="bg-orange-100 p-3 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.085 11.085a1 1 0 011.829 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Customer Satisfaction</h3>
                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($customerSatisfaction, 1) }}<span class="text-yellow-400">★</span></p>
                <p class="text-sm mt-1 @if($customerSatisfactionGrowth >= 0) text-green-600 @else text-red-600 @endif">
                    {{ ($customerSatisfactionGrowth >= 0 ? '+' : '') }}{{ number_format($customerSatisfactionGrowth, 1) }} this quarter
                </p>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sales Performance by Model -->
        <x-ui.card class="md:col-span-2 p-6 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Sales Performance by Model</h2>
            <div class="space-y-6">
                @foreach($salesPerformanceByModel as $item)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-lg font-medium text-gray-900">{{ $item['model'] }}</span>
                            <span class="text-lg font-semibold text-gray-900">${{ number_format($item['revenue'] / 1000000, 1) }}M</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Sold: {{ $item['sold_units'] }}/{{ $item['sold_units'] + rand(5, 10) }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                            <div class="bg-blue-600 h-2.5 rounded-full progress-bar"
                                 data-width="{{ $item['percentage_sold'] }}"></div>
                        </div>
                        <p class="text-xs @if($item['growth'] >= 0) text-green-600 @else text-red-600 @endif">
                            {{ ($item['growth'] >= 0 ? '+' : '') }}{{ $item['growth'] }}%
                        </p>
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <!-- Top Performing Dealers -->
        <x-ui.card class="p-6 bg-white rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Top Performing Dealers</h2>
            <div class="space-y-4">
                @foreach($topDealersData as $dealer)
                    <div class="flex justify-between items-center py-2 border-b last:border-b-0">
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $dealer['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $dealer['location'] }}</p>
                            <p class="text-sm text-gray-500">Sales: {{ $dealer['sales_units'] }} units</p>
                        </div>
                        <div class="text-right">
                            @php
                                $statusClass = '';
                                if ($dealer['status'] === 'excellent') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($dealer['status'] === 'good') {
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                } elseif ($dealer['status'] === 'average') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($dealer['status'] === 'warning') {
                                    $statusClass = 'bg-red-100 text-red-800';
                                }
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ ucfirst($dealer['status']) }}
                            </span>
                            <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($dealer['revenue'] / 1000000, 1) }}M</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.progress-bar').forEach(function(progressBar) {
            const width = progressBar.getAttribute('data-width');
            progressBar.style.width = (width && !isNaN(width)) ? width + '%' : '0%';
        });
    });
</script>
@endpush
@endsection