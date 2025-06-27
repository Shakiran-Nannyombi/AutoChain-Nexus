@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">New Chat</h1>
        <a href="{{ route('chats.index') }}" class="bg-blue-900 hover:bg-blue-950 text-black font-bold py-2 px-4 rounded-full inline-flex items-center transition duration-300 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Chats
        </a>
    </div>
    
    <div class="bg-gray-800 shadow-lg rounded-lg p-6">
        <form action="{{ route('chats.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="receiver_id" class="block text-sm font-medium text-white">Select User to Chat With</label>
                <select name="receiver_id" id="receiver_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-black shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Select a user...</option>
                    @foreach($validContacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-white">Message</label>
                <textarea name="message" id="message" rows="3" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-black shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your message..." required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white font-bold py-2 px-4 rounded-full inline-flex items-center transition duration-300 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 