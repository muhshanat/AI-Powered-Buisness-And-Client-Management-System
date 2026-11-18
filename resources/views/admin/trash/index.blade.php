
@extends('admin.layouts.app')

@section('title', 'Trash Management')
@section('heading', 'Trash Management')
@section('page_description', 'View and restore deleted records.')

@section('content')

<div class="trash-dashboard">

    {{-- Tab Buttons --}}
    <div class="trash-tabs">
        @if ($canViewOrganizationTrash)
            <button type="button" class="trash-tab"
                    data-tab="organizations">
                Organizations
                <span>{{ $organizations->count() }}</span>
            </button>
        @endif

        @if ($canViewRoleTrash)
            <button type="button" class="trash-tab"
                    data-tab="roles">
                Roles
                <span>{{ $roles->count() }}</span>
            </button>
        @endif

        @if ($canViewAdminTrash)
            <button type="button" class="trash-tab"
                    data-tab="admins">
                Admins
                <span>{{ $admins->count() }}</span>
            </button>
        @endif
    </div>

    {{-- Organizations Table --}}
    @if ($canViewOrganizationTrash)
    <section class="trash-panel" id="organizations-panel">
        <div class="content-card">
            <div class="trash-panel-heading">
                <div>
                    <h2>Organizations Trash</h2>
                    <p>Deleted organization accounts</p>
                </div>
                <span class="trash-count">{{ $organizations->count() }} records</span>
            </div>

            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizations as $organization)
                            <tr>
                                <td>{{ $organization->id }}</td>
                                <td>{{ $organization->name }}</td>
                                <td>{{ $organization->email }}</td>
                                <td>{{ $organization->deleted_at }}</td>
                                <td>
                                    <form action="{{ route('admin.organizations.restore', $organization->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Restore this organization?')">
                                        @csrf
                                        <button type="submit" class="btn">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No deleted organizations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- Roles Table --}}
    @if ($canViewRoleTrash)
    <section class="trash-panel" id="roles-panel">
        <div class="content-card">
            <div class="trash-panel-heading">
                <div>
                    <h2>Roles Trash</h2>
                    <p>Deleted roles and permissions</p>
                </div>
                <span class="trash-count">{{ $roles->count() }} records</span>
            </div>

            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description ?: 'No description' }}</td>
                                <td>{{ $role->deleted_at }}</td>
                                <td>
                                    <form action="{{ route('admin.roles.restore', $role->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Restore this role?')">
                                        @csrf
                                        <button type="submit" class="btn">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No deleted roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- Admins Table --}}
    @if ($canViewAdminTrash)
    <section class="trash-panel" id="admins-panel">
        <div class="content-card">
            <div class="trash-panel-heading">
                <div>
                    <h2>Admins Trash</h2>
                    <p>Deleted administrator accounts</p>
                </div>
                <span class="trash-count">{{ $admins->count() }} records</span>
            </div>

            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->deleted_at }}</td>
                                <td>
                                    <form action="{{ route('admin.admins.restore', $admin->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Restore this admin?')">
                                        @csrf
                                        <button type="submit" class="btn">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No deleted admins found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.trash-tab');
    const panels = document.querySelectorAll('.trash-panel');

    function showTab(tabName) {
        const selectedPanel = document.getElementById(tabName + '-panel');

        if (!selectedPanel) {
            const firstTab = tabs[0];
            if (firstTab) showTab(firstTab.dataset.tab);
            return;
        }

        tabs.forEach(tab => {
            const isActive = tab.dataset.tab === tabName;
            tab.classList.toggle('active', isActive);
            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        panels.forEach(panel => {
            panel.classList.toggle(
                'active',
                panel.id === tabName + '-panel'
            );
        });

        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            showTab(this.dataset.tab);
        });
    });

    const requestedTab = new URLSearchParams(window.location.search).get('tab');
    const validTab = document.getElementById(requestedTab + '-panel')
        ? requestedTab
        : (tabs[0]?.dataset.tab);

    if (validTab) showTab(validTab);
});
</script>

@endsection
