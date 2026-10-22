```html
<!DOCTYPE html>
<html>
<head>
    <title>Deleted Roles</title>
</head>
<body>
    <h1>Deleted Roles (Trash)</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <p>
        <a href="{{ route('admin.roles.index') }}">
            Back to Role Management
        </a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Description</th>
                <th>Deleted At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description ?? 'N/A' }}</td>
                    <td>{{ $role->deleted_at }}</td>
                    <td>
                        <form
                            action="{{ route('admin.roles.restore', $role->id) }}"
                            method="POST"
                        >
                            @csrf
                            <button
                                type="submit"
                                onclick="return confirm('Restore this role?')"
                            >
                                Restore
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No deleted roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
```