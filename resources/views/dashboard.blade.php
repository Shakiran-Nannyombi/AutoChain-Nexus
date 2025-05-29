<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(auth()->user()->isAdmin())
                        <user-approvals></user-approvals>
                    @else
                        <div id="app">
                            {{ __("You're logged in!") }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>
