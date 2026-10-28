<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    private function availablePermissions(): array
    {
        return [
            'customers.view' => 'View Customers',
            'customers.create' => 'Add Customers',
            'customers.edit' => 'Edit Customers',
            'customers.delete' => 'Delete Customers',
            'roles.view' => 'View Roles',
            'roles.create' => 'Add Roles',
            'roles.edit' => 'Edit Roles',
            'roles.delete' => 'Delete Roles',
            'admins.view' => 'View Admins',
            'admins.create' => 'Add Admins',
            'admins.edit' => 'Edit Admins',
            'admins.delete' => 'Delete Admins',
        ];
    }

    public function index()
    {
        $roles = Role::latest()->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->availablePermissions();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $permissions = array_keys($this->availablePermissions());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($permissions)],
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role added successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = $this->availablePermissions();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $permissions = array_keys($this->availablePermissions());

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($permissions)],
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role moved to Trash.');
    }

    public function trash()
    {
        $roles = Role::onlyTrashed()->latest('deleted_at')->get();

        return view('admin.roles.trash', compact('roles'));
    }

    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return redirect()->route('admin.roles.trash')
            ->with('success', 'Role restored successfully.');
    }
}
