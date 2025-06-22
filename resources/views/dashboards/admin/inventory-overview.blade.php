@extends('layouts.dashboard')

@section('title', 'Inventory Overview')

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
    <h1 class='page-title' style='margin-bottom: 1.5rem;'>Inventory Overview</h1>
    <div class="empty-state">
        <h3>Full Inventory View Coming Soon</h3>
        <p>This page will provide a comprehensive, read-only overview of all inventory across the platform.</p>
        <p>Once user-specific inventory management is implemented, this is where you will be able to monitor system-wide stock levels, track item movements, and identify trends.</p>
    </div>
@endsection 