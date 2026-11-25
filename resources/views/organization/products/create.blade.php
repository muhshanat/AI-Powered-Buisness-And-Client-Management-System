
@extends('organization.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-products.css') }}">
@endpush

@section('title', 'Add Product')
@section('heading', 'Add Product')

@section('content')

<div class="content-card product-form-card">

    <h2>Add New Product</h2>
    <p>Enter the details of your product below.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('organization.products.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="product-form-grid">

            <div class="form-group full-width">
                <label>Product Name</label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Enter product name" required>
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description"
                          rows="4"
                          placeholder="Enter product description">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Price (৳)</label>
                <input type="number" name="price"
                       value="{{ old('price') }}"
                       step="0.01" min="0"
                       placeholder="Enter price" required>
            </div>

            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock"
                       value="{{ old('stock', 0) }}"
                       min="0" required>
            </div>

            <div class="form-group full-width">
                <label>Product Image</label>
                <input type="file" name="image"
                       class="product-image-input"
                       accept="image/*">
            </div>

            <div class="form-group full-width">
                <label>Status</label>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

        </div>

        <div class="product-form-actions">

            <button type="submit" class="btn btn-primary">
                Save Product
            </button>

            <a href="{{ route('organization.products.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection
