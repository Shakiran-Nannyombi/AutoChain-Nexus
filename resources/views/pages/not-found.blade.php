@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- 404 Icon -->
        <div class="flex justify-center">
            <svg class="h-24 w-24 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Error Message -->
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                404 - Page Not Found
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Sorry, we couldn't find the page you're looking for.
            </p>
        </div>

        <!-- Navigation Options -->
        <div class="mt-8 space-y-4">
            <a href="{{ route('dashboard') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Go to Dashboard
            </a>
            <a href="{{ url()->previous() }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Go Back
            </a>
        </div>

        <!-- Help Text -->
        <div class="mt-6">
            <p class="text-sm text-gray-500">
                Need help? Contact our support team at
                <a href="mailto:support@example.com" class="font-medium text-primary hover:text-primary-dark">
                    support@example.com
                </a>
            </p>
        </div>
    </div>
</div>
@endsection 