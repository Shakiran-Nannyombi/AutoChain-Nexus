<x-guest-layout>
    <div class="login-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="login-title">Reset Password</div>
        <div class="login-desc">Enter your email to receive a password reset link.</div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
            <button type="submit" class="btn-login">Send Reset Link</button>
    </form>
    </div>
</x-guest-layout>