@extends('layouts.dashboard')

@section('title', 'Database Backups')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('sidebar-content')
    @include('dashboards.admin.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-2">
    <div class="flex justify-between items-center mb-6">
        <h1 class="page-title">Database Backups</h1>
        <form action="{{ route('admin.backups.create') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary">Create New Backup</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="card-flow">
        <h2 class="card-title mb-4">Existing Backups</h2>
        <div class="backup-list">
            @if (count($backups) > 0)
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="p-3">Date</th>
                            <th class="p-3">File Path</th>
                            <th class="p-3">Size</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($backups as $backup)
                            <tr class="border-b">
                                <td class="p-3">{{ $backup['date'] }}</td>
                                <td class="p-3">{{ $backup['path'] }}</td>
                                <td class="p-3">{{ $backup['size'] }}</td>
                                <td class="p-3">
                                    <a href="#" class="btn-action-sm">Download</a>
                                    <a href="#" class="btn-action-sm btn-delete">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="p-4 text-center text-gray-500">No backups found.</p>
            @endif
        </div>
    </div>
</div>
@endsection 