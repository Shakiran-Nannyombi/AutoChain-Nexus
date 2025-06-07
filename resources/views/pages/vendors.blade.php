@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Vendor Management</h2>
                    <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md">
                        Add New Vendor
                    </button>
                </div>

                <!-- Vendor Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Total Vendors</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalVendors ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Active Contracts</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeContracts ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Average Rating</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($averageRating ?? 0, 1) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm font-medium text-gray-500">Pending Approvals</h3>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $pendingApprovals ?? 0 }}</p>
                    </div>
                </div>

                <!-- Vendor List -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold">Vendor Directory</h3>
                        <div class="flex space-x-2">
                            <input type="text" placeholder="Search vendors..." class="px-4 py-2 border rounded-md">
                            <select class="px-4 py-2 border rounded-md">
                                <option value="">All Categories</option>
                                <option value="raw_materials">Raw Materials</option>
                                <option value="equipment">Equipment</option>
                                <option value="services">Services</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($vendors ?? [] as $vendor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ $vendor->logo_url }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $vendor->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $vendor->type }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $vendor->category }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $vendor->contact_name }}</div>
                                            <div>{{ $vendor->contact_email }}</div>
                                            <div>{{ $vendor->contact_phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $vendor->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">{{ number_format($vendor->rating, 1) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($vendor->status === 'active') bg-green-100 text-green-800
                                                @elseif($vendor->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($vendor->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button class="text-primary hover:text-primary-dark">View</button>
                                                <button class="text-primary hover:text-primary-dark">Edit</button>
                                                <button class="text-red-600 hover:text-red-900">Deactivate</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No vendors found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Contract Management -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Active Contracts</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Contract Overview</h4>
                            <div class="space-y-4">
                                @forelse($activeContracts ?? [] as $contract)
                                    <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $contract->vendor_name }}</p>
                                                <p class="text-xs text-gray-500">Contract #{{ $contract->number }}</p>
                                            </div>
                                            <span class="text-xs text-gray-500">Expires: {{ $contract->expiry_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-xs text-gray-500">
                                                <span>Value: ${{ number_format($contract->value, 2) }}</span>
                                                <span>Status: {{ ucfirst($contract->status) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No active contracts</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Contract Performance</h4>
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
                                        <span class="text-sm font-medium text-gray-700">Quality Compliance</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($qualityCompliance ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $qualityCompliance ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Performance -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Vendor Performance</h3>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Top Performers</h4>
                                <div class="space-y-2">
                                    @forelse($topPerformers ?? [] as $vendor)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $vendor->name }}</span>
                                            <span class="text-sm text-gray-500">{{ number_format($vendor->performance, 1) }}%</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No performance data available</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Areas for Improvement</h4>
                                <div class="space-y-2">
                                    @forelse($improvementAreas ?? [] as $area)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-900">{{ $area->name }}</span>
                                            <span class="text-sm text-gray-500">{{ number_format($area->score, 1) }}%</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No improvement areas identified</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Recent Evaluations</h4>
                                <div class="space-y-2">
                                    @forelse($recentEvaluations ?? [] as $evaluation)
                                        <div class="border-b pb-2 last:border-b-0 last:pb-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $evaluation->vendor_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $evaluation->date->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">Score: {{ number_format($evaluation->score, 1) }}/10</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No recent evaluations</p>
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