@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('heading', 'Edit Customer')

@section('content')

    <div class="page-heading">
        <h1>Edit Customer</h1>
        <p>Update the customer's account information.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $customer->name) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $customer->email) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">New Password (Optional)</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Leave blank to keep the current password"
                >
                <small>Only enter a password if you want to change it.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm the new password"
                >
            </div>

            <div class="action-links">
                <button type="submit" class="btn">
                    Update Customer
                </button>

                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

@endsection
