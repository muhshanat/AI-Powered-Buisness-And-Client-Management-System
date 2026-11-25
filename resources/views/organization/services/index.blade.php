@extends('organization.layouts.app')

@section('title', 'Services')
@section('heading', 'Service Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-services.css') }}">
@endpush

@section('content')
<div class="content-card">
    <div class="page-header">
        <div>
            <h2>All Services</h2>
            <p>Manage the services offered by your organization.</p>
        </div>

        <a href="{{ route('organization.services.create') }}" class="btn btn-primary">
            + Add New Service
        </a>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($services->count() > 0)
        <div class="table-responsive">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}"
                                         alt="{{ $service->name }}"
                                         width="55"
                                         height="55"
                                         style="object-fit: cover; border-radius: 8px;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>

                            <td>{{ $service->name }}</td>
                            <td>৳{{ number_format($service->price, 2) }}</td>

                            <td>
                                {{ $service->duration ? $service->duration . ' minutes' : 'Not specified' }}
                            </td>

                            <td>
                                <span class="status-badge {{ $service->status }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('organization.services.edit', $service->id) }}"
                                       class="btn btn-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('organization.services.destroy', $service->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this service?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-delete">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $services->links() }}
        </div>
    @else
        <div class="empty-state">
            <h3>No Services Added Yet</h3>
            <p>Start by adding the services your organization provides.</p>

            <a href="{{ route('organization.services.create') }}" class="btn btn-primary">
                Add Your First Service
            </a>
        </div>
    @endif
</div>
@endsection
