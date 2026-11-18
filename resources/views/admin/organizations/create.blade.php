
@extends('admin.layouts.app')

@section('title', 'Add New Organization')
@section('heading', 'Add New Organization')

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

        <form action="{{ route('admin.organizations.store') }}" method="POST">
            @csrf

            {{-- Organization Information --}}
            <h3>Organization Information</h3>

            <div class="form-group">
                <label for="organization_name">Organization Name</label>
                <input
                    type="text"
                    id="organization_name"
                    name="organization_name"
                    class="form-control"
                    value="{{ old('organization_name') }}"
                    placeholder="Enter organization's name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="organization_email">Organization Contact Email</label>
                <input
                    type="email"
                    id="organization_email"
                    name="organization_email"
                    class="form-control"
                    value="{{ old('organization_email') }}"
                    placeholder="Enter organization's contact email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}"
                    placeholder="Enter phone number"
                >
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input
                    type="url"
                    id="website"
                    name="website"
                    class="form-control"
                    value="{{ old('website') }}"
                    placeholder="https://example.com"
                >
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    class="form-control"
                    value="{{ old('address') }}"
                    placeholder="Enter organization address"
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Brief description of the organization"
                >{{ old('description') }}</textarea>
            </div>

            {{-- Owner Account Information --}}
            <hr>

            <h3>Owner Account Details</h3>

            <div class="form-group">
                <label for="owner_name">Owner Full Name</label>
                <input
                    type="text"
                    id="owner_name"
                    name="owner_name"
                    class="form-control"
                    value="{{ old('owner_name') }}"
                    placeholder="Enter owner's full name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="owner_email">Owner Login Email</label>
                <input
                    type="email"
                    id="owner_email"
                    name="owner_email"
                    class="form-control"
                    value="{{ old('owner_email') }}"
                    placeholder="Enter owner's login email"
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
                    placeholder="Enter owner login password"
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
                    Create Organization
                </button>

                <a href="{{ route('admin.organizations.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

@endsection
