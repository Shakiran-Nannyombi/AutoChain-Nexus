<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Password Reset Token</title>
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
            max-width: 400px; 
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
        .title {
            text-align: center;
            margin-bottom: 22px;
            color: var(--deep-purple);
            font-weight: 700;
            letter-spacing: 1px;
        }
        .input-group {
            margin-bottom: 18px;
            width: 100%;
        }
        .input-group label {
            display: block;
            margin-bottom: 7px;
            color: var(--maroon);
            font-weight: 600;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1.5px solid var(--deep-purple);
            background: var(--light-cyan);
            color: var(--deep-purple);
            font-size: 1rem;
            transition: border 0.2s;
        }
        .input-group input:focus {
            border: 1.5px solid var(--orange);
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, var(--maroon), var(--orange));
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(140,50,57,0.08);
        }
        .btn:hover {
            background: linear-gradient(90deg, var(--orange), var(--maroon));
        }
        .status {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid var(--maroon);
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Autochain Nexus Logo" class="register-logo-img">
        </div>
        
        <h2 class="title">Enter Reset Token</h2>

        <p class="desc">Please enter the password reset token sent to your email.</p>
        @if(session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        
        @if(session('error'))
            <div class="status">{{ session('error') }}</div>
        @endif

        <form onsubmit="goToResetForm(event);">
            <div class="input-group">
                <label for="token">Token</label>
                <input type="text" id="token" name="token" required>
            </div>
            <button type="submit" class="btn">Continue</button>
        </form>
    </div>

    <script>
        function goToResetForm(event) {
            event.preventDefault();
            const token = document.getElementById('token').value;
            if (token) {
                const url = "{{ route('password.reset', ['token' => 'REPLACE_ME']) }}".replace('REPLACE_ME', token);
                window.location.href = url;
            }
        }
    </script>
</body>
</html>