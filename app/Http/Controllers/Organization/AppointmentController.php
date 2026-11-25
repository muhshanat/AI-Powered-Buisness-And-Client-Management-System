<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    // Get the logged-in organization's approved account
    private function currentOrganization()
    {
        return auth('organization')->user()
            ->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }

    // Display all appointments
    public function index()
    {
        $organization = $this->currentOrganization();

        $appointments = $organization->appointments()
            ->latest('appointment_date')
            ->paginate(10);

        return view('organization.appointments.index', compact(
            'organization',
            'appointments'
        ));
    }

    // Display individual appointment details
    public function show(Appointment $appointment)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $appointment->organization_id === $organization->id,
            404
        );

        $appointment->load('service');

        return view('organization.appointments.show', compact(
            'organization',
            'appointment'
        ));
    }

    // Update appointment status
    public function update(Request $request, Appointment $appointment)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $appointment->organization_id === $organization->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled'
                ])
            ],
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('organization.appointments.show', $appointment->id)
            ->with('success', 'Appointment updated successfully.');
    }
}
