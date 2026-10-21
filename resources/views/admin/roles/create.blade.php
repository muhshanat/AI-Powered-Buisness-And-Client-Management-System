
<!DOCTYPE html>
<html>
<head>
    <title>Add New Role</title>
</head>
<body>
    <h1>Add New Role</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        <div>
            <label>Role Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <br>

        <div>
            <label>Description:</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <br>

        <button type="submit">Save Role</button>
    </form>

    <br>

    <a href="{{ route('admin.roles.index') }}">Back to Roles</a>
</body>
</html>
