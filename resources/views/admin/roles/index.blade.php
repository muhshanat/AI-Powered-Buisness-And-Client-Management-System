
<!DOCTYPE html>
<html>
<head>
    <title>Role Management</title>
</head>
<body>
    <h1>Role Management</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <p>
        <a href="{{ route('admin.roles.create') }}">Add New Role</a>
        |
        <a href="{{ route('admin.roles.trash') }}">View Trash</a>
        |
        <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role->id) }}">
                            Edit
                        </a>

                        <form action="{{ route('admin.roles.destroy', $role->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Move this role to Trash?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
