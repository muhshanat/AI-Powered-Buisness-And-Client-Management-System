@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('heading', 'Dashboard')

@section('content')

    <div class="page-heading">
        <h1>Welcome Back!</h1>
        <p>Manage your application and administrative activities.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <p>Customer Management</p>
            <h3>Customers</h3>
            <br>
            <a href="{{ route('admin.customers.index') }}" class="btn">
                View Customers
            </a>
        </div>

        <div class="stat-card">
            <p>Role Management</p>
            <h3>Roles</h3>
            <br>
            <a href="{{ route('admin.roles.index') }}" class="btn">
                Manage Roles
            </a>
        </div>

        <div class="stat-card">
            <p>Admin Management</p>
            <h3>Admins</h3>
            <br>
            <a href="{{ route('admin.admins.index') }}" class="btn">
                Manage Admins
            </a>
        </div>

    </div>

    <div class="content-card">
        <h3>Administration Overview</h3>
        <br>
        <p>
            Use the sidebar or the cards above to manage customers,
            roles, and admin accounts.
        </p>
    </div>

@endsection
