@extends('layouts.dashboard')

@section('title', 'Chat')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Chat</h1>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("Chat functionality will be implemented here.") }}
        </div>
    </div>
@endsection
