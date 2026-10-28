
@extends('admin.layouts.app')

@section('title', 'Role Management')
@section('heading', 'Role Management')

@section('content')

    <div class="page-heading">
        <h1>Role Management</h1>
        <p>Create and manage roles in your application.</p>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">

        <div class="action-links" style="margin-bottom: 20px;">
            <a href="{{ route('admin.roles.create') }}" class="btn">
                + Add New Role
            </a>

            <a href="{{ route('admin.roles.trash') }}"
               class="btn btn-secondary">
                View Trash
            </a>

            <a href="{{ route('admin.dashboard') }}"
               class="btn btn-secondary">
                Back to Dashboard
            </a>
        </div>

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description ?: 'No description' }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="btn">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.roles.destroy', $role->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Move this role to Trash?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
