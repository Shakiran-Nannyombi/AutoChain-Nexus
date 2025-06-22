<x-guest-layout>
    <div class="login-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="login-title">Enter Password Reset Token</div>
        <div class="login-desc">Please enter the token sent to your email.</div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.token.submit') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="input-group">
                <label for="token">Reset Token</label>
                <input id="token" type="text" name="token" required>
            </div>
            <button type="submit" class="btn-login">Verify Token</button>
        </form>
    </div>
</x-guest-layout>