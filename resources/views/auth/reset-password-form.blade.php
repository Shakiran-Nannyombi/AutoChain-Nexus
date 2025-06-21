<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root { --deep-purple: #20003a; --maroon: #8c3239; --orange: #f4843b; --light-cyan: #d8eceb; --white: #fff; }
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
            max-width: 400px; 
            width: 100%;
            margin: 4rem auto; 
            background: var(--white); 
            border-radius: 16px; 
            box-shadow: 0 4px 24px rgba(32,0,58,0.08); 
            padding: 2.5rem 2rem 2rem 2rem; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        .register-logo-img {
            width: 90px;
            height: 90px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .title { color: var(--deep-purple); font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; }
        .desc { color: var(--maroon); text-align: center; margin-bottom: 1.5rem; }
        label { color: var(--deep-purple); font-weight: 500; margin-bottom: 0.3rem; display: block; width: 100%; text-align: left; }
        input[type="email"], input[type="password"] { width: 100%; padding: 0.6rem 1rem; border: 1.5px solid #e0e0e0; border-radius: 7px; background: #f8fafc; font-size: 1rem; margin-bottom: 1.2rem; }
        input:focus { border-color: var(--deep-purple); outline: none; }
        .btn { width: 100%; background: linear-gradient(90deg, var(--deep-purple), var(--orange)); color: var(--white); border: none; border-radius: 7px; padding: 0.7rem 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; margin-top: 0.5rem; }
        .btn:hover { background: linear-gradient(90deg, var(--orange), var(--deep-purple)); }
        .error { background: #ffeaea; color: var(--maroon); border-radius: 7px; padding: 0.7rem 1rem; margin-bottom: 1rem; text-align: center; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <div><br></div>
        <div class="title">Set New Password</div>
        <div class="desc">Enter your new password below</div>
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" required>
            <label for="password">New Password</label>
            <input id="password" type="password" name="password" required>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>