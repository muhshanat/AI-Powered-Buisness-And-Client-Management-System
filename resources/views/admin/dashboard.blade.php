
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
</head>
<body>
    <h1>Super Admin Dashboard</h1>
    <p>Welcome to the administration panel.</p>

    <hr>

<h3>Management</h3>

<p>
    <a href="{{ route('admin.customers.index') }}">
        Customer Management
    </a>
</p>

<p>
    <a href="{{ route('admin.roles.index') }}">
        Role Management
    </a>
</p>

<p>
    <a href="{{ route('admin.admins.index') }}">
        Admin Management
    </a>
</p>

<hr>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
