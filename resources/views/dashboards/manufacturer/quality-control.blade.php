@extends('layouts.dashboard')

@section('title', 'Manufacturer Dashboard')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <div class="content-card">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 1.5rem;"><i class="fas fa-shield-alt"></i> Quality Control</h2>
        <div style="text-align:center; color: var(--primary); font-size: 1.1rem; padding: 2rem 0;">
            Quality control information will be displayed here.
        </div>
    </div>
@endsection
