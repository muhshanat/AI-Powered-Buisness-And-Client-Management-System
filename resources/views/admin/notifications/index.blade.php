@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')


<div class="content-card">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <div>
            <h3>All Notifications</h3>
            <p>{{ auth()->user()->unreadNotifications()->count() }} unread notifications</p>
        </div>

        @if(auth()->user()->unreadNotifications()->exists())
            <form method="POST" action="{{ route('admin.notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @forelse($notifications as $notification)

        <div style="padding:20px; border-bottom:1px solid #dce5ef;
            background:{{ $notification->read_at ? '#ffffff' : '#eaf4ff' }};">

            <div style="display:flex; justify-content:space-between; gap:20px;">

                <div>
                    <h4>
                        {{ $notification->data['title'] ?? 'System Notification' }}
                    </h4>

                    <p>
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    </p>

                    <small>
                        {{ $notification->created_at->format('d M Y, h:i A') }}
                    </small>

                    @if(!$notification->read_at)
                        <span style="color:#0877c9; font-weight:bold;">
                            • Unread
                        </span>
                    @endif
                </div>

                <div style="display:flex; gap:10px; align-items:center;">

                    @if(!$notification->read_at)
                        <form method="POST"
                            action="{{ route('admin.notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="btn">
                                Mark as Read
                            </button>
                        </form>
                    @endif

                    <form method="POST"
                        action="{{ route('admin.notifications.destroy', $notification->id) }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Delete this notification?')">
                            Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>

    @empty
        <p style="text-align:center; padding:40px;">
            No notifications found.
        </p>
    @endforelse

    <div style="margin-top:20px;">
        {{ $notifications->links() }}
    </div>

</div>

@endsection