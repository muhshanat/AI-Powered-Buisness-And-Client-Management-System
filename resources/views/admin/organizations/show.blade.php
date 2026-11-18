
@extends('admin.layouts.app')

@section('title', 'Organization Details')
@section('heading', 'Organization Details')

@section('content')

<style>
    .org-profile {
        display: grid;
        gap: 20px;
    }

    .org-card {
        background: #ffffff;
        border: 1px solid #dce5ef;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(30, 55, 90, 0.05);
    }

    /* Organization header */
    .org-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
    }

    .org-heading-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .org-title {
        font-size: 22px;
        font-weight: 700;
        color: #183b60;
        margin: 0;
    }

    .org-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .org-subtitle {
        color: #718096;
        font-size: 13px;
    }

    /* Organization status */
    .org-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        background: #fff4d8;
        color: #986500;
    }

    .org-status.approved {
        background: #e2f6eb;
        color: #187545;
    }

    .org-status.rejected,
    .org-status.suspended {
        background: #fde8e8;
        color: #b42318;
    }

    /* Header action links: icon and text only */
    .org-header-actions {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-end;
        gap: 25px;
        margin-left: auto;
    }

    .org-header-actions a.org-text-action {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center;
        gap: 8px;
        width: auto !important;
        padding: 8px 0 !important;
        margin: 0 !important;
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: #17639b !important;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none !important;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .org-header-actions a.org-text-action svg {
        display: block !important;
        width: 19px !important;
        height: 19px !important;
        min-width: 19px !important;
        max-width: 19px !important;
        min-height: 19px !important;
        max-height: 19px !important;
        flex: 0 0 19px !important;
        fill: none !important;
        stroke: currentColor !important;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        margin: 0 !important;
    }

    .org-header-actions a.org-text-action:hover {
        color: #168b91 !important;
        background: transparent !important;
        text-decoration: none !important;
    }

    /* Organization and owner information layout */
    .org-details-layout {
        display: grid;
        grid-template-columns: 0.8fr 1.5fr;
        gap: 35px;
    }

    .owner-info-section {
        padding-right: 35px;
        border-right: 2px solid #e2eaf2;
    }

    .org-info-section {
        padding-left: 5px;
    }

    /* Section headings */
    .org-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 17px;
        font-weight: 700;
        color: #183b60;
        margin: 0 0 22px;
        padding-bottom: 13px;
        border-bottom: 1px solid #e5edf5;
    }

    /* Information icons */
    .org-icon {
        width: 18px;
        height: 18px;
        stroke: #168b91;
        stroke-width: 1.8;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
        vertical-align: middle;
    }

    /* Information fields */
    .org-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .org-field {
        min-width: 0;
    }

    .org-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #718096;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .org-label .org-icon {
        width: 16px;
        height: 16px;
    }

    .org-value {
        color: #243b53;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .org-value a {
        color: #17639b;
        text-decoration: none;
    }

    .org-value a:hover {
        text-decoration: underline;
    }

    /* Description */
    .org-description {
        background: #f5f8fc;
        border-radius: 9px;
        padding: 14px;
        color: #43566c;
        line-height: 1.6;
        font-size: 14px;
    }

    /* Owner profile */
    .org-owner {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 25px;
    }

    .org-avatar {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #e4eff8;
        color: #17639b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
    }

    /* Owner account status */
    .owner-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .owner-status.active {
        color: #187545;
        background: #e5f6ed;
    }

    .owner-status.inactive {
        color: #b42318;
        background: #fdeaea;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Staff table */
    .org-staff-table {
        width: 100%;
        border-collapse: collapse;
    }

    .org-staff-table th,
    .org-staff-table td {
        padding: 13px;
        text-align: left;
        border-bottom: 1px solid #e5edf5;
        font-size: 13px;
    }

    .org-staff-table th {
        color: #52677e;
        background: #f1f5fa;
    }

    /* Empty state */
    .org-empty {
        background: #f5f8fc;
        border-radius: 9px;
        padding: 18px;
        color: #718096;
        font-size: 14px;
        text-align: center;
    }

    /* Responsive layout */
    @media (max-width: 800px) {
        .org-details-layout {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .owner-info-section {
            padding-right: 0;
            padding-bottom: 25px;
            border-right: none;
            border-bottom: 2px solid #e2eaf2;
        }

        .org-info-section {
            padding-left: 0;
        }
    }

    @media (max-width: 600px) {
        .org-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .org-header-actions {
            justify-content: flex-start;
            margin-left: 0;
            flex-wrap: wrap;
            gap: 18px;
        }
    }

    @media (max-width: 550px) {
        .org-grid {
            grid-template-columns: 1fr;
        }

        .org-card {
            padding: 18px;
        }

        .org-title {
            font-size: 19px;
        }
    }
</style>


<div class="org-profile">

   
{{-- Organization Header --}}
<div class="org-card">

    <div class="org-header">

        {{-- LEFT: Organization Name, ID and Status --}}
        <div class="org-heading-info">

            <h2 class="org-title">
                {{ $organization->name }}
            </h2>

            <div class="org-meta">

                <span class="org-subtitle">
                    Organization Profile | 
                </span>

                <span class="org-status {{ $organization->status }}">
                    {{ ucfirst($organization->status) }}
                </span>

            </div>

        </div>

        {{-- RIGHT: Action Buttons --}}
        <div class="org-header-actions">

    <a href="{{ route('admin.organizations.index') }}"
       class="org-text-action">

        <svg viewBox="0 0 24 24">
            <path d="M19 12H5"/>
            <path d="m12 19-7-7 7-7"/>
        </svg>

        Back
    </a>

    <a href="{{ route('admin.organizations.edit', $organization->id) }}"
       class="org-text-action">

        <svg viewBox="0 0 24 24">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
        </svg>

        Edit Organization
    </a>

</div>

    </div>

</div>


    {{-- Organization and Owner Information --}}
    <div class="org-card">

        <div class="org-details-layout">

            {{-- LEFT SIDE: Owner Information --}}
            <div class="owner-info-section">

                <h3 class="org-section-title">

                    <svg class="org-icon" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>

                    Owner Information
                </h3>

                @if ($owner)

                    {{-- Owner Profile --}}
                    <div class="org-owner">

                        <div class="org-avatar">
                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </div>

                        <div>
                            <div class="org-value">
                                {{ $owner->name }}
                            </div>

                            <div class="org-subtitle">
                                Organization Owner
                            </div>
                        </div>

                    </div>


                    {{-- Owner Email --}}
                    <div class="org-field" style="margin-bottom: 23px;">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>

                            Email Address
                        </span>

                        <div class="org-value">
                            {{ $owner->email }}
                        </div>

                    </div>


                    {{-- Account Status --}}
                    <div class="org-field">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m8 12 3 3 5-6"/>
                            </svg>

                            Account Status
                        </span>

                        <div>
                            @if ($owner->pivot->is_active)

                                <span class="owner-status active">
                                    <span class="status-dot"></span>
                                    Active
                                </span>

                            @else

                                <span class="owner-status inactive">
                                    <span class="status-dot"></span>
                                    Inactive
                                </span>

                            @endif
                        </div>

                    </div>

                @else

                    <div class="org-empty">
                        No owner account found.
                    </div>

                @endif

            </div>


            {{-- RIGHT SIDE: Organization Profile --}}
            <div class="org-info-section">

                <h3 class="org-section-title">

                    <svg class="org-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M9 21V9h6v12"/>
                        <path d="M9 13h6"/>
                        <path d="M9 17h6"/>
                    </svg>

                    Organization Profile
                </h3>


                <div class="org-grid">

                    {{-- Contact Email --}}
                    <div class="org-field">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m3 7 9 6 9-6"/>
                            </svg>

                            Contact Email
                        </span>

                        <div class="org-value">
                            {{ $organization->email }}
                        </div>

                    </div>


                    {{-- Phone --}}
                    <div class="org-field">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.09 4.18 2 2 0 0 1 4.08 2h3a2 2 0 0 1 2 1.72"/>
                            </svg>

                            Phone Number
                        </span>

                        <div class="org-value">
                            {{ $organization->phone ?? 'N/A' }}
                        </div>

                    </div>


                    {{-- Website --}}
                    <div class="org-field">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M2 12h20"/>
                                <path d="M12 2a15 15 0 0 1 0 20"/>
                                <path d="M12 2a15 15 0 0 0 0 20"/>
                            </svg>

                            Website
                        </span>

                        <div class="org-value">

                            @if ($organization->website)

                                <a href="{{ $organization->website }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Visit Website
                                </a>

                            @else
                                N/A
                            @endif

                        </div>

                    </div>


                    {{-- Registered On --}}
                    <div class="org-field">

                        <span class="org-label">
                            <svg class="org-icon" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>

                            Registered On
                        </span>

                        <div class="org-value">
                            {{ $organization->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>

                </div>


                {{-- Address --}}
                <div style="margin-top: 25px;">

                    <span class="org-label">
                        <svg class="org-icon" viewBox="0 0 24 24">
                            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>

                        Address
                    </span>

                    <div class="org-value">
                        {{ $organization->address ?? 'N/A' }}
                    </div>

                </div>


                {{-- Description --}}
                <div style="margin-top: 22px;">

                    <span class="org-label">
                        <svg class="org-icon" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                            <path d="M14 2v6h6M8 13h8M8 17h8"/>
                        </svg>

                        Description
                    </span>

                    <div class="org-description">
                        {{ $organization->description ?? 'No description available.' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Staff Members --}}
    <div class="org-card">

        <h3 class="org-section-title">

            <svg class="org-icon" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>

            Staff Members

        </h3>


        @if ($staff->count() > 0)

            <div style="overflow-x: auto;">

                <table class="org-staff-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($staff as $member)

                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>

                                <td>
                                    {{ ucfirst($member->pivot->organization_role) }}
                                </td>

                                <td>
                                    {{ $member->pivot->is_active ? 'Active' : 'Inactive' }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="org-empty">
                No staff members have been added to this organization yet.
            </div>

        @endif

    </div>

</div>

@endsection
