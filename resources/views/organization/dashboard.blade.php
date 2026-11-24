
@extends('organization.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

<style>
    .org-welcome-banner {
        background: linear-gradient(110deg, #ffffff, #f0f7ff);
        border: 1px solid #d5e3f1;
        border-left: 4px solid #3287d8;
        border-radius: 14px;
        padding: 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .org-welcome-banner h1 {
        font-size: 23px;
        color: #153b60;
        margin-bottom: 10px;
    }

    .org-welcome-banner p {
        font-size: 13px;
        color: #71869d;
    }

    .org-date {
        background: white;
        border: 1px solid #d5e3f1;
        border-radius: 12px;
        padding: 15px 22px;
        text-align: center;
        min-width: 125px;
        box-shadow: 0 3px 10px rgba(30,65,100,.05);
    }

    .org-date small {
        color: #71869d;
        display: block;
        margin-bottom: 7px;
    }

    .org-date strong {
        color: #153b60;
        font-size: 14px;
    }

    .org-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 25px;
    }

    .org-stat-card {
        background: #ffffff;
        border: 1px solid #cbdbea;
        border-radius: 13px;
        padding: 22px;
        min-height: 155px;
        box-shadow: 0 4px 12px rgba(30,65,100,.06);
    }

    .org-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        color: #405b78;
        font-size: 13px;
        font-weight: 600;
    }

    .org-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #e1effc;
        color: #2675bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .org-stat-card h2 {
        font-size: 27px;
        color: #153b60;
        margin-top: 17px;
    }

    .org-stat-card p {
        font-size: 11px;
        color: #8294a8;
        margin-top: 9px;
    }

    .org-bottom-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .org-info-card {
        background: white;
        border: 1px solid #cbdbea;
        border-radius: 13px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(30,65,100,.06);
    }

    .org-info-card h3 {
        font-size: 17px;
        color: #153b60;
        margin-bottom: 20px;
    }

    .org-info-row {
        padding: 13px 0;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        justify-content: space-between;
        gap: 15px;
        font-size: 13px;
    }

    .org-info-row:last-child {
        border-bottom: none;
    }

    .org-info-row span {
        color: #71869d;
    }

    .org-info-row strong {
        color: #244665;
        text-align: right;
    }

    .org-status {
        background: #e1f5eb;
        color: #188453;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
    }

    .org-empty-state {
        background: #f5f9fe;
        border: 1px dashed #cbdbea;
        border-radius: 10px;
        padding: 25px 15px;
        text-align: center;
        color: #8294a8;
        font-size: 13px;
    }

    @media(max-width: 1050px) {
        .org-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 650px) {
        .org-welcome-banner {
            padding: 22px;
            align-items: flex-start;
            flex-direction: column;
            gap: 20px;
        }

        .org-bottom-grid {
            grid-template-columns: 1fr;
        }

        .org-stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .org-stat-card {
            padding: 15px;
        }
    }
</style>

<!-- Welcome Banner -->
<div class="org-welcome-banner">
    <div>
        <h1>Welcome back, {{ $user->name }}!</h1>
        <p>Here's what's happening with your organization today.</p>
    </div>

    <div class="org-date">
        <small>Today</small>
        <strong>{{ now()->format('d M Y') }}</strong>
    </div>
</div>

<!-- Statistics Cards -->
<div class="org-stats-grid">

    <div class="org-stat-card">
        <div class="org-stat-top">
            <span>Products</span>
            <div class="org-stat-icon">▣</div>
        </div>
        <h2>—</h2>
        <p>Product statistics coming soon</p>
    </div>

    <div class="org-stat-card">
        <div class="org-stat-top">
            <span>Services</span>
            <div class="org-stat-icon">◇</div>
        </div>
        <h2>—</h2>
        <p>Service statistics coming soon</p>
    </div>

    <div class="org-stat-card">
        <div class="org-stat-top">
            <span>Orders</span>
            <div class="org-stat-icon">🛒</div>
        </div>
        <h2>—</h2>
        <p>Order statistics coming soon</p>
    </div>

    <div class="org-stat-card">
        <div class="org-stat-top">
            <span>Appointments</span>
            <div class="org-stat-icon">▦</div>
        </div>
        <h2>—</h2>
        <p>Appointment statistics coming soon</p>
    </div>

</div>

<!-- Bottom Sections -->
<div class="org-bottom-grid">

    <div class="org-info-card">
        <h3>Organization Information</h3>

        <div class="org-info-row">
            <span>Organization Name</span>
            <strong>{{ $organization->name }}</strong>
        </div>

        <div class="org-info-row">
            <span>Owner Name</span>
            <strong>{{ $user->name }}</strong>
        </div>

        <div class="org-info-row">
            <span>Email Address</span>
            <strong>{{ $user->email }}</strong>
        </div>

        <div class="org-info-row">
            <span>Account Status</span>
            <strong>
                <span class="org-status">
                    {{ ucfirst($organization->status) }}
                </span>
            </strong>
        </div>
    </div>

    <div class="org-info-card">
        <h3>Recent Activity</h3>

        <div class="org-empty-state">
            <p>No recent activity to display.</p>
            <p style="margin-top:8px;">Your organization activities will appear here.</p>
        </div>
    </div>

</div>

@endsection
