<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
</head>
<body>
    <h1>Edit Admin</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Name:</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $admin->name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Email:</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $admin->email) }}"
                required
            >
        </div>

        <br>

        <p>Leave the password fields empty if you don't want to change the password.</p>

        <div>
            <label>New Password:</label>
            <input type="password" name="password">
        </div>

        <br>

        <div>
            <label>Confirm New Password:</label>
            <input type="password" name="password_confirmation">
        </div>

        <br>

        <button type="submit">Update Admin</button>
    </form>

    <br>

    <a href="{{ route('admin.admins.index') }}">Back to Admin List</a>
</body>
</html>
