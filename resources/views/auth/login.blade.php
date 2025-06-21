<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/auth.css'])
</head>
<body>
    <a href="/" style="display:inline-block;margin:1rem 0 0 1rem;color:#6c63ff;text-decoration:none;font-weight:600;">&#8592; Back to Home</a>
    <div class="login-container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            <a href="{{ route('login') }}">Login</a>
        @endif
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="login-title">Welcome Back</div>
        <div class="login-desc">Sign in to your AUTOCHAIN NEXUS account</div>
        
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
            <div>
                <div class="input-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="">Select Role</option>
                        <option value="manufacturer">Manufacturer</option>
                        <option value="supplier">Supplier</option>
                        <option value="vendor">Vendor</option>
                        <option value="retailer">Retailer</option>
                        <option value="analyst">Analyst</option>
                    </select>
                </div>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1" aria-label="Show/Hide password">
                    <span id="eye" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                        <!-- Eye SVG (visible by default) -->
                        <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2"/>
                        </svg>
                        <!-- Eye-off SVG (hidden by default) -->
                        <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
                            <path stroke-width="2" d="M17.94 17.94A10.97 10.97 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 014.22-5.94M1 1l22 22"/>
                            <path stroke-width="2" d="M9.53 9.53A3 3 0 0112 9c1.66 0 3 1.34 3 3 0 .47-.11.91-.29 1.29"/>
                        </svg>
                    </span>
                </button>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            <button type="submit" class="btn-login">Sign In</button>
        </form>
        <div class="login-footer">
            Don't have an account? <a href="/register">Apply for registration</a>
            <br>
            Want to check your application? <a href="#" onclick="checkApplicationStatus()">View application status</a>
            <br>
            <a href="/admin/login" style="color: var(--maroon); text-decoration: none; margin-top: 0.5rem; display: inline-block;">
                <i class="fas fa-shield-alt"></i> Admin Login
            </a>
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
</body>
</html>
