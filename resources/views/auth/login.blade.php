@section('title', 'Login')
<x-guest-layout>
    <nav class="login-navbar">
        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
            <span class="navbar-brand">Autocahin Nexus</span>
        </div>
        <div class="navbar-links">
            <a href="/">Home</a>
            <a href="/register">Register</a>
        </div>
    </nav>
    <div class="login-split-container">
        
        <!-- Left: Illustration and logo-->
        <div class="login-split-left">
            <div class="login-illustration-wrapper">
                <div class="login-illustration-text">Welcome Back!</div>
                <div class="login-split-illustration">
                    <img src="{{ asset('images/login.png') }}" alt="Login Illustration" style="max-width: 420px; width: 100%; height: auto; display: block; margin: 2rem auto 0 auto;">
                </div>
            </div>
        </div>

        <!-- Right: Login form -->
        <div class="login-split-right">
            <div class="login-split-right-inner">
            <div class="login-form-box">
                    <div class="login-title">Sign into your Account</div>
                    <div class="login-desc">To access your tailored Dashboard just for you</div>
                @if(session('status'))
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
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                            <label for="email">Email address <span class="required-asterisk">*</span></label>
                        <input id="email" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                    </div>
                        <div class="input-group" style="position: relative;">
                            <label for="password">Password <span class="required-asterisk">*</span></label>
                            <input id="password" type="password" name="password" placeholder="Enter password" required>
                            <button type="button" class="toggle-password" aria-label="Show/Hide password" tabindex="-1">
                                <span id="eye">
                                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
                                        <path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                                    </svg>
                                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-width="2" d="M17.94 17.94A10.97 10.97 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 014.22-5.94M1 1l22 22"/>
                                        <path stroke-width="2" d="M9.53 9.53A3 3 0 0112 9c1.66 0 3 1.34 3 3 0 .47-.11.91-.29 1.29"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 1.2rem;">
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn-login">Login</button>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var pwd = document.getElementById('password');
                            var toggleBtn = document.querySelector('.toggle-password');
                            var eyeOpen = document.getElementById('eye-open');
                            var eyeClosed = document.getElementById('eye-closed');
                            // Always start with password hidden and closed eye visible
                            pwd.type = 'password';
                            eyeOpen.style.display = 'none';
                            eyeClosed.style.display = 'inline';
                            if (toggleBtn) {
                                toggleBtn.addEventListener('click', togglePassword, false);
                                toggleBtn.addEventListener('touchstart', function(e) { e.preventDefault(); togglePassword(); }, false);
                            }
                            function togglePassword() {
                                if (pwd.type === 'password') {
                                    pwd.type = 'text';
                                    eyeOpen.style.display = 'inline';
                                    eyeClosed.style.display = 'none';
                                } else {
                                    pwd.type = 'password';
                                    eyeOpen.style.display = 'none';
                                    eyeClosed.style.display = 'inline';
                                }
                            }
                        });
                    </script>
                    <div class="login-register-row">
                        <span>Don't have an account?</span>
                        <a href="/register">Register</a>
                    </div>
                    <div class="login-dashboard-row">
                    <a href="/admin/login" class="btn-dashboard btn-admin"><i class="fas fa-shield-alt"></i> Admin Dashboard</a>
                    <a href="{{ route('customer.dashboard') }}" class="btn-dashboard btn-customer"><i class="fas fa-user"></i> Customer Dashboard</a>
                    </div>
                </div>
                <br><br><br><br>
            </div>
            <div class="login-copyright">©2024 all rights reserved</div>
        </div>
    </div>
</x-guest-layout>
<footer class="login-footer">
    ©2024 Autocahin Nexus. All rights reserved.
</footer>
