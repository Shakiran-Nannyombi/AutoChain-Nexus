@extends('layouts.dashboard')

@section('title', 'Inventory Analysis')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Inventory Analysis</h1>
    <div class="content-card">
    <h2 class="page-header"><i class="fas fa-warehouse"></i> Inventory Analysis</h2>

    <h4 style="margin-top: 2rem; color: var(--deep-purple);">Supplier Raw Material Stock</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplierStocks as $stock)
                <tr>
                    <td>{{ $stock->material_name }}</td>
                    <td>{{ $stock->total_quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="margin-top: 3rem; color: var(--deep-purple);">Retailer Car Stock</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Car Model</th>
                <th>Total Received</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retailerStocks as $stock)
                <tr>
                    <td>{{ $stock->car_model }}</td>
                    <td>{{ $stock->total_received }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection