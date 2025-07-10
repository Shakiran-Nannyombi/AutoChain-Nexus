@section('title', 'Application Status')
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
                <div class="login-illustration-text">Check your application status</div>
                <div class="login-split-illustration">
                    <img src="{{ asset('images/status.png') }}" alt="Application Status Illustration" style="max-width: 420px; width: 100%; height: auto; display: block; margin: 2rem auto 0 auto;">
                </div>
            </div>
        </div>
        <!-- Right: Status card and form -->
        <div class="login-split-right">
            <div class="login-split-right-inner">
                <div class="login-form-box">
                    <div class="login-title">Application Status</div>
                    @if(isset($user))
                        <div class="status-info">
                            <p><strong>Company:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p>
                                <strong>Status:</strong>
                                <span class="status-badge status-{{ $user->status ?? 'pending' }}">
                                    {{ ucfirst($user->status ?? 'Pending') }}
                                </span>
                            </p>
                            <p class="status-message">
                                @if($user->status == 'approved')
                                    Your application has been approved! You can now <a href="{{ route('login') }}">login</a>.
                                @elseif($user->status == 'rejected')
                                    We regret to inform you that your application has been rejected. Please contact support for more information.
                                @else
                                    Your application is currently under review. We will notify you once a decision has been made.
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="status-info">
                            <p class="status-message">{{ $message ?? 'Enter your email to check your application status.' }}</p>
                        </div>
                    @endif
                    <form method="GET" action="/application-status" class="status-form">
                        <div class="input-group">
                            <label for="email">Check another application <span class="required-asterisk">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Enter email address" required value="{{ request('email') }}">
                        </div>
                        <button type="submit" class="btn-login">Check Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<footer class="login-footer">
    ©2024 Autochain Nexus. All rights reserved.
</footer> 