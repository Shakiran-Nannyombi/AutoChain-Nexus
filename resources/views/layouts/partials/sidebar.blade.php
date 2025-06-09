<div class="w-64 bg-slate-900 text-white flex flex-col">
    <div class="p-6">
        <h1 class="text-xl font-bold text-blue-400">AUTOCHAIN NEXUS</h1>
        <p class="text-sm text-gray-400 mt-1">Car Manufacturing</p>
    </div>
    
    @auth
        <div class="px-6 py-4 border-b border-slate-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    @endauth
    
    <nav class="flex-1 px-4 py-6">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('inventory.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('inventory.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Inventory</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('supply-chain') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('supply-chain') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Supply Chain</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('manufacturing') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('manufacturing') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span>Manufacturing</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('retail') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('retail') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Retail</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('vendors') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('vendors') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Vendors</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('communications') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('communications') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>Communications</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('analytics') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('analytics') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Analytics</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('reports') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('reports') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Reports</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('settings') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('settings') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</div> 