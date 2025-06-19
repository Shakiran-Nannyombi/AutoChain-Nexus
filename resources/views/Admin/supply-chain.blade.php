@extends('layouts.app')

@section('headerTitle', 'Supply Chain Management')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
<!-- Supply Chain Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-pink-200 p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-700">Active Shipments</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeShipments ?? 0 }}</p>
    </div>
    <div class="bg-orange-200 p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-700">On-Time Delivery</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($onTimeDelivery ?? 0, 1) }}%</p>
    </div>
    <div class="bg-blue-200 p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-700">Active Suppliers</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeSuppliers ?? 0 }}</p>
    </div>
    <div class="bg-green-200 p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-700">Completed Today</h3>
        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $completedToday ?? 0 }}</p>
    </div>
</div>

<div class="flex flex-row justify-between items-center">
    <div class=" mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Supply Chain Overview</h2>
    </div>

<!-- Add Purchase Order Button -->
 <div class="flex justify-between items-center mb-6">
    <button id="newPurchaseOrderButton" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">
        New Purchase Order
    </button>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Active Shipments Table -->
    <div class="md:col-span-2 mb-6">
        <h3 class="text-lg font-semibold mb-3">Active Shipments</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin -> Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activeShipmentsData ?? [] as $shipment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $shipment->shipment_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipment->origin }} -> {{ $shipment->destination }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full progress-bar" data-width="{{ $shipment->progress }}"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipment->eta }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($shipment->status === 'delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                @elseif($shipment->status === 'in-transit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                @elseif($shipment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">View</button>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">Update</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No active shipments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Supplier Performance -->
    <div class="md:col-span-1 mb-6">
        <h3 class="text-lg font-semibold mb-3">Supplier Performance</h3>
        <div class="space-y-4">
            @forelse($supplierPerformanceData ?? [] as $supplier)
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $supplier->name }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($supplier->rating, 1) }} ★</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $supplier->category }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $supplier->orders }} orders</p>
                    <div class="flex justify-between mt-2">
                        <span class="text-sm text-gray-500">On-time delivery</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($supplier->on_time_delivery, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-blue-600 h-2 rounded-full progress-bar" data-width="{{ $supplier->on_time_delivery }}"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No supplier data available</p>
            @endforelse
        </div>
    </div>
</div>

<x-add-purchase-order-modal/>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addPurchaseOrderModal = document.getElementById('addPurchaseOrderModal');
        if (addPurchaseOrderModal) {
            addPurchaseOrderModal.classList.add('hidden');
        }

        // Handle progress bar widths with JavaScript
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(function(bar) {
            const width = bar.dataset.width;
            bar.style.width = width + '%';
        });
    });
</script>
@endpush