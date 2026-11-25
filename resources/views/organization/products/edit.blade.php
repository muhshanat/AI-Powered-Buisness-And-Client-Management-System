
@extends('organization.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-products.css') }}">
@endpush

@section('title', 'Edit Product')
@section('heading', 'Edit Product')

@section('content')

<div class="content-card product-form-card">

    <h2>Edit Product</h2>
    <p>Update your product information below.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('organization.products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="product-form-grid">

            <div class="form-group full-width">
                <label>Product Name</label>
                <input type="text" name="name"
                       value="{{ old('name', $product->name) }}"
                       required>
            </div>

            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Price (৳)</label>
                <input type="number" name="price"
                       value="{{ old('price', $product->price) }}"
                       step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock"
                       value="{{ old('stock', $product->stock) }}"
                       min="0" required>
            </div>

            <div class="form-group full-width">
                <label>Current Product Image</label>

                @if($product->image)
                    <div style="margin:10px 0;">
                        <img src="{{ asset('storage/'.$product->image) }}"
                             width="110" height="110"
                             style="object-fit:cover; border-radius:10px;">
                    </div>
                @else
                    <p>No image uploaded.</p>
                @endif
            </div>

            <div class="form-group full-width">
                <label>Change Product Image</label>
                <input type="file" name="image"
                       class="product-image-input"
                       accept="image/*">
            </div>

            <div class="form-group full-width">
                <label>Status</label>
                <select name="status" required>
                    <option value="active"
                        {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="inactive"
                        {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <div class="product-form-actions">

            <button type="submit" class="btn btn-primary">
                Update Product
            </button>

            <a href="{{ route('organization.products.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection
