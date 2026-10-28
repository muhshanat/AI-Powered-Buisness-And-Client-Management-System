
@extends('admin.layouts.app')

@section('title', 'Customer Management')
@section('heading', 'Customer Management')

@section('content')

    <div class="page-heading">
        <h1>Customer Management</h1>
        <p>View, update, and manage customer accounts.</p>
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
            <a href="{{ route('admin.customers.create') }}" class="btn">
                + Add New Customer
            </a>

            <a href="{{ route('admin.customers.trash') }}"
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
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <div class="action-links">
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                       class="btn">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.customers.destroy', $customer->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Move this customer to Trash?')"
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
                            <td colspan="4">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
