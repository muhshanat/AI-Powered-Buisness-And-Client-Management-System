
@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('heading', 'Create Role')

@section('content')

    <div class="page-heading">
        <h1>Create New Role</h1>
        <p>Add a role and select its permissions.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Role Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Example: Manager"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    placeholder="Describe what this role is for"
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Role Permissions</label>
                <p style="color: #64748b; margin: 8px 0 15px;">
                    Select the actions this role should be allowed to perform.
                </p>

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
                @endphp

                @foreach ($permissionGroups as $group => $items)
                    <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 15px;">
                        <h4 style="font-weight: bold; margin-bottom: 12px;">
                            {{ $group }}
                        </h4>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                            @foreach ($items as $value => $label)
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $value }}"
                                        {{ in_array($value, old('permissions', [])) ? 'checked' : '' }}
                                    >
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="action-links">
                <button type="submit" class="btn">
                    Save Role
                </button>

                <a href="{{ route('admin.roles.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

@endsection
