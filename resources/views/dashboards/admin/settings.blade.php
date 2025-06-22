@extends('layouts.dashboard')

@section('title', 'Settings')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Settings</h1>
    <p>This is the Settings page.</p>
@endsection
