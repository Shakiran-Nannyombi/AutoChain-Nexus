<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Application Status Update</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>

            @if($status === 'approved')
                <p>We are pleased to inform you that your application has been <strong>approved</strong>!</p>
                <p>You can now log in to your account and access your dashboard.</p>
                <p>
                    <a href="{{ route('login') }}" class="button">Login to Your Account</a>
                </p>
            @elseif($status === 'rejected')
                <p>We regret to inform you that your application has been <strong>rejected</strong>.</p>
                @if($rejectionReason)
                    <p><strong>Reason for rejection:</strong><br>
                    {{ $rejectionReason }}</p>
                @endif
                <p>If you believe this is an error or would like to provide additional information, please contact our support team.</p>
            @else
                <p>Your application status has been updated to: <strong>{{ ucfirst($status) }}</strong></p>
            @endif

            <p>Thank you for your interest in our platform.</p>
        </div>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html> 