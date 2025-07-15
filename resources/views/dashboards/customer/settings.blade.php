@extends('layouts.dashboard')

@section('title', 'Customer Settings')

@section('sidebar-content')
    @include('dashboards.customer.sidebar')
@endsection

@section('content')
<div class="content-card">
    <h2 style="color: var(--deep-purple); margin-bottom: 1rem;">
        <i class="fas fa-cog"></i> Customer Settings
    </h2>
    <p>This is a simple customer settings page. You can add your settings options here.</p>
</div>
@endsection