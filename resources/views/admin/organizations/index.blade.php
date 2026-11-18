
@extends('admin.layouts.app')

@section('title', 'Organization Management')
@section('heading', 'Organization Management')

@section('content')

<div class="content-card">

    <div class="action-links" style="margin-bottom: 20px;">
        <a href="{{ route('admin.organizations.create') }}" class="btn">
            + Add New Organization
        </a>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    <div style="overflow-x: auto;">

        <table class="admin-table">
           
<thead>
    <tr>
        <th>ID</th>
        <th>Organization</th>
        <th>Contact Email</th>
        <th>Website</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
</thead>

<tbody>
    @forelse ($organizations as $organization)
        <tr>
            <td>{{ $organization->id }}</td>

            <td>{{ $organization->name }}</td>

            <td>{{ $organization->email }}</td>

            <td>
                @if ($organization->website)
                    <a href="{{ $organization->website }}" target="_blank">
                        Visit Website
                    </a>
                @else
                    N/A
                @endif
            </td>

            <td>{{ $organization->description ?? 'N/A' }}</td>

            <td>{{ ucfirst($organization->status) }}</td>

            <td>
                <div class="action-links">

                    <a href="{{ route('admin.organizations.show', $organization->id) }}"
                       class="btn btn-secondary">
                        View
                    </a>

                    <a href="{{ route('admin.organizations.edit', $organization->id) }}"
                       class="btn">
                        Edit
                    </a>

                    <form action="{{ route('admin.organizations.destroy', $organization->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this organization?')">
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
            <td colspan="7" style="text-align: center;">
                No organizations found.
            </td>
        </tr>
    @endforelse
</tbody>

        </table>

    </div>

</div>

@endsection
