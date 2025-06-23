@extends('layouts.dashboard')

@section('title', 'Production Analytics')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection
@section('content')
    <h1 class="page-header-manufacturer">Production Analytics</h1>
@endsection 