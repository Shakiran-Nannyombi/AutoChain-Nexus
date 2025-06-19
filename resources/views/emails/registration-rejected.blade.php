<!DOCTYPE html>
<html>
<head>
    <title>Registration Rejected</title>
</head>
<body>
    <h1>Registration Status Update</h1>
    
    <p>Dear {{ $user->name }},</p>
    
    <p>We regret to inform you that your registration has been rejected. Here are the reasons for rejection:</p>
    
    <p>{{ $rejectionReason }}</p>
    
    <p>If you believe this is an error or would like to provide additional information, please contact our support team.</p>
    
    <p>You can also submit a new registration with updated information.</p>
    
    <p>Best regards,<br>The Team</p>
</body>
</html> 