<!DOCTYPE html>
<html>
<head>
    <title>Application Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Congratulations, {{ $user->name }}!</h2>

    <p>We are pleased to inform you that your application for the role of <strong>{{ ucfirst($user->role) }}</strong> at Autochain Nexus has been approved.</p>
    
    <p>You can now log in to your account and access the dashboard.</p>

    <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 5px;">Login to Your Account</a>

    <p>Thank you for joining our platform.</p>

    <p>Best regards,<br>The Autochain Nexus Team</p>

</body>
</html> 