<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>

   <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@stack('styles')

@php
    $theme = \App\Models\Setting::pluck('value', 'key')->toArray();
@endphp

<style>
    :root {
        --app-primary: {{ $theme['primary_color'] ?? '#1769B2' }};
        --app-secondary: {{ $theme['secondary_color'] ?? '#647F9B' }};
        --app-background: {{ $theme['page_background'] ?? '#E5EDF6' }};
        --app-nav-background: {{ $theme['nav_background'] ?? '#FFFFFF' }};
        --app-nav-text: {{ $theme['nav_text_color'] ?? '#173B5E' }};
        --app-button: {{ $theme['button_color'] ?? '#1769B2' }};
        --app-heading: {{ $theme['heading_color'] ?? '#173B5E' }};
        --app-body-text: {{ $theme['body_text_color'] ?? '#52677F' }};
        --app-logo-text: {{ $theme['logo_text_color'] ?? '#173B5E' }};
    }
</style>

@if(request()->routeIs('profile.edit'))
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}?v=1">
@endif
    

</head>

<script>
document.addEventListener('click', function (event) {

    const dropdowns = document.querySelectorAll(
        '.nav-dropdown, .profile-dropdown, .notification-dropdown, .add-account-dropdown'
    );

    dropdowns.forEach(function (dropdown) {

        if (!dropdown.contains(event.target)) {
            dropdown.removeAttribute('open');
        }

    });

});
</script>


<body>

@php

    $applicationName = \App\Models\Setting::where('key', 'application_name')->value('value') ?? 'Admin Panel';
    $user = auth()->user();
    $isSuperAdmin = $user && $user->role === 'super_admin';

    $role = $user
        ? \App\Models\Role::where('name', $user->role)->first()
        : null;

    $permissions = $role->permissions ?? [];

    $canViewOrganizations = $isSuperAdmin || in_array('organizations.view', $permissions);
    $canViewRoles = $isSuperAdmin || in_array('roles.view', $permissions);
    $canViewAdmins = $isSuperAdmin || in_array('admins.view', $permissions);

    $canViewOrganizationTrash = $isSuperAdmin || in_array('organizations.delete', $permissions);
    $canViewRoleTrash = $isSuperAdmin || in_array('roles.delete', $permissions);
    $canViewAdminTrash = $isSuperAdmin || in_array('admins.delete', $permissions);

    $trashActive = request()->routeIs(
    'admin.trash.*',
    'admin.organizations.trash',
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

        {{-- HORIZONTAL NAVIGATION --}}
       <nav class="main-navigation">
    <div class="navigation-scroll">

    <a href="{{ route('admin.dashboard') }}" class="brand top-nav-brand">
   <span class="brand-icon">
    @if(!empty($theme['logo_path']))
        <img src="{{ asset('storage/' . $theme['logo_path']) }}"
             alt="Application Logo"
             class="admin-brand-image">
    @else
        <svg><use href="#icon-shield"></use></svg>
    @endif
</span>

    <span class="brand-name">{{ $applicationName }}</span>
</a>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg><use href="#icon-dashboard"></use></svg>
            <span>Dashboard</span>
        </a>

        {{-- Organizations --}}
        @if ($canViewOrganizations)
            <a href="{{ route('admin.organizations.index') }}"
               class="nav-link {{ request()->routeIs('admin.organizations.*') && !request()->routeIs('admin.organizations.trash') ? 'active' : '' }}">
                <svg><use href="#icon-users"></use></svg>
                <span>Organizations</span>
            </a>
        @endif

        {{-- Master Setup --}}
        @if ($canViewAdmins || $canViewRoles)
            <details name="main-navigation-dropdown" class="nav-dropdown">
                <summary class="nav-link {{ request()->routeIs('admin.admins.*', 'admin.roles.*') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.8"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 3L3 8l9 5 9-5-9-5z"/>
        <path d="M3 12l9 5 9-5"/>
        <path d="M3 16l9 5 9-5"/>
    </svg>
    <span>Master Setup</span>
   <span class="dropdown-arrow"></span>
</summary>

                <div class="nav-dropdown-menu">
                    @if ($canViewAdmins)
                        <a href="{{ route('admin.admins.index') }}"
                           class="dropdown-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                            Admins
                        </a>
                    @endif

                    @if ($canViewRoles)
                        <a href="{{ route('admin.roles.index') }}"
                           class="dropdown-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            Roles & Permissions
                        </a>
                    @endif
                </div>
            </details>
        @endif

       {{-- Settings --}}
@if ($isSuperAdmin)
    <details name="main-navigation-dropdown" class="nav-dropdown">
        <summary class="nav-link {{ request()->routeIs('admin.activity-logs.*', 'admin.sessions.*', 'admin.notifications.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 7h10"/>
                <path d="M18 7h2"/>
                <path d="M4 17h2"/>
                <path d="M10 17h10"/>
                <circle cx="16" cy="7" r="2"/>
                <circle cx="8" cy="17" r="2"/>
            </svg>
            <span>Settings</span>
            <span class="dropdown-arrow"></span>
        </summary>

        <div class="nav-dropdown-menu">
            <a href="{{ route('admin.activity-logs.index') }}"
               class="dropdown-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                Activity Logs
            </a>

            <a href="{{ route('admin.sessions.index') }}"
               class="dropdown-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                Sessions
            </a>

            <a href="{{ route('admin.appearance.index') }}"
   class="dropdown-link {{ request()->routeIs('admin.appearance.*') ? 'active' : '' }}">
    Appearance
</a>

           @if ($canViewOrganizationTrash || $canViewRoleTrash || $canViewAdminTrash)
    <a href="{{ route('admin.trash.index') }}"
       class="dropdown-link {{ request()->routeIs('admin.trash.*') ? 'active' : '' }}">
        
        Trash
    </a>
@endif
        </div>
    </details>
@endif





    </div>
</nav>

    {{-- TOP HEADER --}}
    <header class="admin-header">

        <div class="header-main">

            <a href="{{ route('admin.dashboard') }}" class="brand">
                <span class="brand-icon">
                    <svg><use href="#icon-shield"></use></svg>
                </span>

                <span class="brand-name">{{ $applicationName }}</span>
            </a>

            <div class="header-actions">


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
    @if($user && $user->profile_photo_path)
        <img
            src="{{ asset('storage/' . $user->profile_photo_path) }}"
            alt="{{ $user->name }}"
            class="profile-avatar-image"
        >
    @else
        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
    @endif
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


                        {{-- Profile --}}
<a href="{{ route('profile.edit') }}" class="profile-menu-link">
    <svg class="profile-menu-icon">
        <use href="#icon-user"></use>
    </svg>
    <span>Profile</span>
</a>

{{-- 2FA Settings --}}
@if ($isSuperAdmin)
    <a href="{{ route('admin.2fa.settings') }}" class="profile-menu-link">
        <svg class="profile-menu-icon">
            <use href="#icon-lock"></use>
        </svg>
        <span>2FA Settings</span>
    </a>
@endif

<div class="profile-menu-divider"></div>


                {{-- Multiple Account Switcher --}}
<div class="account-switcher-section">

    <strong class="account-switcher-title">Switch Accounts</strong>

    @php
        $savedAccounts = session('saved_accounts', []);
    @endphp

    @forelse($savedAccounts as $account)

        <div class="saved-account-item">

    <div class="saved-account-info">

        <div class="account-name-row">
            <svg class="account-icon">
                <use href="#icon-user"></use>
            </svg>

            
            <strong>{{ $account['name'] }}</strong>
        </div>

    
        <small>{{ $account['role'] ?? 'User' }}</small>

    </div>

    @if($account['id'] != $user->id)

        <form method="POST"
              action="{{ route('accounts.switch', $account['id']) }}">
            @csrf

            <button type="submit" class="account-switch-btn">
                <span>⇄</span> Switch
            </button>
        </form>

    @else

        <span class="current-account-label">
            ✓ Current
        </span>

    @endif

</div>

    @empty
        <p class="no-saved-accounts">No other accounts added.</p>
    @endforelse

    <div class="profile-menu-divider"></div>

    <details class="add-account-dropdown">
        <summary>
   <svg class="account-icon"
     viewBox="0 0 24 24"
     fill="none"
     stroke="currentColor"
     stroke-width="1.8"
     stroke-linecap="round"
     stroke-linejoin="round">
    <circle cx="10" cy="7" r="4"/>
    <path d="M4 21v-2a6 6 0 0 1 12 0v2"/>
    <path d="M19 8v6"/>
    <path d="M16 11h6"/>
</svg>
    Add another account
</summary>
        <form method="POST" action="{{ route('accounts.add') }}">
            @csrf

            <input type="email"
                   name="email"
                   placeholder="Account email"
                   required>

            <input type="password"
                   name="password"
                   placeholder="Account password"
                   required>

            <button type="submit">Add Account</button>
        </form>
    </details>

</div>




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



    </header>


    {{-- MAIN CONTENT --}}
    <div class="main-area">

        <main class="page-content">

           

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
