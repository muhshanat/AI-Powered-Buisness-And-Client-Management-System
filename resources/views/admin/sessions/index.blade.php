@extends('admin.layouts.app')

@section('title', 'Login Sessions')
@section('heading', 'Login Sessions')
@section('page_description', 'Monitor user login sessions, devices, and activity.')

@section('content')


<div class="session-actions">
    <form action="{{ route('admin.sessions.destroyOthers') }}"
          method="POST"
          onsubmit="return confirm('Are you sure you want to logout all other sessions of your account?')">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">
            Logout All Other Sessions
        </button>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table sessions-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>Browser / Device</th>
                    <th>Last Activity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sessions as $session)
                    @php
                        $isActive = $session->last_activity >=
                            now()->timestamp - (config('session.lifetime') * 60);

                        $agent = $session->user_agent ?? '';

                        $browser = 'Unknown Browser';

                        if (str_contains($agent, 'Edg/')) {
                            $browser = 'Microsoft Edge';
                        } elseif (str_contains($agent, 'Chrome/')) {
                            $browser = 'Google Chrome';
                        } elseif (str_contains($agent, 'Firefox/')) {
                            $browser = 'Mozilla Firefox';
                        } elseif (str_contains($agent, 'Safari/')) {
                            $browser = 'Safari';
                        }

                        $device = str_contains($agent, 'Windows') ? 'Windows'
                            : (str_contains($agent, 'Android') ? 'Android'
                            : (str_contains($agent, 'iPhone') ? 'iPhone'
                            : (str_contains($agent, 'Mac') ? 'Mac' : 'Unknown Device')));
                    @endphp

                    <tr>
                        <td>{{ $session->name ?? 'Deleted User' }}</td>
                        <td>{{ $session->email ?? 'N/A' }}</td>
                        <td>{{ $session->ip_address ?? 'N/A' }}</td>
                        <td>{{ $browser }} / {{ $device }}</td>

                        <td>
                            {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            @if($isActive)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Expired</span>
                            @endif
                        </td>

                        <td>
                            @if($session->id !== session()->getId())
                                <form action="{{ route('admin.sessions.destroy', $session->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to terminate this session?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        Terminate
                                    </button>
                                </form>
                            @else
                                <span class="status-badge status-active">
                                    Current Session
                                </span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No login sessions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $sessions->links('vendor.pagination.admin') }}
    </div>
</div>

@endsection
