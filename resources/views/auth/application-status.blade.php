@section('title', 'Application Status')
<x-guest-layout>
    <div class="status-container">
        <div class="status-card">
            <div class="register-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
            <div class="status-title">Application Status</div>
            
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

            <form method="GET" action="{{ route('application.status') }}" class="status-form">
                <div class="input-group">
                    <label for="email">Check another application</label>
                    <input type="email" id="email" name="email" placeholder="Enter email address" required value="{{ request('email') }}">
        </div>
                <button type="submit" class="btn-primary">Check Status</button>
            </form>
        </div>
    </div>
</x-guest-layout> 