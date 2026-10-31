<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Two-Factor Authentication</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .verify-card {
            width: 100%;
            max-width: 420px;
            padding: 32px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h2 {
            color: #1e293b;
        }

        p {
            color: #64748b;
            line-height: 1.6;
        }

        input {
            width: 100%;
            padding: 14px;
            margin: 16px 0;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 20px;
            text-align: center;
            letter-spacing: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            color: #dc2626;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <div class="verify-card">

        <h2>Two-Factor Verification</h2>

        <p>
            Open Google Authenticator and enter the six-digit verification code.
        </p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first('code') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.2fa.verify') }}">
            @csrf

            <input
                type="text"
                name="code"
                placeholder="000000"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                autofocus
            >

            <button type="submit">Verify & Continue</button>
        </form>

    </div>

</body>
</html>