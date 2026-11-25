
@extends('organization.layouts.app')

@section('title', 'Edit Service')
@section('heading', 'Edit Service')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-services.css') }}">
@endpush

@section('content')
<div class="content-card service-form-card">

    <div class="page-header">
        <div>
            <h2>Edit Service</h2>
            <p>Update your service information.</p>
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

    <form action="{{ route('organization.services.update', $service->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="service-form-grid">

            <div class="form-group">
                <label for="name">Service Name *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $service->name) }}"
                       required>
            </div>

            <div class="form-group">
                <label for="price">Price (৳) *</label>
                <input type="number"
                       id="price"
                       name="price"
                       value="{{ old('price', $service->price) }}"
                       min="0"
                       step="0.01"
                       required>
            </div>

            <div class="form-group">
                <label for="duration">Duration (Minutes)</label>
                <input type="number"
                       id="duration"
                       name="duration"
                       value="{{ old('duration', $service->duration) }}"
                       min="1">
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          rows="5">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label>Current Service Image</label>

                @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}"
                         alt="{{ $service->name }}"
                         width="120"
                         style="border-radius: 8px; margin-bottom: 12px;">
                @else
                    <p>No image uploaded.</p>
                @endif

                <label for="image">Upload New Image (Optional)</label>
                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*">

                <small>Maximum image size: 2 MB. Leave empty to keep the current image.</small>
            </div>

        </div>

        <div class="service-form-actions">
            <button type="submit" class="btn btn-primary">
                Update Service
            </button>

            <a href="{{ route('organization.services.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
