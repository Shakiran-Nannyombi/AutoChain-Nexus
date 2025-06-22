<!DOCTYPE html>
<html>
<head>
    <title>Application Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Hello, {{ $user->name }}</h2>

    <p>Thank you for your interest in Autochain Nexus and for taking the time to submit your application for the role of <strong>{{ ucfirst($user->role) }}</strong>.</p>
    
    <p>After careful consideration, we regret to inform you that we will not be proceeding with your application at this time. This was a difficult decision due to the high volume of qualified applicants.</p>

    <p>We wish you the best in your future endeavors.</p>

    <p>Best regards,<br>The Autochain Nexus Team</p>

</body>
</html> 