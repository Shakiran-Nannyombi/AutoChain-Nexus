<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Status | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/auth.css'])
    <style>
        :root { 
            --deep-purple: #20003a; 
            --maroon: #8c3239; 
            --orange: #f4843b; 
            --light-cyan: #d8eceb; 
            --white: #fff; 
        }
        body { 
            background: var(--deep-purple); 
            font-family: 'Segoe UI', Arial, sans-serif; 
            margin: 0; 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }
        .container {
            max-width: 500px; 
            width: 100%;
            margin: 4rem auto; 
            background: var(--white); 
            border-radius: 18px; 
            box-shadow: 0 6px 32px rgba(32,0,58,0.12); 
            padding: 2.5rem 2rem 2rem 2rem; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            border: 2px solid var(--maroon);
        }
        .logo {
            margin-bottom: 1.5rem;
        }
        .logo img {
            width: 80px;
            height: 80px;
        }
        .title {
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--deep-purple);
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .desc {
            text-align: center;
            color: var(--maroon);
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .status-card {
            width: 100%;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
        }
        .status-pending {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        .status-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .status-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .status-message {
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .actions {
            display: flex;
            gap: 1rem;
            width: 100%;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(90deg, var(--maroon), var(--orange));
            color: var(--white);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, var(--orange), var(--maroon));
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: var(--light-cyan);
            color: var(--deep-purple);
            border: 2px solid var(--deep-purple);
        }
        .btn-secondary:hover {
            background: var(--deep-purple);
            color: var(--white);
        }
        .refresh-info {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo">
        </div>
        
        <h1 class="title">Application Status</h1>
        <p class="desc">Check your account approval status</p>

        <div class="status-card status-{{ strtolower($user->status) }}">
            @if($user->status === 'pending')
                <div class="status-icon">⏳</div>
                <div class="status-text">Application Pending</div>
                <div class="status-message">
                    Your application is currently under review. Our admin team will review your information and approve your account soon. You'll be notified once your account is approved.
                </div>
            @elseif($user->status === 'approved')
                <div class="status-icon">✅</div>
                <div class="status-text">Application Approved</div>
                <div class="status-message">
                    Congratulations! Your account has been approved. You can now log in and access the dashboard.
                </div>
            @elseif($user->status === 'rejected')
                <div class="status-icon">❌</div>
                <div class="status-text">Application Rejected</div>
                <div class="status-message">
                    Unfortunately, your application has been rejected. Please contact support for more information.
                </div>
            @endif
        </div>

        <div class="actions">
            @if($user->status === 'approved')
                <a href="{{ route('login') }}" class="btn btn-primary">Login to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Go to Login</a>
            @endif
            <button onclick="location.reload()" class="btn btn-secondary">Refresh Status</button>
        </div>

        <div class="refresh-info">
            <p>Status last updated: {{ $user->updated_at->format('M d, Y g:i A') }}</p>
            <p>Click "Refresh Status" to check for updates</p>
        </div>
    </div>
</body>
</html> 