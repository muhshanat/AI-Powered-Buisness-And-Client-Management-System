
@extends('admin.layouts.app')

@section('title', 'Add New Customer')
@section('heading', 'Add New Customer')

@section('content')

    

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

        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Enter customer's full name"
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
                    value="{{ old('email') }}"
                    placeholder="Enter customer's email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm password"
                    required
                >
            </div>

            <div class="action-links">
                <button type="submit" class="btn">
                    Create Customer
                </button>

                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

@endsection
