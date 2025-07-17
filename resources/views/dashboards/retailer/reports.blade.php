@extends('layouts.dashboard')
@section('title', 'Retailer Dashboard')
@section('sidebar-content')
    @include('dashboards.retailer.sidebar')
@endsection
@section('content')
    <div class="content-card">
        <h2 style="color: var(--deep-purple); margin-bottom: 1.5rem; font-size: 1.8rem;">
            <i class="fas fa-file-alt"></i> Retailer Reports
        </h2>
    </div>
@endsection
@section('scripts')
    <script>
        // Custom scripts for the reports page can be added here
    </script>
@endsection
