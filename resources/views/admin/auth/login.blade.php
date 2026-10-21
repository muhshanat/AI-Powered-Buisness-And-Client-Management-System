
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>
</head>
<body>
    <h1>Super Admin Login</h1>

    @if ($errors->any())
        <p style="color: red;">
            {{ $errors->first() }}
        </p>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div>
            <label for="email">Email:</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required>
        </div>

        <br>

        <div>
            <label for="password">Password:</label>
            <input id="password" type="password"
                   name="password" required>
        </div>

        <br>

        <button type="submit">Log In</button>
    </form>
</body>
</html>
