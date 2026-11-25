@extends('organization.layouts.app')

@section('title', 'Add Service')
@section('heading', 'Add New Service')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-services.css') }}">
@endpush

@section('content')
<div class="content-card service-form-card">

    <div class="page-header">
        <div>
            <h2>Add New Service</h2>
            <p>Enter the details of the service your organization offers.</p>
        </div>

        <a href="{{ route('organization.services.index') }}" class="btn btn-secondary">
            Back to Services
        </a>
    </div>

    @if($errors->any())
        <div class="error-alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('organization.services.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="service-form-grid">

            <div class="form-group">
                <label for="name">Service Name *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Enter service name"
                       required>
            </div>

            <div class="form-group">
                <label for="price">Price (৳) *</label>
                <input type="number"
                       id="price"
                       name="price"
                       value="{{ old('price') }}"
                       min="0"
                       step="0.01"
                       placeholder="Enter service price"
                       required>
            </div>

            <div class="form-group">
                <label for="duration">Duration (Minutes)</label>
                <input type="number"
                       id="duration"
                       name="duration"
                       value="{{ old('duration') }}"
                       min="1"
                       placeholder="e.g. 60">
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          rows="5"
                          placeholder="Describe your service...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="image">Service Image</label>
                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*">
                <small>Maximum image size: 2 MB.</small>
            </div>

        </div>

        <div class="service-form-actions">
            <button type="submit" class="btn btn-primary">
                Save Service
            </button>

            <a href="{{ route('organization.services.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
