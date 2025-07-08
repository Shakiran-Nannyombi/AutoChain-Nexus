@extends('layouts.dashboard')

@section('title', 'Machine Health')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Machine Health</h1>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("Machine health information will be displayed here.") }}
        </div>
    </div>
@endsection
