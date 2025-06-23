@section('title', 'Reset Password Form')
<x-guest-layout>
    <div class="login-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="login-title">Set New Password</div>
        <div class="login-desc">Create a new password for your account.</div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="login-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="input-group">
            <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', request()->email) }}" required autofocus>
            </div>
            
            <div class="input-group">
            <label for="password">New Password</label>
            <input id="password" type="password" name="password" required>
            </div>
            
            <div class="input-group">
                <label for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn-login">Reset Password</button>
        </form>
    </div>
</x-guest-layout>