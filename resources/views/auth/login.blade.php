<x-guest-layout>
    <!-- Content within the white card container provided by guest layout -->

    <div class="flex flex-col items-center text-center mb-2">
        <!-- Logo is provided by the guest layout -->
        <h2 class="text-2xl font-bold text-[#171d3f] mt-4">Welcome Back</h2>
        <p class="text-gray-600 text-sm">Sign in to your AUTOCHAIN NEXUS account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="w-full">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-black text-sm font-medium mb-1">Email Address</label>
            <x-ui.input id="email" class="block mt-1 w-full text-sm !bg-[#38b5ea] border !border-white placeholder-white-400 rounded-md shadow-sm focus:ring focus:ring-white focus:ring-opacity-50 input-text-color" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-2">
            <label for="password" class="block text-black text-sm font-medium mb-1">Password</label>
            <div class="relative">
            <x-ui.input id="password" class="block mt-1 w-full text-sm !bg-[#38b5ea] border !border-white placeholder-white-400 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity50 pr-10 input-text-color" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Forgot Password -->
        <div class="text-right mb-6">
            @if (Route::has('password.request'))
                <a class="text-xs text-blue-600 hover:text-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Sign In Button -->
        <div>
             <button type="submit" class="w-full bg-[#171d3f] hover:bg-[#2c8ac9] text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline text-sm">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <!-- Registration Link -->
    <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Apply for registration</a>
    </div>

</x-guest-layout>
