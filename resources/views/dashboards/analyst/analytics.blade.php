@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')

@section('sidebar-content')
    @include('dashboards.analyst.sidebar')
@endsection

@section('content')
    <h1 class="page-header">Analytics</h1>
@endsection 