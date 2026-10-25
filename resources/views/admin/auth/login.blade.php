<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Super Admin Login</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #111827;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f9fafb;
        }

        .login-wrapper {
            width: 100%;
            max-width: 560px;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo svg {
            width: 85px;
            height: 85px;
            color: #6b7280;
        }

        .login-card {
            background: #1f2937;
            padding: 24px 30px 20px;
            border-radius: 11px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #f3f4f6;
        }

        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"] {
            width: 100%;
            height: 52px;
            background: #111827;
            border: 1px solid #374151;
            border-radius: 7px;
            padding: 12px;
            color: #f9fafb;
            font-size: 16px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #60a5fa;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 65px !important;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 12px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 27px;
            color: #9ca3af;
            font-size: 16px;
        }

        .remember-row input {
            width: 20px;
            height: 20px;
            margin: 0;
            accent-color: #60a5fa;
            cursor: pointer;
        }

        .remember-row label {
            cursor: pointer;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .forgot-link {
            color: #9ca3af;
            font-size: 16px;
            text-decoration: underline;
        }

        .login-button {
            height: 43px;
            padding: 0 22px;
            border: none;
            border-radius: 9px;
            background: #e5e7eb;
            color: #111827;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .login-button:hover {
            background: #ffffff;
        }

        .error-message {
            color: #fca5a5;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .success-message {
            color: #86efac;
            font-size: 14px;
            margin-bottom: 18px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px 20px;
            }

            .form-footer {
                gap: 18px;
            }

            .forgot-link {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        <div class="logo">
            <svg viewBox="0 0 100 100"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="3"
                 stroke-linejoin="round"
                 aria-label="Super Admin logo">

                <path d="M20 25 L38 15 L56 25 L56 65 L38 75 L20 65 Z"/>
                <path d="M20 25 L38 35 L56 25"/>
                <path d="M38 35 L38 75"/>
                <path d="M38 55 L56 45 L74 55 L74 75 L56 85 L38 75"/>
                <path d="M56 45 L56 65 L74 75"/>
                <path d="M56 45 L74 35 L92 45 L92 65 L74 75"/>
                <path d="M74 35 L74 55 L92 45"/>
            </svg>
        </div>

        <div class="login-card">

            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="password-wrapper">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()"
                        >
                            SHOW
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    <label for="remember">Remember me</label>
                </div>

                <div class="form-footer">

                    @if (Route::has('password.request'))
                        <a
                            class="forgot-link"
                            href="{{ route('password.request') }}"
                        >
                            Forgot your password?
                        </a>
                    @endif

                    <button type="submit" class="login-button">
                        LOG IN
                    </button>

                </div>
            </form>

        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const button = document.querySelector('.toggle-password');

            if (password.type === 'password') {
                password.type = 'text';
                button.textContent = 'HIDE';
            } else {
                password.type = 'password';
                button.textContent = 'SHOW';
            }
        }
    </script>

</body>
</html>
