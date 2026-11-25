
@extends('organization.layouts.app')

@section('title', 'Order Details')
@section('heading', 'Order Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-orders.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>Order #{{ $order->order_number }}</h2>
            <p>View customer information and manage this order.</p>
        </div>

        <a href="{{ route('organization.orders.index') }}" class="btn btn-view">
            Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="order-details-grid">

        <div class="order-info-card">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email ?? 'Not provided' }}</p>
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
        </div>

        <div class="order-info-card">
            <h3>Payment Information</h3>
            <p><strong>Subtotal:</strong> ৳{{ number_format($order->subtotal, 2) }}</p>
            <p><strong>Delivery Charge:</strong> ৳{{ number_format($order->delivery_charge, 2) }}</p>
            <p><strong>Total Amount:</strong> ৳{{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'Not specified' }}</p>

            <p>
                <strong>Payment Status:</strong>
                <span class="order-badge {{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
        </div>

    </div>

    <div class="order-info-card order-items-card">
        <h3>Ordered Products</h3>

        @if($order->items->count() > 0)
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                <td>৳{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No product items found for this order.</p>
        @endif
    </div>

    <div class="order-info-card order-update-card">
        <h3>Update Order Status</h3>

        <form action="{{ route('organization.orders.update', $order->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="order-form-grid">

                <div class="form-group">
                    <label for="status">Order Status</label>

                    <select name="status" id="status" required>
                        @foreach([
                            'pending',
                            'confirmed',
                            'processing',
                            'shipped',
                            'delivered',
                            'cancelled'
                        ] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status</label>

                    <select name="payment_status" id="payment_status" required>
                        @foreach([
                            'unpaid',
                            'paid',
                            'failed',
                            'refunded'
                        ] as $paymentStatus)
                            <option value="{{ $paymentStatus }}"
                                {{ old('payment_status', $order->payment_status) === $paymentStatus ? 'selected' : '' }}>
                                {{ ucfirst($paymentStatus) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            @if($errors->any())
                <div class="error-alert">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="btn btn-view">
                Update Order
            </button>

        </form>
    </div>

</div>
@endsection
