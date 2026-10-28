
@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>Edit Role</h1>
    <p>Update role details and permissions.</p>
</div>

<div class="content-card">
    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Role Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $role->name) }}"
                required
            >
            @error('name')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="3"
            >{{ old('description', $role->description) }}</textarea>
            @error('description')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <h3 style="margin: 25px 0 15px;">Role Permissions</h3>

        @php
            $permissionGroups = [
                'Customers' => [
                    'customers.view' => 'View Customers',
                    'customers.create' => 'Add Customers',
                    'customers.edit' => 'Edit Customers',
                    'customers.delete' => 'Delete Customers',
                ],
                'Roles' => [
                    'roles.view' => 'View Roles',
                    'roles.create' => 'Add Roles',
                    'roles.edit' => 'Edit Roles',
                    'roles.delete' => 'Delete Roles',
                ],
                'Admins' => [
                    'admins.view' => 'View Admins',
                    'admins.create' => 'Add Admins',
                    'admins.edit' => 'Edit Admins',
                    'admins.delete' => 'Delete Admins',
                ],
            ];

            $selectedPermissions = old(
                'permissions',
                $role->permissions ?? []
            );
        @endphp

        @foreach ($permissionGroups as $group => $items)
            <div style="border: 1px solid #e2e8f0; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                <h4 style="font-weight: bold; margin-bottom: 12px;">
                    {{ $group }}
                </h4>

                @foreach ($items as $value => $label)
                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $value }}"
                            {{ in_array($value, $selectedPermissions) ? 'checked' : '' }}
                        >
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        @endforeach

        @error('permissions')
            <small style="color: red;">{{ $message }}</small>
        @enderror

        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
