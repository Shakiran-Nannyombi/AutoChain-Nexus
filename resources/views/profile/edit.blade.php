<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('profile.partials.update-profile-information-form')

                        <div class="mb-4">
                            <label for="profile_photo" class="block font-medium text-sm text-gray-700">Profile Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="mt-1 block w-full">
                            @if(auth()->user()->profile_photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" width="100" class="rounded-full border">
                                </div>
                            @endif
                            @error('profile_photo')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @include('profile.partials.update-password-form')
                        @include('profile.partials.delete-user-form')

                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                {{ __('Update Profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
