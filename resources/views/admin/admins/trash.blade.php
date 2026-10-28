
@extends('admin.layouts.app')

@section('title', 'Admin Trash')
@section('heading', 'Admin Trash')

@section('content')

    <div class="page-heading">
        <h1>Deleted Administrators</h1>
        <p>View and restore administrator accounts that were moved to Trash.</p>
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
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                Back to Admin Management
            </a>
        </div>

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->deleted_at }}</td>
                            <td>
                                <form
                                    action="{{ route('admin.admins.restore', $admin->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Restore this admin?')"
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
                            <td colspan="5">No deleted admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
