
@extends('admin.layouts.app')

@section('title', 'Customer Trash')
@section('heading', 'Customer Trash')

@section('content')

    

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="content-card">

        <div class="action-links" style="margin-bottom: 20px;">
            <a href="{{ route('admin.customers.index') }}"
               class="btn btn-secondary">
                Back to Customer Management
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
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->deleted_at }}</td>
                            <td>
                                <form
                                    action="{{ route('admin.customers.restore', $customer->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Restore this customer?')"
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
                            <td colspan="5">No deleted customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
