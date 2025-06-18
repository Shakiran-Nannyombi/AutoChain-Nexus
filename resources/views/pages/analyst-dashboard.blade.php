@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Analyst Dashboard</h2>
                    <div class="flex space-x-2">
                        <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                            Generate Report
                        </button>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                            Export Data
                        </button>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                        <p class="text-2xl font-semibold text-gray-900">${{ $totalSales ?? '0.00' }}</p>
                        <p class="text-sm text-gray-500">Last 30 days</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Growth Rate</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $growthRate ?? '0%' }}</p>
                        <p class="text-sm text-gray-500">vs last period</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Average Order Value</h3>
                        <p class="text-2xl font-semibold text-gray-900">${{ $averageOrderValue ?? '0.00' }}</p>
                        <p class="text-sm text-gray-500">Per transaction</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Customer Satisfaction</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $customerSatisfaction ?? '0%' }}</p>
                        <p class="text-sm text-gray-500">Based on feedback</p>
                    </div>
                </div>

                <!-- Data Analysis -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Data Analysis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900 mb-4">Sales Trends</h4>
                            <div class="h-64 bg-gray-50 rounded-md flex items-center justify-center">
                                <p class="text-gray-500">Sales chart will be displayed here</p>
                            </div>
                        </div>
                        <div class="p-4 border rounded-md">
                            <h4 class="font-medium text-gray-900 mb-4">Inventory Analysis</h4>
                            <div class="h-64 bg-gray-50 rounded-md flex items-center justify-center">
                                <p class="text-gray-500">Inventory chart will be displayed here</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insights -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Key Insights</h3>
                    <div class="space-y-4">
                        <div class="p-4 border rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Sales Growth</h4>
                                    <p class="mt-1 text-sm text-gray-500">Sales have increased by 15% compared to last month, primarily driven by the new product line.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Inventory Alert</h4>
                                    <p class="mt-1 text-sm text-gray-500">5 items are running low on stock and require immediate attention.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Reports</h3>
                        <button class="text-primary hover:text-primary-dark text-sm font-medium">
                            View All
                        </button>
                    </div>
                    <div class="space-y-4">
                        @for ($i = 0; $i < 5; $i++)
                        <div class="flex items-center justify-between p-4 border rounded-md">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Monthly Sales Report</h4>
                                <p class="text-sm text-gray-500">Generated on {{ now()->subDays($i)->format('M d, Y') }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-primary hover:text-primary-dark text-sm font-medium">
                                    View
                                </button>
                                <button class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                    Download
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 