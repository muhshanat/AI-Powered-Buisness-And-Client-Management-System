
@extends('admin.layouts.app')

@section('title', 'Role Trash')
@section('heading', 'Role Trash')

@section('content')

    <div class="page-heading">
        <h1>Deleted Roles</h1>
        <p>View and restore roles that have been moved to Trash.</p>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="content-card">

        <div class="action-links" style="margin-bottom: 20px;">
            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-secondary">
                Back to Role Management
            </a>
        </div>

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description ?: 'No description' }}</td>
                            <td>{{ $role->deleted_at }}</td>
                            <td>
                                <form
                                    action="{{ route('admin.roles.restore', $role->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Restore this role?')"
                                >
                                    @csrf

                                    <button type="submit" class="btn">
                                        Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No deleted roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
