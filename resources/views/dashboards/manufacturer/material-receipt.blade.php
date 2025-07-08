@extends('layouts.dashboard')

@section('title', 'Material Receipt')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Material Receipt</h1>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("Material receipt information will be displayed here.") }}
        </div>
    </div>
@endsection
