
<!DOCTYPE html>
<html>
<head>
    <title>Edit Role</title>
</head>
<body>
    <h1>Edit Role</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.roles.update', $role->id) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Role Name:</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $role->name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Description:</label>
            <textarea name="description">{{ old('description', $role->description) }}</textarea>
        </div>

        <br>

        <button type="submit">Update Role</button>
    </form>

    <br>

    <a href="{{ route('admin.roles.index') }}">Back to Roles</a>
</body>
</html>