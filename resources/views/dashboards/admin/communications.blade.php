@extends('layouts.dashboard')

@section('title', 'Communications')
@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<h1 class='page-title' style='margin-bottom: 1.5rem;'>Communications</h1>
    <p>This is the Communications page.</p>
@endsection
