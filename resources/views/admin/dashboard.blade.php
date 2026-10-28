@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('heading', 'Dashboard')

@section('content')


    <div class="page-heading">
        <h1>Welcome Back!</h1>
        <p>Manage your application and administrative activities.</p>
    </div>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user->role === 'super_admin';

        $role = \App\Models\Role::where('name', $user->role)->first();
        $permissions = $role->permissions ?? [];

        if ($isSuperAdmin) {
            $permissions = [
                'customers.view',
                'roles.view',
                'admins.view'
            ];
        }
    @endphp

    <div class="stats-grid">

        @if (in_array('customers.view', $permissions))
            <div class="stat-card">
                <p>Customer Management</p>
                <h3>Customers</h3>
                <br>
                <a href="{{ route('admin.customers.index') }}" class="btn">
                    View Customers
                </a>
            </div>
        @endif

        @if (in_array('roles.view', $permissions))
            <div class="stat-card">
                <p>Role Management</p>
                <h3>Roles</h3>
                <br>
                <a href="{{ route('admin.roles.index') }}" class="btn">
                    Manage Roles
                </a>
            </div>
        @endif

        @if (in_array('admins.view', $permissions))
            <div class="stat-card">
                <p>Admin Management</p>
                <h3>Admins</h3>
                <br>
                <a href="{{ route('admin.admins.index') }}" class="btn">
                    Manage Admins
                </a>
            </div>
        @endif

    </div>

    <div class="content-card">
        <h3>Administration Overview</h3>
        <br>
        <p>
            Use the cards above to access the modules permitted for your account.
        </p>
    </div>

@endsection