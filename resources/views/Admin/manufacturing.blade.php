@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
        <div class="flex-shrink-0 bg-blue-200 rounded-full p-3 mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 012 2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v14zm-12 0h-2a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Daily Production</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $dailyProduction }}</p>
            <p class="text-xs text-green-500">+8% vs yesterday</p>
        </div>
    </div>
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
        <div class="flex-shrink-0 bg-green-200 rounded-full p-3 mr-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8L11 2m7 0H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Overall Efficiency</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $overallEfficiency }}%</p>
            <p class="text-xs text-green-500">+2% this week</p>
        </div>
    </div>
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
        <div class="flex-shrink-0 bg-purple-200 rounded-full p-3 mr-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-4v-2m4 2v-4m0 4h-4m-6 3a2 2 0 11-4 0 2 2 0 014 0zm0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-8 0h12a2 2 0 002-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v9a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Active Workers</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $activeWorkers }}</p>
            <p class="text-xs text-gray-500">Across 4 lines</p>
        </div>
    </div>
    <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center">
        <div class="flex-shrink-0 bg-yellow-200 rounded-full p-3 mr-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20v-6h4v6m2 2h-8a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Quality Score</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $qualityScore }}%</p>
            <p class="text-xs text-green-500">Above target</p>
        </div>
    </div>
</div>

<div class="flex flex-row justify-between items-center">
    <div class=" mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Active Work Orders</h2>
    </div>

<div class="flex justify-between items-center mb-6">
    <button id="createWorkOrderButton" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">
        Create Work Order
    </button>
</div>
</div>

<!-- Work Orders Table -->
<div class="mb-6 mt-6">
    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
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
                            @if($order->status === 'in_progress')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            @elseif($order->status === 'on_hold')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->start_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->due_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="window.location.href='/manufacturing/{{ $order->id }}'" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">View</button>
                                <button data-id="{{ $order->id }}" data-product="{{ $order->product_name }}" data-quantity="{{ $order->quantity }}" data-status="{{ $order->status }}" data-start-date="{{ $order->start_date->format('Y-m-d') }}" data-due-date="{{ $order->due_date->format('Y-m-d') }}" class="edit-work-order bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">Update</button>
                                <form action="/manufacturing/{{ $order->id }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this work order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">Cancel</button>
                                </form>
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Production Lines -->
    <div>
        <h3 class="text-xl font-semibold mb-4">Production Lines</h3>
        <div class="space-y-4">
            @foreach($productionLines as $line)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold">{{ $line['name'] }}</h4>
                    <p class="text-sm text-gray-500">{{ $line['product'] }}</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs font-medium text-gray-700">Progress: {{ $line['progress'] }}</span>
                        @if($line['status'] === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">active</span>
                        @elseif($line['status'] === 'maintenance')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">maintenance</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $line['status'] }}</span>
                        @endif
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                        @if($line['status'] === 'active')
                            <div class="h-2.5 rounded-full bg-blue-600 progress-bar" data-width="{{ $line['efficiency'] }}"></div>
                        @elseif($line['status'] === 'maintenance')
                            <div class="h-2.5 rounded-full bg-yellow-600 progress-bar" data-width="{{ $line['efficiency'] }}"></div>
                        @else
                            <div class="h-2.5 rounded-full bg-gray-600 progress-bar" data-width="{{ $line['efficiency'] }}"></div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Efficiency: {{ $line['efficiency'] }}%</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quality Control -->
    <div>
        <h3 class="text-xl font-semibold mb-4">Quality Control</h3>
        <div class="space-y-4">
            @foreach($qualityControls as $control)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold">{{ $control['name'] }}</h4>
                    <p class="text-sm text-gray-500">Target: {{ $control['target_defect_rate'] }}% defect rate</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-700">Current defect rate: {{ $control['current_defect_rate'] }}%</span>
                        @if($control['status'] === 'good')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">good</span>
                        @elseif($control['status'] === 'excellent')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">excellent</span>
                        @elseif($control['status'] === 'warning')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">warning</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $control['status'] }}</span>
                        @endif
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                        @php
                            $qcProgressBarWidth = ($control['target_defect_rate'] - $control['current_defect_rate']) / $control['target_defect_rate'] * 100;
                        @endphp
                        @if($control['status'] === 'good')
                            <div class="h-2.5 rounded-full bg-blue-600 progress-bar" data-width="{{ $qcProgressBarWidth }}"></div>
                        @elseif($control['status'] === 'excellent')
                            <div class="h-2.5 rounded-full bg-green-600 progress-bar" data-width="{{ $qcProgressBarWidth }}"></div>
                        @elseif($control['status'] === 'warning')
                            <div class="h-2.5 rounded-full bg-yellow-600 progress-bar" data-width="{{ $qcProgressBarWidth }}"></div>
                        @else
                            <div class="h-2.5 rounded-full bg-gray-600 progress-bar" data-width="{{ $qcProgressBarWidth }}"></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<x-add-work-order-modal/>
<x-edit-work-order-modal/>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createWorkOrderButton = document.getElementById('createWorkOrderButton');
        const addWorkOrderModal = document.getElementById('addWorkOrderModal');
        const closeAddWorkOrderModalButton = document.getElementById('closeAddWorkOrderModal');

        if (createWorkOrderButton && addWorkOrderModal && closeAddWorkOrderModalButton) {
            createWorkOrderButton.onclick = function(e) {
                e.preventDefault();
                addWorkOrderModal.classList.remove('hidden');
            };

            closeAddWorkOrderModalButton.onclick = function(e) {
                e.preventDefault();
                addWorkOrderModal.classList.add('hidden');
            };

            addWorkOrderModal.addEventListener('click', function(event) {
                if (event.target === addWorkOrderModal) {
                    addWorkOrderModal.classList.add('hidden');
                }
            });
        } else {
            console.error('Could not find one or more elements for the Add Work Order modal.');
        }

        // Edit Work Order Modal Logic
        const editWorkOrderModal = document.getElementById('editWorkOrderModal');
        const closeEditWorkOrderModalButton = document.getElementById('closeEditWorkOrderModal');
        const editWorkOrderForm = document.getElementById('editWorkOrderForm');

        if (editWorkOrderModal && closeEditWorkOrderModalButton && editWorkOrderForm) {
            document.querySelectorAll('.edit-work-order').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const product_name = this.dataset.product;
                    const quantity = this.dataset.quantity;
                    const status = this.dataset.status;
                    const start_date = this.dataset.startDate;
                    const due_date = this.dataset.dueDate;

                    document.getElementById('edit_order_id').value = id;
                    document.getElementById('edit_product_name').value = product_name;
                    document.getElementById('edit_quantity').value = quantity;
                    document.getElementById('edit_status').value = status;
                    document.getElementById('edit_start_date').value = start_date;
                    document.getElementById('edit_due_date').value = due_date;

                    editWorkOrderForm.action = `/manufacturing/${id}`;
                    editWorkOrderModal.classList.remove('hidden');
                });
            });

            closeEditWorkOrderModalButton.onclick = function(e) {
                e.preventDefault();
                editWorkOrderModal.classList.add('hidden');
            };

            editWorkOrderModal.addEventListener('click', function(event) {
                if (event.target === editWorkOrderModal) {
                    editWorkOrderModal.classList.add('hidden');
                }
            });
        } else {
            console.error('Could not find one or more elements for the Edit Work Order modal.');
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