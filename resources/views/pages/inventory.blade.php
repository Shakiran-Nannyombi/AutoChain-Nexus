@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Inventory Management</h2>
        <button id="addItemButton" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span>Add Item</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Items</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalItems }}</p>
            </div>
            <div class="text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.368H6.622a2.25 2.25 0 01-2.247-2.368L3.75 7.5M10 11.25h4m-4 2.25h4m-4 2.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Low Stock Items</h3>
                <p class="text-3xl font-bold text-orange-500">{{ $lowStockItems }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Critical Items</h3>
                <p class="text-3xl font-bold text-red-600">{{ $criticalItems }}</p>
            </div>
            <div class="text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.368H6.622a2.25 2.25 0 01-2.247-2.368L3.75 7.5M10 11.25h4m-4 2.25h4m-4 2.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Total Value</h3>
                <p class="text-3xl font-bold text-green-600">{{ $totalValue }}</p>
            </div>
        </div>
    </div>

    <!-- Inventory Items List -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Inventory Items</h3>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" placeholder="Search items..." class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 sm:text-sm">
            </div>
        </div>

        <div class="space-y-4">
            @forelse($inventoryItems as $item)
                @php
                    $stockPercentage = ($item->max_stock > 0) ? ($item->current_stock / $item->max_stock) * 100 : 0;
                    $status = 'normal';
                    $statusColor = 'bg-green-100 text-green-800';

                    if ($item->current_stock <= $item->critical_stock_threshold) {
                        $status = 'critical';
                        $statusColor = 'bg-red-100 text-red-800';
                    } elseif ($item->current_stock <= $item->min_stock_threshold) {
                        $status = 'low';
                        $statusColor = 'bg-yellow-100 text-yellow-800';
                    }
                @endphp
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between shadow-sm">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $item->name }}</h4>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $item->sku }} • {{ $item->category }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            <p class="font-medium">Location</p>
                            <p>{{ $item->location }}</p>
                        </div>
                        <div class="w-32">
                            <p class="text-sm text-gray-600 font-medium mb-1">Stock</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full {{ $stockPercentage < 25 ? 'bg-red-500' : ($stockPercentage < 50 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $stockPercentage }}%;"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->current_stock }}/{{ $item->max_stock }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No inventory items found. Click "Add Item" to get started!</p>
            @endforelse
        </div>
    </div>
</div>

<x-add-item-modal/>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addItemButton = document.getElementById('addItemButton');
        const addItemModal = document.getElementById('addItemModal');
        const closeAddItemModalButton = document.getElementById('closeAddItemModal');
        const cancelAddItemModalButton = document.getElementById('cancelAddItemModal');


        if (addItemButton && addItemModal && closeAddItemModalButton && cancelAddItemModalButton) {
            addItemButton.onclick = function(e) {
                e.preventDefault();
                addItemModal.classList.remove('hidden');
            };

            closeAddItemModalButton.onclick = function(e) {
                e.preventDefault();
                addItemModal.classList.add('hidden');
            };

            cancelAddItemModalButton.onclick = function(e) {
                e.preventDefault();
                addItemModal.classList.add('hidden');
            };

            addItemModal.addEventListener('click', function(event) {
                if (event.target === addItemModal) {
                    addItemModal.classList.add('hidden');
                }
            });
        } else {
            console.error('Could not find one or more elements for the Add Item modal.');
        }
    });
</script>
@endpush