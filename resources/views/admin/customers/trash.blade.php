
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deleted Customers</title>
</head>
<body>
    <h1>Customer Trash</h1>

    <a href="{{ route('admin.customers.index') }}">
        Back to Customers
    </a>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

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
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->deleted_at?->format('d M Y H:i') }}</td>
                    <td>
                        <form
                            action="{{ route('admin.customers.restore', $customer->id) }}"
                            method="POST"
                        >
                            @csrf
                            <button type="submit">Restore</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No deleted customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
