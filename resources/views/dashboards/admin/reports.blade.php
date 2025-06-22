@extends('layouts.dashboard')

@section('title', 'Reports')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Reports</h1>
    <p>This is the Reports page.</p>
@endsection
