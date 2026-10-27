<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div class="admin-layout">

        <aside class="sidebar">
            <div class="sidebar-logo">
                Admin<span>Panel.</span>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>

                <li>
                    <a href="{{ route('admin.customers.index') }}">
                        Customer Management
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.roles.index') }}">
                        Role Management
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.admins.index') }}">
                        Admin Management
                    </a>
                </li>
            </ul>

            <form
                class="logout-form"
                method="POST"
                action="{{ route('admin.logout') }}"
            >
                @csrf
                <button type="submit">Logout</button>
            </form>
        </aside>

        <div class="main-area">

            <header class="topbar">
                <h2>@yield('heading', 'Dashboard')</h2>
                <span>Super Admin</span>
            </header>

            <main class="page-content">

                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>

    </div>

</body>
</html>