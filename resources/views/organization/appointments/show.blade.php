
@extends('organization.layouts.app')

@section('title', 'Appointment Details')
@section('heading', 'Appointment Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-appointments.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>Booking #{{ $appointment->appointment_number }}</h2>
            <p>View customer information and manage this appointment.</p>
        </div>

        <a href="{{ route('organization.appointments.index') }}" class="btn btn-view">
            Back to Appointments
        </a>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="appointment-details-grid">

        <div class="appointment-info-card">
            <h3>Customer Information</h3>

            <p><strong>Name:</strong> {{ $appointment->customer_name }}</p>
            <p><strong>Email:</strong> {{ $appointment->customer_email ?? 'Not provided' }}</p>
            <p><strong>Phone:</strong> {{ $appointment->customer_phone }}</p>
        </div>

        <div class="appointment-info-card">
            <h3>Service Information</h3>

            <p><strong>Service:</strong> {{ $appointment->service_name }}</p>
            <p><strong>Price:</strong> ৳{{ number_format($appointment->service_price, 2) }}</p>
            <p><strong>Booking Number:</strong> {{ $appointment->appointment_number }}</p>
        </div>

        <div class="appointment-info-card">
            <h3>Schedule Information</h3>

            <p>
                <strong>Date:</strong>
                {{ $appointment->appointment_date->format('d M Y') }}
            </p>

            <p>
                <strong>Start Time:</strong>
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
            </p>

            <p>
                <strong>End Time:</strong>
                {{ $appointment->end_time
                    ? \Carbon\Carbon::parse($appointment->end_time)->format('h:i A')
                    : 'Not specified' }}
            </p>

            <p>
                <strong>Current Status:</strong>
                <span class="appointment-badge {{ $appointment->status }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </p>
        </div>

        <div class="appointment-info-card">
            <h3>Additional Notes</h3>

            <p>{{ $appointment->notes ?: 'No additional notes provided.' }}</p>
        </div>

    </div>

    <div class="appointment-info-card appointment-update-card">
        <h3>Update Appointment Status</h3>

        <form action="{{ route('organization.appointments.update', $appointment->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Appointment Status</label>

                <select name="status" id="status" required>
                    @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $appointment->status) === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($errors->any())
                <div class="error-alert">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="btn btn-view">
                Update Appointment
            </button>

        </form>
    </div>

</div>
@endsection
