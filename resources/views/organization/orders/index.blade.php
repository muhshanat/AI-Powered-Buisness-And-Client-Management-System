
@extends('organization.layouts.app')

@section('title', 'Orders')
@section('heading', 'Order Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-orders.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>All Orders</h2>
            <p>Manage and track orders received by your organization.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="order-summary">
        <div class="order-summary-card">
            <span>Total Orders</span>
            <strong>{{ $orders->total() }}</strong>
        </div>
    </div>

    @if($orders->count() > 0)

        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone }}</td>
                            <td>৳{{ number_format($order->total_amount, 2) }}</td>

                            <td>
                                <span class="order-badge {{ $order->payment_status }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>

                            <td>
                                <span class="order-badge {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('organization.orders.show', $order->id) }}"
                                   class="btn btn-view">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>

    @else
        <div class="empty-state">
            <h3>No Orders Yet</h3>
            <p>Orders placed by customers will appear here.</p>
        </div>
    @endif

</div>
@endsection
