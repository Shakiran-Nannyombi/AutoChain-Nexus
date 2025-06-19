@extends('layouts.app')

@section('headerTitle', 'User Profile')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left Column: User Info Card -->
        <div class="md:col-span-1 ">
            <x-ui.card class="p-6 text-center bg-white shadow-md">
                <div class="flex flex-col items-center mb-4">
                    <div class="flex-shrink-0 mb-4">
                        <span class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 text-blue-600 text-3xl font-semibold">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xl font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ ucfirst($user->role) }} Manager</p>
                    </div>
                </div>

                <div class="space-y-3 text-left text-gray-700">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9a2 2 0 114 0 2 2 0 01-4 0z"></path>
                        </svg>
                        <span>Operations</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $user->company_address ?? 'New York, NY' }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Joined {{ $user->created_at->format('F Y') }}</span>
                    </div>
                </div>

                <x-ui.button variant="outline" class="mt-6 w-full bg-blue-50 hover:bg-blue-100">Edit Profile</x-ui.button>
            </x-ui.card>
        </div>

        <!-- Right Column: Profile Settings Form -->
        <div class="md:col-span-2">
            <x-ui.card class="p-6 bg-gray-50 shadow-md">
                <x-ui.card-header>
                    <x-ui.card-title class="text-lg">Profile Settings</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-bold text-gray-700">Full Name</label>
                                <x-ui.input type="text" id="full_name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                                <x-ui.input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700">Phone</label>
                                <x-ui.input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-bold text-gray-700">Location</label>
                                <x-ui.input type="text" id="location" name="company_address" value="{{ old('company_address', $user->company_address ?? '') }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-bold text-gray-700">Role</label>
                                <x-ui.input type="text" id="role" name="role" value="{{ ucfirst($user->role) }} Manager" class="mt-1 block w-full" disabled />
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-bold text-gray-700">Department</label>
                                <x-ui.input type="text" id="department" name="department" value="Operations" class="mt-1 block w-full" disabled />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="bio" class="block text-sm font-bold text-gray-700">Bio</label>
                            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('bio', $user->bio ?? 'Experienced supply chain professional with 8+ years in automotive manufacturing. Passionate about optimizing processes and building efficient vendor relationships.') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>
                        <x-ui.button type="submit" variant="primary" class="mt-4">Save Changes</x-ui.button>
                    </form>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection 