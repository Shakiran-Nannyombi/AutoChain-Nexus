<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Inventory Management System</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        
        <h1 class="login-title">Admin Login</h1>
        <p class="login-desc">Access the admin control panel</p>
        
        <form method="POST" action="/admin/login" class="login-form">
            @csrf
            
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login as Admin
            </button>
        </form>
        
        <div class="login-footer">
            <a href="/login" style="color: var(--blue); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to User Login
            </a>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const passwordInput = document.getElementById('password');
        const togglePassword = document.createElement('button');
        togglePassword.type = 'button';
        togglePassword.className = 'toggle-password';
        togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
        
        passwordInput.parentNode.style.position = 'relative';
        passwordInput.parentNode.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>
</html> 