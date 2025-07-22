@section('title', 'Forgot Password')
<x-guest-layout>
    <nav class="login-navbar" style="display: flex; align-items: center; justify-content: space-between;">
        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
            <span class="navbar-brand">Autochain Nexus</span>
        </div>
        <button class="navbar-hamburger" aria-label="Open menu" onclick="document.querySelector('.navbar-links').classList.toggle('open')">
            &#9776;
        </button>
        <div class="navbar-links">
            <a href="/">Home</a>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </div>
        <button id="darkModeToggleAuth" aria-label="Toggle dark mode" style="margin-left: 1.2rem; background: var(--background); color: var(--primary); border: 1px solid var(--primary); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: var(--shadow); cursor: pointer; transition: background 0.2s, color 0.2s; padding: 0;">
            <span id="darkModeIconAuth">🌙</span>
        </button>
    </nav>
    <div class="login-split-container">
        <!-- Left: Illustration -->
        <div class="login-split-left">
            <div class="login-illustration-wrapper">
                <div class="login-illustration-text" style="color: var(--text);">Forgot your password?</div>
                <div class="login-split-illustration">
                    <img src="{{ asset('images/forgotpassword.png') }}" alt="Forgot Password Illustration" style="max-width: 420px; width: 100%; height: auto; display: block; margin: 2rem auto 0 auto;">
                </div>
            </div>
        </div>
        <!-- Right: Form -->
        <div class="login-split-right">
            <div class="login-split-right-inner">
                <div class="login-form-box">
                    <div class="login-title">Receive reset Token</div>
                    <div class="login-desc">Enter your email to receive a password reset link.</div>
                    @if (session('status'))
                        <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
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
            <div class="input-group" style="color: var(--text);">
                            <label for="email">Email Address <span class="required-asterisk">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
            <button type="submit" class="btn-login">Send Reset Link</button>
    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<footer class="login-footer">
    ©2024 Autochain Nexus. All rights reserved.
</footer>