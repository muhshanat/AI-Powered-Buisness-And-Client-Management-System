
@extends('admin.layouts.app')

@section('title', 'Admin Management')
@section('heading', 'Admin Management')

@section('content')


  

    <div class="content-card">

        <div class="action-links" style="margin-bottom: 20px;">
            <a href="{{ route('admin.admins.create') }}" class="btn">
                + Add New Admin
            </a>

            <a href="{{ route('admin.admins.trash') }}"
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                       class="btn">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.admins.destroy', $admin->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Move this admin to Trash?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                No admins found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection