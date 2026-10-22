
<!DOCTYPE html>
<html>
<head>
    <title>Add New Admin</title>
</head>
<body>
    <h1>Add New Admin</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf

        <div>
            <label>Name:</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Email:</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Password:</label>
            <input
                type="password"
                name="password"
                required
            >
        </div>

        <br>

        <div>
            <label>Confirm Password:</label>
            <input
                type="password"
                name="password_confirmation"
                required
            >
        </div>

        <br>

        <button type="submit">Save Admin</button>
    </form>

    <br>

    <a href="{{ route('admin.admins.index') }}">Back to Admin List</a>
</body>
</html>
