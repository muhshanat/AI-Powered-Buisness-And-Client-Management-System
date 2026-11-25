
@extends('organization.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-products.css') }}">
@endpush

@section('title', 'Products')
@section('heading', 'Products')

@section('content')

<div class="content-card">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <div>
            <h2>Product Management</h2>
            <p>Manage your organization's products.</p>
        </div>

        <a href="{{ route('organization.products.create') }}"
           class="btn btn-primary">
            + Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     width="50" height="50"
                                     style="object-fit:cover; border-radius:8px;">
                            @else
                                No image
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>

                        <td>৳{{ number_format($product->price, 2) }}</td>

                        <td>{{ $product->stock }}</td>

                        <td>{{ ucfirst($product->status) }}</td>

                        <td>
                            <a href="{{ route('organization.products.edit', $product->id) }}">
                                Edit
                            </a>

                            <form action="{{ route('organization.products.destroy', $product->id) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px;">
                            No products added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $products->links() }}
    </div>

</div>

@endsection
