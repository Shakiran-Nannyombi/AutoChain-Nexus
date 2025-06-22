<x-guest-layout>
    <div class="login-container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div class="login-title">Admin Access</div>
        <div class="login-desc">Enter your administrator credentials</div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
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
        
        <form class="login-form" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="input-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="Enter your admin email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>
        <div class="login-footer" style="margin-top: 1rem; text-align: center;">
            <a href="{{ route('login') }}" style="color: var(--blue); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to User Login
            </a>
        </div>
    </div>
</x-guest-layout>

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