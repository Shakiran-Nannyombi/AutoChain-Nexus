@extends('layouts.dashboard')

@section('title', 'Customer Orders')

@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Customer Orders</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if($orders->count())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_date->format('Y-m-d H:i') }}</td>
                <td>
                    {{ $order->customer_name }}<br>
                    <small>{{ $order->customer_email }}</small>
                </td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    @if($order->status === 'pending')
                    <form action="{{ route('retailer.customer-orders.receive', $order->id) }}"
                          method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-sm btn-primary">Mark Received</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
    @else
    <div class="alert alert-info">
        You have no customer orders yet.
    </div>
    @endif
@endsection
