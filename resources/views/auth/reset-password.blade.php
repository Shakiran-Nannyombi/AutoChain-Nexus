<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Autochain Nexus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        .container {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            min-height: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(32,0,58,0.12); /* replaced var(--shadow) with actual value */
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        .register-logo-img {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .title {
            color: var(--deep-purple);
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .desc {
            color: var(--maroon);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            color: var(--deep-purple);
            font-weight: 500;
            margin-bottom: 0.3rem;
            display: block;
        }
        input[type="email"] {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            background: #f8fafc;
            font-size: 1rem;
            margin-bottom: 1.2rem;
        }
        input[type="email"]:focus {
            border-color: var(--deep-purple);
            outline: none;
        }
        .btn {
            width: 100%;
            background: linear-gradient(90deg, var(--deep-purple), var(--orange));
            color: var(--white);
            border: none;
            border-radius: 7px;
            padding: 0.7rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        .btn:hover {
            background: linear-gradient(90deg, var(--orange), var(--deep-purple));
        }
        .status {
            background: #e6f7ee;
            color: #2e8b57;
            border-radius: 7px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        <br><br>
        <div class="title">Reset Password</div>
        <div class="desc">Enter your email to receive a reset token</div>
        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" required>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
    </div>
</body>
</html>