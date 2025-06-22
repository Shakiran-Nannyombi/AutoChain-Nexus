@extends('layouts.dashboard')

@section('title', 'Analytics')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Analytics</h1>
    <p>This is the Analytics page.</p>
@endsection
