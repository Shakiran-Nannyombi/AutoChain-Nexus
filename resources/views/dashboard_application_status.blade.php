<x-guest-layout>
    <div class="flex flex-col items-center text-center mb-6">
        <h2 class="text-2xl font-bold text-[#171d3f] mt-0">Application Status</h2>
        <p class="text-gray-600 text-sm mt-2">Thank you for applying to AUTOCHAIN NEXUS</p>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-[#171d3f] mb-4">Application Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Role</p>
                    <p class="font-medium capitalize">{{ $user->role }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Company</p>
                    <p class="font-medium">{{ $user->company_name }}</p>
                </div>
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-[#171d3f] mb-4">Current Status</h3>
            @if($user->status === 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Your application is currently under review. We will notify you once it has been processed.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($user->status === 'approved')
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Congratulations! Your application has been approved. You can now log in to access your account.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="bg-[#171d3f] hover:bg-[#2c8ac9] text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline text-sm">Go to Login</a>
                </div>
            @elseif($user->status === 'rejected')
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                Your application has been rejected. 
                                @if($user->rejection_reason)
                                    Reason: {{ $user->rejection_reason }}
                                @else
                                    Please contact support for more information.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout> 