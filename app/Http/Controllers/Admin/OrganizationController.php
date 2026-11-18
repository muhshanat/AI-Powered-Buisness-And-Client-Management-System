<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class OrganizationController extends Controller
{
    // Record an activity
    private function logActivity($action, $description)
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Unknown User',
            'action' => $action,
            'module' => 'organizations',
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    // Show all organizations
    public function index()
{
    $organizations = Organization::with('users')
        ->latest()
        ->get();

    return view('admin.organizations.index', compact('organizations'));
}

    // Show add organization form
    public function create()
    {
        return view('admin.organizations.create');
    }

    // Save new organization
    
public function store(Request $request)
{
    $validated = $request->validate([
        'organization_name' => ['required', 'string', 'max:255'],
        'organization_email' => ['required', 'email', 'max:255', 'unique:organizations,email'],
        'phone' => ['nullable', 'string', 'max:30'],
        'website' => ['nullable', 'url', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'description' => ['nullable', 'string'],

        'owner_name' => ['required', 'string', 'max:255'],
        'owner_email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {

        // Create organization
        $organization = \App\Models\Organization::create([
            'name' => $validated['organization_name'],
            'email' => $validated['organization_email'],
            'phone' => $validated['phone'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'approved',
        ]);

        // Create owner account
        $owner = new User();
        $owner->name = $validated['owner_name'];
        $owner->email = $validated['owner_email'];
        $owner->password = Hash::make($validated['password']);
        $owner->role = 'organization';
        $owner->save();

        // Connect owner to organization
        $organization->users()->attach($owner->id, [
            'organization_role' => 'owner',
            'is_active' => true,
        ]);

    });

    $this->logActivity(
        'Created',
        'Created a new organization: ' . $validated['organization_name']
    );

    return redirect()
        ->route('admin.organizations.index')
        ->with('success', 'Organization created successfully.');
}


    // Show edit organization form
    public function edit(Organization $organization)
{
    return view('admin.organizations.edit', compact('organization'));
}

    // Update organization
    
public function update(Request $request, Organization $organization)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('organizations', 'email')->ignore($organization->id),
        ],
        'phone' => ['nullable', 'string', 'max:30'],
        'website' => ['nullable', 'url', 'max:255'],
        'address' => ['nullable', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'status' => ['required', 'in:pending,approved,rejected,suspended'],
    ]);

    $organization->update($validated);

    $this->logActivity(
        'Updated',
        'Updated organization: ' . $organization->name
    );

    return redirect()
        ->route('admin.organizations.index')
        ->with('success', 'Organization updated successfully.');
}

    // Soft delete organization
    public function destroy(Organization $organization)
{
    $organizationName = $organization->name;
    $organizationEmail = $organization->email;

    $organization->delete();

    $this->logActivity(
        'Deleted',
        'Moved organization to Trash: ' . $organizationName . ' (' . $organizationEmail . ')'
    );

    return redirect()
        ->route('admin.organizations.index')
        ->with('success', 'Organization moved to Trash.');
}

    // Show deleted organizations
    public function trash()
    {
        $organizations = User::onlyTrashed()
            ->where('role', 'organization')
            ->latest('deleted_at')
            ->get();

        return view('admin.organizations.trash', compact('organizations'));
    }

// Show organization details
    public function show(Organization $organization)
{
    $organization->load('users');

    $owner = $organization->users->firstWhere(
        'pivot.organization_role',
        'owner'
    );

    $staff = $organization->users->where(
        'pivot.organization_role',
        'staff'
    );

    return view('admin.organizations.show', compact(
        'organization',
        'owner',
        'staff'
    ));
}

    // Restore deleted organization
   public function restore($id)
{
    $organization = Organization::onlyTrashed()->findOrFail($id);

    $organizationName = $organization->name;
    $organizationEmail = $organization->email;

    $organization->restore();

    $this->logActivity(
        'Restored',
        'Restored organization: ' . $organizationName . ' (' . $organizationEmail . ')'
    );

    return redirect()
        ->route('admin.trash.index', ['tab' => 'organizations'])
        ->with('success', 'Organization restored successfully.');
}


}