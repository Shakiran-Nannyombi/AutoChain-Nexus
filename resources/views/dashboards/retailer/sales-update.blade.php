@extends('layouts.dashboard')
@section('title', 'Sales Update')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <h1 class="page-header">Sales Update</h1>
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-cash-register"></i> Record a Sale
        </h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('retailer.sales-update.submit') }}" method="POST" style="margin-bottom: 2rem;">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="car_model" style="display: block; margin-bottom: 0.5rem;">Car Model</label>
                <select name="car_model" id="car_model" class="form-control" required>
                    @foreach($stock as $model => $items)
                        <option value="{{ $model }}">{{ $model }} ({{ $items->sum('quantity_received') }} available)</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="quantity" style="display: block; margin-bottom: 0.5rem;">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
            </div>

            <button type="submit" class="btn btn-primary">Submit Sale</button>
        </form>
    </div>
@endsection 