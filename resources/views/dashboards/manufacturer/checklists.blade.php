@extends('layouts.dashboard')

@section('title', 'Checklists')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Checklists</h1>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("Checklists will be displayed here.") }}
        </div>
    </div>
@endsection
