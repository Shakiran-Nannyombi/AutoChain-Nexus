@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-black">Chat with {{ $chat->sender_id === auth()->id() ? $chat->receiver->name : $chat->sender->name }}</h1>
        <a href="{{ route('chats.index') }}" class="bg-blue-900 hover:bg-blue-950 text-black font-bold py-2 px-4 rounded-full inline-flex items-center transition duration-300 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Chats
        </a>
    </div>
    
    <div class="bg-gray-800 shadow-lg rounded-lg p-6">
        <div class="space-y-4">
            @foreach($chat->messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-900 text-white' : 'bg-gray-700 text-white' }}">
                        <p class="text-sm">{{ $message->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('M d, Y H:i') }}
                            @if($message->sender_id === auth()->id())
                                <a href="{{ route('chats.editMessage', $message) }}" class="text-blue-300 hover:text-blue-500 text-xs ml-2">Edit</a>
                                <form action="{{ route('chats.destroyMessage', $message) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-300 hover:text-red-500 text-xs ml-2">Delete</button>
                                </form>
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <form action="{{ route('chats.storeMessage', $chat) }}" method="POST" class="mt-6">
            @csrf
            <div class="flex items-center">
                <textarea name="message" rows="1" class="w-full rounded-md border-gray-600 bg-gray-700 text-black shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your message..." required style="color: black !important;"></textarea>
                <button type="submit" class="ml-2 bg-blue-900 hover:bg-blue-950 text-white font-bold py-2 px-4 rounded-full inline-flex items-center transition duration-300 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 