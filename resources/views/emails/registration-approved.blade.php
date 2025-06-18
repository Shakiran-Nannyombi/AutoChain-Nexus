<!DOCTYPE html>
<html>
<head>
    <title>Registration Approved</title>
</head>
<body>
    <h1>Welcome to Our Platform!</h1>
    
    <p>Dear {{ $user->name }},</p>
    
    <p>We are pleased to inform you that your registration has been approved. You can now log in to your account and start using our platform.</p>
    
    <p>Your account details:</p>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Role: {{ ucfirst($user->role) }}</li>
    </ul>
    
    <p>Please click the link below to log in:</p>
    <a href="{{ url('/login') }}">Login to Your Account</a>
    
    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
    
    <p>Best regards,<br>The Team</p>
</body>
</html> 