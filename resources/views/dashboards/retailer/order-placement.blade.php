@extends('layouts.dashboard')
@section('title', 'Order Placement')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <h1 class="page-header">Order Placement</h1>
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-shopping-bag"></i> Place a Customer Order
        </h2>

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