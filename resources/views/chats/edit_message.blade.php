@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Edit Message</h1>
    <form action="{{ route('chats.messages.update', $message) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="message" rows="3" class="w-full rounded-md border-gray-600 bg-gray-700 text-black shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('message', $message->message) }}</textarea>
        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white font-bold py-2 px-4 rounded-full">Update</button>
        </div>
    </form>
</div>
@endsection 