<x-guest-layout>
    <div class="mb-4 text-sm font-semibold text-[#171d3f]">
        {{ __('Forgot your password? Enter your email address below and we will send you a link to reset your password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
             <label for="email" class="block text-[#171d3f] text-sm font-medium mb-1">Email</label>
            <x-text-input id="email" class="block mt-1 w-full  text-sm !bg-[#38b5ea] border !border-white placeholder-white-400 input-text-color" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 ">
            <button type="submit" class="bg-[#171d3f] hover:bg-[#2c8ac9] text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
