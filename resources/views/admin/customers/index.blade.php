
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #243447;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            margin-top: 15px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #243447;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top">
            <h1>Customer Management</h1>

            <a class="btn" href="{{ route('admin.customers.create') }}">
                + Add Customer
            </a>
        </div>

        <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <table>
           
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Registered At</th>
        <th>Actions</th>
    </tr>
</thead>


            <tbody>
               
@forelse ($customers as $customer)
    <tr>
        <td>{{ $customer->id }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->email }}</td>
        <td>{{ $customer->created_at?->format('d M Y') ?? 'N/A' }}</td>
        <td>
            <a href="{{ route('admin.customers.edit', $customer) }}">
                Edit
            </a>

            <form action="{{ route('admin.customers.destroy', $customer) }}"
                  method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Are you sure you want to move this customer to Trash?');">
                @csrf
                @method('DELETE')

                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5">No customers found.</td>
    </tr>
@endforelse

            </tbody>
        </table>
    </div>
</body>
</html>
