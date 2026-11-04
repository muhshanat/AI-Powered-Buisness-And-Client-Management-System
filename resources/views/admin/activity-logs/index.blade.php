
@extends('admin.layouts.app')

@section('title', 'Activity Logs')
@section('heading', 'Activity Logs')
@section('page_description', 'Monitor and review administrative activities.')

@section('content')

{{-- FILTERS --}}
<div class="content-card">

    <form method="GET" action="{{ route('admin.activity-logs.index') }}">

        <div style="display:flex; flex-wrap:wrap; gap:15px; align-items:end;">

            <div class="form-group" style="flex:2; min-width:200px; margin:0;">
                <label for="search">Search Activities</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="form-control"
                    placeholder="Search user, action, module or IP..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group" style="flex:1; min-width:140px; margin:0;">
                <label for="module">Module</label>
                <select name="module" id="module" class="form-control">
                    <option value="">All Modules</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module }}"
                            {{ request('module') === $module ? 'selected' : '' }}>
                            {{ $module }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="flex:1; min-width:140px; margin:0;">
                <label for="action">Action</label>
                <select name="action" id="action" class="form-control">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}"
                            {{ request('action') === $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="flex:1; min-width:140px; margin:0;">
                <label for="date_from">From Date</label>
                <input
                    type="date"
                    name="date_from"
                    id="date_from"
                    class="form-control"
                    value="{{ request('date_from') }}"
                >
            </div>

            <div class="form-group" style="flex:1; min-width:140px; margin:0;">
                <label for="date_to">To Date</label>
                <input
                    type="date"
                    name="date_to"
                    id="date_to"
                    class="form-control"
                    value="{{ request('date_to') }}"
                >
            </div>

            <div class="action-links">
                <button type="submit" class="btn">
                    Search
                </button>

                <a href="{{ route('admin.activity-logs.index') }}"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </div>

    </form>

</div>


{{-- ACTIVITY LOGS TABLE --}}
<div class="content-card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">

        <div>
            <h3>All Activities</h3>
            <p style="font-size:12px; color:#8190a0; margin-top:5px;">
                Total matching records: {{ $logs->total() }}
            </p>
        </div>

        <span style="background:#e6f2fc; color:#247fc1; padding:7px 12px; border-radius:8px; font-size:12px; font-weight:600;">
            {{ $logs->count() }} Records on this page
        </span>

    </div>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($logs as $log)

                    <tr>
                        <td>{{ $logs->firstItem() + $loop->index }}</td>

                        <td style="white-space:nowrap;">
                            {{ $log->created_at?->format('d M Y') }}
                            <br>
                            <small style="color:#8190a0;">
                                {{ $log->created_at?->format('h:i A') }}
                            </small>
                        </td>

                        <td>
                            <strong>{{ $log->user_name ?: 'Unknown User' }}</strong>
                        </td>

                        <td>
                            <span style="background:#e6f2fc; color:#247fc1; padding:5px 9px; border-radius:6px; font-size:11px; font-weight:600;">
                                {{ $log->action }}
                            </span>
                        </td>

                        <td>{{ $log->module }}</td>

                        <td style="min-width:180px; max-width:280px; white-space:normal;">
                            {{ $log->description ?: 'No description' }}
                        </td>

                        <td>
                            {{ $log->ip_address ?: 'Not available' }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" style="text-align:center; padding:35px;">
                            <strong>No activity records found.</strong>
                            <p style="color:#8190a0; font-size:12px; margin-top:6px;">
                                Activities will appear here when your application records them.
                            </p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    @if ($logs->hasPages())
        <div style="margin-top:22px;">
            {{ $logs->links('vendor.pagination.admin') }}
        </div>
    @endif

</div>

@endsection
