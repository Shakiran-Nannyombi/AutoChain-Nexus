@section('title', 'Login')
<x-guest-layout>
    <div class="login-split-container">
        <!-- Left: Logo, welcome, and form -->
        <div class="login-split-left login-split-left-white">
            <div class="login-form-box">
                <div class="login-title" style="margin-bottom: 0.3rem;"><h2>Welcome back!</h2></div>
                <div class="login-desc" style="margin-top: 0;"><p>Sign in to get access to your account.</p></div>
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                    <a href="{{ route('login') }}">Login</a>
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
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input id="password" type="password" name="password" placeholder="Enter your password" required style="width: 100%; padding-right: 42px;">
                            <button type="button" class="toggle-password"
                                onclick="togglePassword()" tabindex="-1" aria-label="Show/Hide password"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; height: 32px; width: 32px; display: flex; align-items: center; justify-content: center;">
                                <span id="eye" style="display: flex; align-items: center; justify-content: center;">
                                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                                    </svg>
                                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
                                        <path stroke-width="2" d="M17.94 17.94A10.97 10.97 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 014.22-5.94M1 1l22 22"/>
                                        <path stroke-width="2" d="M9.53 9.53A3 3 0 0112 9c1.66 0 3 1.34 3 3 0 .47-.11.91-.29 1.29"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot your password?</a>
                    <button type="submit" class="btn-login">Log In</button>
                </form>
                <div class="login-footer">
                    <a href="/admin/login" class="btn-dashboard btn-admin"><i class="fas fa-shield-alt"></i> Admin Dashboard</a>
                    <a href="{{ route('customer.dashboard') }}" class="btn-dashboard btn-customer"><i class="fas fa-user"></i> Customer Dashboard</a>
                    <div style="margin-top: 1.5rem;">
                        <p style="color: black;">Don't have an account?</p> <a href="/register">Register here</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right: Abstract background -->
        <div class="login-split-right login-split-right-abstract">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </div>
    </div>
    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'inline';
            } else {
                pwd.type = 'password';
                eyeOpen.style.display = 'inline';
                eyeClosed.style.display = 'none';
            }
        }
        function checkApplicationStatus() {
            const email = document.getElementById('email').value;
            if (email) {
                window.location.href = `/application-status?email=${encodeURIComponent(email)}`;
            } else {
                alert('Please enter your email address first to check your application status.');
            }
        }
    </script>
</x-guest-layout>
