<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user && $user->role === 'super_admin';

        $role = $user
            ? \App\Models\Role::where('name', $user->role)->first()
            : null;

        $permissions = $role->permissions ?? [];
    @endphp

    <div class="admin-layout">

        <aside class="sidebar">
            <div class="sidebar-logo">
                Admin<span>Panel.</span>
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>

                @if ($isSuperAdmin || in_array('customers.view', $permissions))
                    <li>
                        <a href="{{ route('admin.customers.index') }}">
                            Customer Management
                        </a>
                    </li>
                @endif

                @if ($isSuperAdmin || in_array('roles.view', $permissions))
                    <li>
                        <a href="{{ route('admin.roles.index') }}">
                            Role Management
                        </a>
                    </li>
                @endif

                @if ($isSuperAdmin || in_array('admins.view', $permissions))
                    <li>
                        <a href="{{ route('admin.admins.index') }}">
                            Admin Management
                        </a>
                    </li>
                @endif

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

                <div>
                    <span>{{ $user->name ?? 'Admin' }}</span>

                    <small>
                        {{ $isSuperAdmin ? 'Super Admin' : ($user->role ?? 'Admin') }}
                    </small>
                </div>
            </header>

            <main class="page-content">

                

                @if (session('error'))
                    <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>

    </div>

</body>
</html>