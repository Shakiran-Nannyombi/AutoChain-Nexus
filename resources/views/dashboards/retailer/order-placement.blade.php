@extends('layouts.dashboard')
@section('title', 'Order Placement')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <h1 class="page-header">Order Placement</h1>
    <div class="content-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="color: var(--deep-purple); font-size: 1.8rem; margin: 0;">
                <i class="fas fa-shopping-bag"></i> Place a Customer Order
            </h2>
            <a href="{{ route('retailer.orders') }}" class="button" style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-list"></i> View My Orders
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('retailer.orders.submit') }}" method="POST" style="margin-bottom: 2rem;">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="customer_name" style="display: block; margin-bottom: 0.5rem;">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="car_model" style="display: block; margin-bottom: 0.5rem;">Car Model</label>
                <input type="text" name="car_model" id="car_model" class="form-control" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="quantity" style="display: block; margin-bottom: 0.5rem;">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
            </div>

            <button type="submit" class="btn btn-primary">Submit Order</button>
        </form>
    </div>
@endsection 