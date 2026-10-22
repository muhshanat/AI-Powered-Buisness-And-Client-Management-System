<!DOCTYPE html>
<html>
<head>
    <title>Admin Management</title>
</head>
<body>
    <h1>Admin Management</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <p>
        <a href="{{ route('admin.admins.create') }}">Add New Admin</a>
        |
        <a href="{{ route('admin.admins.trash') }}">View Trash</a>
        |
        <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <a href="{{ route('admin.admins.edit', $admin->id) }}">
                            Edit
                        </a>

                        <form
                            action="{{ route('admin.admins.destroy', $admin->id) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Move this admin to Trash?')"
                            >
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No admins found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
