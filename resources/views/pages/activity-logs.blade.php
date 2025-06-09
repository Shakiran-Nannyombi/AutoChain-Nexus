@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Activity Log</h1>

    @forelse($activities as $activity)
        <div class="bg-white shadow-md rounded-lg p-4 mb-4">
            <div class="flex items-center space-x-3 mb-2">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary">
                        <span class="text-sm font-medium leading-none text-white">
                            {{ substr($activity->user->name ?? 'System', 0, 2) }}
                        </span>
                    </span>
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $activity->description }}</p>
                    <p class="text-sm text-gray-500">{{ $activity->created_at->format('M d, Y H:i:s') }} ({{ $activity->created_at->diffForHumans() }})</p>
                </div>
            </div>
            @if ($activity->targetUser)
                <p class="text-sm text-gray-600">Target User: {{ $activity->targetUser->name }} ({{ $activity->targetUser->email }})</p>
            @endif
        </div>
    @empty
        <p class="text-gray-600">No activity logs found.</p>
    @endforelse

    <div class="mt-6">
        {{ $activities->links() }}
    </div>
</div>
@endsection 