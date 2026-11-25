
@extends('organization.layouts.app')

@section('title', 'Appointments')
@section('heading', 'Appointment Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-appointments.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>All Appointments</h2>
            <p>Manage and track customer bookings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="appointment-summary">
        <div class="appointment-summary-card">
            <span>Total Appointments</span>
            <strong>{{ $appointments->total() }}</strong>
        </div>
    </div>

    @if($appointments->count() > 0)

        <div class="table-responsive">
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Booking Number</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_number }}</td>
                            <td>{{ $appointment->customer_name }}</td>
                            <td>{{ $appointment->service_name }}</td>

                            <td>
                                {{ $appointment->appointment_date->format('d M Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                            </td>

                            <td>
                                <span class="appointment-badge {{ $appointment->status }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('organization.appointments.show', $appointment->id) }}"
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
            {{ $appointments->links() }}
        </div>

    @else
        <div class="empty-state">
            <h3>No Appointments Yet</h3>
            <p>Customer bookings will appear here when appointments are made.</p>
        </div>
    @endif

</div>
@endsection
