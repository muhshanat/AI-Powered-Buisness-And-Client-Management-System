<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Two-Factor Authentication</title>

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

        .setup-card {
            width: 100%;
            max-width: 480px;
            padding: 32px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #1e293b;
        }

        p {
            color: #64748b;
            line-height: 1.6;
        }

        .qr-code {
            margin: 24px 0;
            overflow-wrap: anywhere;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
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
            font-size: 14px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <div class="setup-card">
        <h2>Set Up Two-Factor Authentication</h2>

        <p>
            Open Google Authenticator on your phone and scan the QR code below.
        </p>

        <div class="qr-code">
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrCodeUrl) }}"
                alt="Google Authenticator QR Code"
                width="220"
                height="220"
            >
        </div>

        <p>
            After scanning, enter the six-digit code shown in your app.
        </p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first('code') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.2fa.enable') }}">
            @csrf

            <input
                type="text"
                name="code"
                placeholder="Enter 6-digit code"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                required
            >

            <button type="submit">Enable Two-Factor Authentication</button>
        </form>
    </div>

</body>
</html>