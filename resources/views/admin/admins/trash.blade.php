
<!DOCTYPE html>
<html>
<head>
    <title>Deleted Admins</title>
</head>
<body>
    <h1>Deleted Admins (Trash)</h1>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <p>
        <a href="{{ route('admin.admins.index') }}">Back to Admin Management</a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Deleted At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->deleted_at }}</td>
                    <td>
                        <form
                            action="{{ route('admin.admins.restore', $admin->id) }}"
                            method="POST"
                        >
                            @csrf
                            <button
                                type="submit"
                                onclick="return confirm('Restore this admin?')"
                            >
                                Restore
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No deleted admins found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
