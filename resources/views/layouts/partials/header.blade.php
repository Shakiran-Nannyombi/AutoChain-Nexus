<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <h2 class="text-2xl font-semibold text-gray-900">Admin Dashboard</h2>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Search Form -->
            <form id="search-form" class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    id="search-input"
                    placeholder="Search..." 
                    class="pl-10 w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
            </form>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                >
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </button>
                
                <!-- Notifications Dropdown -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="font-semibold text-sm">Notifications</h3>
                    </div>
                    
                    @forelse($notifications as $notification)
                        <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer {{ $notification->unread ? 'bg-blue-50' : '' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm {{ $notification->unread ? 'font-medium' : '' }}">
                                        {{ $notification->message }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if($notification->unread)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-1"></div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-2 text-center text-gray-500 text-sm">
                            No notifications
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                >
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                
                <!-- User Dropdown -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50"
                >
                    @auth
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                                    @if(Auth::user()->role !== 'pending')
                                        <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endauth
                    
                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </div>
                    </a>
                    
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>
                    
                    <button 
                        id="logout-button"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                    >
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header> 