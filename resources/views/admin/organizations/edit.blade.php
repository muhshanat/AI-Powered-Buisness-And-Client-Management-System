
@extends('admin.layouts.app')

@section('title', 'Edit Organization')
@section('heading', 'Edit Organization')

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

        <form action="{{ route('admin.organizations.update', $organization->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h3>Organization Information</h3>

            <div class="form-group">
                <label for="name">Organization Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $organization->name) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Organization Contact Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $organization->email) }}"
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
                    value="{{ old('phone', $organization->phone) }}"
                >
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input
                    type="url"
                    id="website"
                    name="website"
                    class="form-control"
                    value="{{ old('website', $organization->website) }}"
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
                    value="{{ old('address', $organization->address) }}"
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="4"
                >{{ old('description', $organization->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Organization Status</label>
                <select
                    id="status"
                    name="status"
                    class="form-control"
                    required
                >
                    <option value="pending"
                        {{ old('status', $organization->status) == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="approved"
                        {{ old('status', $organization->status) == 'approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                    <option value="rejected"
                        {{ old('status', $organization->status) == 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                    <option value="suspended"
                        {{ old('status', $organization->status) == 'suspended' ? 'selected' : '' }}>
                        Suspended
                    </option>
                </select>
            </div>

            <div class="action-links">
                <button type="submit" class="btn">
                    Update Organization
                </button>

                <a href="{{ route('admin.organizations.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

@endsection
