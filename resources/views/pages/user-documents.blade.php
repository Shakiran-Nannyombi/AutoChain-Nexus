@extends('layouts.app')

@section('headerTitle', $headerTitle)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Documents for {{ $user->name }}</h2>

        <!-- File Scanning Section -->
        @verbatim
        <div class="bg-blue-50 p-4 rounded-lg shadow-sm mb-6" x-data="{ scanning: false, scanResult: null }">
            <h3 class="text-lg font-semibold mb-3">Document Scanner</h3>
            <p class="text-sm text-gray-700 mb-4">Perform a simulated scan for document integrity and malware.</p>
            <x-ui.button variant="primary" @click="scanning = true; scanResult = 'Scanning...'; setTimeout(() => { scanResult = 'Scan Complete: No issues found.'; scanning = false; }, 2000)" :disabled="scanning">
                <span x-show="!scanning">Scan Documents</span>
                <span x-show="scanning">Scanning...</span>
            </x-ui.button>
            <div x-show="scanResult" class="mt-4 p-3 rounded-md" :class="scanResult.includes('No issues') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                <p class="text-sm font-medium" x-text="scanResult"></p>
            </div>
        </div>
        @endverbatim

        @if($user->documents->isEmpty())
            <p class="text-gray-600">No documents submitted by this user.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($user->documents as $document)
                    <x-ui.card class="p-4 flex flex-col items-start space-y-2">
                        <div class="flex items-center space-x-2">
                            @if(in_array($document->document_type, ['jpg', 'jpeg', 'png']))
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L20 16m-2-6a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            @elseif($document->document_type === 'pdf')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0015.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @endif
                            <p class="font-medium text-gray-800">{{ $document->original_filename }}</p>
                        </div>
                        <p class="text-sm text-gray-600">Type: {{ strtoupper($document->document_type) }}</p>
                        <p class="text-sm text-gray-600">Size: {{ round($document->file_size / 1024, 2) }} KB</p>
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mt-2">View Document</a>
                    </x-ui.card>
                @endforeach
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('admin') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Admin Dashboard
            </a>

            @if($user->status === 'pending')
            <div class="flex space-x-2">
                <form action="{{ route('admin.approveUser', $user->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <x-ui.button type="submit" variant="primary">Approve User</x-ui.button>
                </form>
                <form action="{{ route('admin.rejectUser', $user->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <x-ui.button type="submit" variant="destructive">Reject User</x-ui.button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 