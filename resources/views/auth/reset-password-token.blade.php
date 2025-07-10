@section('title', 'Enter Token')
<x-guest-layout>
    <nav class="login-navbar">
        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
            <span class="navbar-brand">Autochain Nexus</span>
        </div>
        <div class="navbar-links">
            <a href="/">Home</a>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </div>
    </nav>
    <div class="login-split-container">
        <!-- Left: Illustration -->
        <div class="login-split-left">
            <div class="login-illustration-wrapper">
                <div class="login-illustration-text">Verify your token</div>
                <div class="login-split-illustration">
                    <img src="{{ asset('images/verifytoken.png') }}" alt="Verify Token Illustration" style="max-width: 420px; width: 100%; height: auto; display: block; margin: 2rem auto 0 auto;">
                </div>
            </div>
        </div>
        <!-- Right: Form -->
        <div class="login-split-right">
            <div class="login-split-right-inner">
                <div class="login-form-box">
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
                            <label for="email">Email Address <span class="required-asterisk">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="input-group">
                            <label for="token">Reset Token <span class="required-asterisk">*</span></label>
                            <input id="token" type="text" name="token" required>
                        </div>
                        <button type="submit" class="btn-login">Verify Token</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<footer class="login-footer">
    ©2024 Autochain Nexus. All rights reserved.
</footer>