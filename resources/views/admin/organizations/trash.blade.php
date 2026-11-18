
@extends('admin.layouts.app')

@section('title', 'organization Trash')
@section('heading', 'organization Trash')

@section('content')

    

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="content-card">

        <div class="action-links" style="margin-bottom: 20px;">
            <a href="{{ route('admin.organizations.index') }}"
               class="btn btn-secondary">
                Back to organization Management
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
                    @forelse ($organizations as $organization)
                        <tr>
                            <td>{{ $organization->id }}</td>
                            <td>{{ $organization->name }}</td>
                            <td>{{ $organization->email }}</td>
                            <td>{{ $organization->deleted_at }}</td>
                            <td>
                                <form
                                    action="{{ route('admin.organizations.restore', $organization->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Restore this organization?')"
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
                            <td colspan="5">No deleted organizations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
