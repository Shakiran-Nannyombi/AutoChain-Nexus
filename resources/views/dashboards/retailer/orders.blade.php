@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">My Orders</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Car Model</th>
                        <th>Quantity</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th>Ordered At</th>
                        <th>Estimated Delivery</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retailerOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->car_model }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'confirmed') bg-primary
                                    @elseif($order->status == 'shipped') bg-info
                                    @elseif($order->status == 'delivered') bg-success
                                    @elseif($order->status == 'rejected') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                {{ $order->estimated_delivery 
                                    ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') 
                                    : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('retailer.orders', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                                @if($order->status === 'rejected')
                                    <button class="btn btn-sm btn-outline-danger" disabled>Rejected</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">You have not placed any orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
