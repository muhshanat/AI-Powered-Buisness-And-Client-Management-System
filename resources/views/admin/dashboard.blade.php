
@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('heading', 'Dashboard')
@section('page_description', 'Monitor your application and administrative activities.')

@section('content')

@php
    use App\Models\User;
    use App\Models\Role;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    $user = auth()->user();
    $isSuperAdmin = $user->role === 'super_admin';

    // Permissions
    $role = Role::where('name', $user->role)->first();
    $permissions = $role->permissions ?? [];

    if ($isSuperAdmin) {
        $permissions = [
            'customers.view',
            'roles.view',
            'admins.view',
        ];
    }

    $canViewCustomers = in_array('customers.view', $permissions);
    $canViewRoles = in_array('roles.view', $permissions);
    $canViewAdmins = in_array('admins.view', $permissions);

    // Customer query: exclude administrator accounts.
    $customerQuery = User::query()->where(function ($query) {
        $query->whereNull('role')
              ->orWhereNotIn('role', ['Admin', 'super_admin']);
    });

    $totalCustomers = (clone $customerQuery)->count();

    $newCustomers = (clone $customerQuery)
        ->whereBetween('created_at', [
            now()->copy()->startOfMonth(),
            now()->copy()->endOfMonth()
        ])
        ->count();

    // Admin accounts, including Super Admin.
    $totalAdmins = User::whereIn('role', ['Admin', 'super_admin'])->count();

    // Active roles.
    $totalRoles = Role::count();

    // Active sessions (only when Laravel's database sessions table exists).
    $activeSessions = 0;

    if (Schema::hasTable('sessions')) {
        $activeSessions = DB::table('sessions')
            ->where(
                'last_activity',
                '>=',
                now()->subMinutes(config('session.lifetime'))->timestamp
            )
            ->count();
    }

    // Recent customers.
    $recentCustomers = (clone $customerQuery)
        ->latest()
        ->take(5)
        ->get(['name', 'email', 'created_at']);

    // Customer growth over the last six months.
    $growthLabels = [];
    $growthData = [];

    for ($i = 5; $i >= 0; $i--) {
        $month = now()->copy()->startOfMonth()->subMonths($i);

        $growthLabels[] = $month->format('M');

        $growthData[] = (clone $customerQuery)
            ->whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])
            ->count();
    }

    // Authenticator status.
    $twoFactorEnabled = (bool) $user->google2fa_enabled;
@endphp


{{-- WELCOME SECTION --}}
<div class="dashboard-welcome">
    <div>
        <h2>Welcome back, {{ $user->name }}!</h2>
        <p>Here's what's happening with your application today.</p>
    </div>

    <div class="dashboard-date">
        <span class="date-label">Today</span>
        <strong>{{ now()->format('d M Y') }}</strong>
    </div>
</div>


{{-- STATISTICS CARDS --}}
<div class="dashboard-stats">

    @if ($canViewCustomers)
        <div class="dashboard-stat-card">
            <div class="dashboard-stat-top">
                <div>
                    <p>Total Customers</p>
                    <h3>{{ number_format($totalCustomers) }}</h3>
                </div>

                <div class="dashboard-stat-icon icon-blue">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.customers.index') }}" class="stat-link">
                View customers <span>→</span>
            </a>
        </div>

        <div class="dashboard-stat-card">
            <div class="dashboard-stat-top">
                <div>
                    <p>New Customers This Month</p>
                    <h3>{{ number_format($newCustomers) }}</h3>
                </div>

                <div class="dashboard-stat-icon icon-green">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>
            </div>

            <span class="stat-note">Registered this month</span>
        </div>
    @endif

    @if ($canViewAdmins)
        <div class="dashboard-stat-card">
            <div class="dashboard-stat-top">
                <div>
                    <p>Total Admins</p>
                    <h3>{{ number_format($totalAdmins) }}</h3>
                </div>

                <div class="dashboard-stat-icon icon-purple">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.admins.index') }}" class="stat-link">
                Manage admins <span>→</span>
            </a>
        </div>
    @endif

    @if ($canViewRoles)
        <div class="dashboard-stat-card">
            <div class="dashboard-stat-top">
                <div>
                    <p>Total Roles</p>
                    <h3>{{ number_format($totalRoles) }}</h3>
                </div>

                <div class="dashboard-stat-icon icon-orange">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.roles.index') }}" class="stat-link">
                Manage roles <span>→</span>
            </a>
        </div>
    @endif

    @if ($isSuperAdmin)
        <div class="dashboard-stat-card">
            <div class="dashboard-stat-top">
                <div>
                    <p>Active Sessions</p>
                    <h3>{{ number_format($activeSessions) }}</h3>
                </div>

                <div class="dashboard-stat-icon icon-slate">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
            </div>

            <span class="stat-note">Current database sessions</span>
        </div>
    @endif

</div>


{{-- CHARTS SECTION --}}
<div class="dashboard-grid">

    {{-- CUSTOMER GROWTH CHART --}}
    <div class="dashboard-panel growth-panel">

        <div class="panel-heading">
            <div>
                <h3>Customer Growth</h3>
                <p>New customer registrations over the last six months</p>
            </div>

            <span class="panel-tag">Last 6 months</span>
        </div>

        <div class="chart-container">
            <canvas id="customerGrowthChart"></canvas>
        </div>

    </div>


    {{-- SYSTEM OVERVIEW --}}
    <div class="dashboard-panel overview-panel">

        <div class="panel-heading">
            <div>
                <h3>System Overview</h3>
                <p>Account distribution</p>
            </div>
        </div>

        <div class="overview-chart">
            <canvas id="systemOverviewChart"></canvas>
        </div>

        <div class="chart-legend">
            <div>
                <span class="legend-dot customer-dot"></span>
                Customers
                <strong>{{ number_format($totalCustomers) }}</strong>
            </div>

            <div>
                <span class="legend-dot admin-dot"></span>
                Admin Accounts
                <strong>{{ number_format($totalAdmins) }}</strong>
            </div>
        </div>

    </div>

</div>


{{-- LOWER DASHBOARD SECTION --}}
<div class="dashboard-grid lower-grid">

    {{-- RECENT CUSTOMERS --}}
    @if ($canViewCustomers)
        <div class="dashboard-panel recent-panel">

            <div class="panel-heading">
                <div>
                    <h3>Recent Customers</h3>
                    <p>Latest registered customer accounts</p>
                </div>

                <a href="{{ route('admin.customers.index') }}" class="panel-action">
                    View all →
                </a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Registered</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentCustomers as $customer)
                            <tr>
                                <td>
                                    <div class="customer-cell">
                                        <span class="customer-avatar">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>

                                        <strong>{{ $customer->name }}</strong>
                                    </div>
                                </td>

                                <td>{{ $customer->email }}</td>

                                <td>
                                    {{ $customer->created_at?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-table">
                                    No customers registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @endif


    {{-- RECENT ACTIVITY --}}
    <div class="dashboard-panel activity-panel">

        <div class="panel-heading">
            <div>
                <h3>Recent Activity</h3>
                <p>Latest administrative actions</p>
            </div>
        </div>

        <div class="activity-empty">
            <div class="activity-empty-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12 8v4l3 2"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <strong>Activity timeline</strong>

            <p>
                Activity records will appear here when the activity log
                module is connected to the dashboard.
            </p>

        </div>

    </div>

</div>


{{-- QUICK ACTIONS --}}
<div class="dashboard-panel quick-actions-panel">

    <div class="panel-heading">
        <div>
            <h3>Quick Actions</h3>
            <p>Shortcuts to frequently used modules</p>
        </div>
    </div>

    <div class="quick-actions">

        @if ($canViewCustomers)
            <a href="{{ route('admin.customers.create') }}" class="quick-action">
                <span class="quick-action-icon icon-blue">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </span>
                <span>Add Customer</span>
            </a>
        @endif

        @if ($canViewAdmins)
            <a href="{{ route('admin.admins.create') }}" class="quick-action">
                <span class="quick-action-icon icon-purple">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </span>
                <span>Add Admin</span>
            </a>
        @endif

        @if ($canViewRoles)
            <a href="{{ route('admin.roles.create') }}" class="quick-action">
                <span class="quick-action-icon icon-green">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                        <path d="M12 8v8M8 12h8"/>
                    </svg>
                </span>
                <span>Create Role</span>
            </a>
        @endif

    </div>

</div>


{{-- SECURITY STATUS --}}
@if ($isSuperAdmin)
    <div class="security-status">
        <div class="security-status-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>

        <div>
            <h3>
                {{ $twoFactorEnabled ? 'Two-Factor Authentication Enabled' : 'Two-Factor Authentication Not Enabled' }}
            </h3>

            <p>
                @if ($twoFactorEnabled)
                    Your Super Admin account is protected by Google Authenticator.
                @else
                    Set up Google Authenticator to add another layer of security.
                @endif
            </p>
        </div>
    </div>
@endif


{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const growthLabels = @json($growthLabels);
    const growthData = @json($growthData);

    // Customer Growth Line Chart
    const growthCanvas = document.getElementById('customerGrowthChart');

    if (growthCanvas) {
        new Chart(growthCanvas, {
            type: 'line',

            data: {
                labels: growthLabels,

               datasets: [{
    label: 'New Customers',
    data: growthData,

    borderColor: '#1685E5',
    backgroundColor: 'rgba(22, 133, 229, 0.18)',

    borderWidth: 3,
    fill: true,
    tension: 0.4,

    pointBackgroundColor: '#1685E5',
    pointBorderColor: '#FFFFFF',
    pointBorderWidth: 3,
    pointRadius: 5,
    pointHoverRadius: 8
}]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#edf1f5'
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }


    // System Overview Doughnut Chart
    const overviewCanvas = document.getElementById('systemOverviewChart');

    if (overviewCanvas) {
        new Chart(overviewCanvas, {
            type: 'doughnut',

            data: {
                labels: ['Customers', 'Admin Accounts'],

               datasets: [{
    data: [
        {{ $totalCustomers }},
        {{ $totalAdmins }}
    ],

   backgroundColor: [
    '#2878D0',
    '#79C5E8'
],

hoverBackgroundColor: [
    '#1D5FAE',
    '#55ADD9'
],

borderColor: '#FFFFFF',
borderWidth: 4,
hoverOffset: 7
}]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',

                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

});
</script>

@endsection
