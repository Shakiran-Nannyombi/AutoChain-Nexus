@extends('layouts.dashboard')

@section('title', 'Products')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-box"></i> Products</h2>
        <div style="text-align:center; color: var(--primary); font-size: 1.1rem; padding: 2rem 0;">
            This is the Products page. Add your product management features here.
        </div>
    </div>
@endsection
