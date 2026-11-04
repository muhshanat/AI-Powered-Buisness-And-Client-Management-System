<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    $canViewCustomers = $isSuperAdmin || in_array('customers.view', $permissions);
    $canViewRoles = $isSuperAdmin || in_array('roles.view', $permissions);
    $canViewAdmins = $isSuperAdmin || in_array('admins.view', $permissions);

    $canViewCustomerTrash = $isSuperAdmin || in_array('customers.delete', $permissions);
    $canViewRoleTrash = $isSuperAdmin || in_array('roles.delete', $permissions);
    $canViewAdminTrash = $isSuperAdmin || in_array('admins.delete', $permissions);

    $trashActive = request()->routeIs(
        'admin.customers.trash',
        'admin.roles.trash',
        'admin.admins.trash'
    );
@endphp

{{-- SVG ICON LIBRARY --}}
<svg xmlns="http://www.w3.org/2000/svg" class="icon-library">
    <symbol id="icon-shield" viewBox="0 0 24 24">
        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
        <path d="m9 12 2 2 4-4"/>
    </symbol>

    <symbol id="icon-home" viewBox="0 0 24 24">
        <path d="m3 10 9-7 9 7"/>
        <path d="M5 9v12h14V9M9 21v-7h6v7"/>
    </symbol>

    <symbol id="icon-users" viewBox="0 0 24 24">
        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="10" cy="7" r="4"/>
        <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
    </symbol>

    <symbol id="icon-user" viewBox="0 0 24 24">
        <circle cx="12" cy="8" r="4"/>
        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
    </symbol>

    <symbol id="icon-lock" viewBox="0 0 24 24">
        <rect x="3" y="11" width="18" height="11" rx="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </symbol>

    <symbol id="icon-file" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <path d="M14 2v6h6M8 13h8M8 17h8"/>
    </symbol>

    <symbol id="icon-monitor" viewBox="0 0 24 24">
        <rect x="2" y="3" width="20" height="14" rx="2"/>
        <path d="M8 21h8M12 17v4"/>
    </symbol>

    <symbol id="icon-bell" viewBox="0 0 24 24">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
        <path d="M10 21h4"/>
    </symbol>

    <symbol id="icon-trash" viewBox="0 0 24 24">
        <path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6"/>
        <path d="M10 11v5M14 11v5"/>
    </symbol>

    <symbol id="icon-search" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="7"/>
        <path d="m20 20-4-4"/>
    </symbol>

    <symbol id="icon-chevron" viewBox="0 0 24 24">
        <path d="m6 9 6 6 6-6"/>
    </symbol>

    <symbol id="icon-logout" viewBox="0 0 24 24">
        <path d="M10 17l5-5-5-5M15 12H3"/>
        <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
    </symbol>
</svg>

<div class="admin-shell">

    {{-- TOP HEADER --}}
    <header class="admin-header">

        <div class="header-main">

            <a href="{{ route('admin.dashboard') }}" class="brand">
                <span class="brand-icon">
                    <svg><use href="#icon-shield"></use></svg>
                </span>

                <span class="brand-name">Admin Panel</span>
            </a>

            <div class="header-actions">

                <button type="button"
                    class="header-icon"
                    title="Search coming soon"
                    aria-label="Search">
                    <svg><use href="#icon-search"></use></svg>
                </button>

               <details name="admin-dropdown" class="notification-dropdown">
    <summary class="header-icon notification-button"
        title="Notifications"
        aria-label="Notifications">

        <svg><use href="#icon-bell"></use></svg>

        @if($user && $user->unreadNotifications()->exists())
            <span class="notification-dot"></span>
        @endif
    </summary>

    <div class="notification-menu">
        <div class="notification-menu-header">
            <strong style="display:flex; align-items:center; gap:9px;">
    <svg width="20" height="20"
         viewBox="0 0 24 24"
         fill="none"
         stroke="#2878D0"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
        <path d="M10 21h4"/>
    </svg>
    Notifications
</strong>


            <span>{{ $user->unreadNotifications()->count() }} unread</span>
        </div>

        @forelse($user->notifications()->latest()->take(5)->get() as $notification)
            <a href="{{ route('admin.notifications.index') }}"
               class="notification-item {{ $notification->read_at ? '' : 'unread' }}">

               <strong style="display:flex; align-items:center; gap:8px;">
    <svg width="17" height="17"
         viewBox="0 0 24 24"
         fill="none"
         stroke="#2878D0"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
        <path d="M10 21h4"/>
    </svg>
    {{ $notification->data['title'] ?? 'System Notification' }}
</strong>

                <p>
                    {{ $notification->data['message'] ?? 'New notification' }}
                </p>

                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </a>
        @empty
            <p class="notification-empty">No notifications yet.</p>
        @endforelse

        <a href="{{ route('admin.notifications.index') }}"
           class="notification-view-all">
            View All Notifications
        </a>
    </div>
</details>

                <details class="profile-dropdown">

                    <summary class="profile-trigger">

                        <span class="profile-avatar">
                            {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                        </span>

                        <span class="profile-text">
                            <strong>{{ $user->name ?? 'Admin' }}</strong>
                            <small>
                                {{ $isSuperAdmin ? 'Super Admin' : ($user->role ?? 'Admin') }}
                            </small>
                        </span>

                        <svg class="chevron">
                            <use href="#icon-chevron"></use>
                        </svg>

                    </summary>

                    <div class="profile-menu">

                        <div class="profile-menu-info">
                            <strong>{{ $user->name ?? 'Admin' }}</strong>
                            <small>{{ $user->email ?? '' }}</small>
                        </div>

                        <div class="profile-menu-divider"></div>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf

                            <button type="submit" class="dropdown-logout">
                                <svg><use href="#icon-logout"></use></svg>
                                Logout
                            </button>
                        </form>

                    </div>

                </details>

            </div>

        </div>

        {{-- HORIZONTAL NAVIGATION --}}
        <nav class="main-navigation">

            <div class="navigation-scroll">

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg><use href="#icon-home"></use></svg>
                    <span>Dashboard</span>
                </a>

                @if ($canViewCustomers)
                    <a href="{{ route('admin.customers.index') }}"
                        class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <svg><use href="#icon-users"></use></svg>
                        <span>Customers</span>
                    </a>
                @endif

                @if ($canViewAdmins)
                    <a href="{{ route('admin.admins.index') }}"
                        class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                        <svg><use href="#icon-user"></use></svg>
                        <span>Admins</span>
                    </a>
                @endif

                @if ($canViewRoles)
                    <a href="{{ route('admin.roles.index') }}"
                        class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <svg><use href="#icon-shield"></use></svg>
                        <span>Roles & Permissions</span>
                    </a>
                @endif

                @if ($isSuperAdmin)
                

                   <a href="{{ route('admin.activity-logs.index') }}"
   class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
    <svg><use href="#icon-file"></use></svg>
    <span>Activity Logs</span>
</a>




    <a href="{{ route('admin.sessions.index') }}"
   class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
    <svg><use href="#icon-monitor"></use></svg>
    <span>Sessions</span>
    </a>



                    <a href="{{ route('admin.notifications.index') }}"
    class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
    <svg><use href="#icon-bell"></use></svg>
    <span>Notifications</span>
</a>



     <a href="{{ route('admin.2fa.settings') }}"
   class="nav-link {{ request()->routeIs('admin.2fa.settings') ? 'active' : '' }}">
    <svg><use href="#icon-lock"></use></svg>
    <span>2FA Settings</span>
</a>




                    @if ($canViewCustomerTrash || $canViewRoleTrash || $canViewAdminTrash)

                       <details name="admin-dropdown" class="trash-dropdown">

    <summary class="nav-link {{ $trashActive ? 'active' : '' }}">
        <svg><use href="#icon-trash"></use></svg>
        <span>Trash</span>
        <svg class="chevron"><use href="#icon-chevron"></use></svg>
    </summary>

    <div class="trash-menu">

        @if ($canViewCustomerTrash)
            <a href="{{ route('admin.customers.trash') }}">
                Customers Trash
            </a>
        @endif

        @if ($canViewRoleTrash)
            <a href="{{ route('admin.roles.trash') }}">
                Roles Trash
            </a>
        @endif

        @if ($canViewAdminTrash)
            <a href="{{ route('admin.admins.trash') }}">
                Admins Trash
            </a>
        @endif

    </div>

</details>

                    @endif

                @endif

            </div>

        </nav>

    </header>


    {{-- MAIN CONTENT --}}
    <div class="main-area">

        <main class="page-content">

            <div class="content-heading">
                <div>
                    <h1>@yield('heading', 'Dashboard')</h1>
                   <p>@yield('page_description', 'Manage your application and administrative activities.')</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    Please check the form for errors.
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>
