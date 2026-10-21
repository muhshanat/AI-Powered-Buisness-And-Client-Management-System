<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Super Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}" required
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-6">
                <label for="password">Password</label>
                <input id="password" name="password" type="password"
                       required class="w-full border rounded p-2">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white p-2 rounded">
                Log In
            </button>
        </form>
    </div>
</body>
</html>
