
@extends('organization.layouts.app')

@section('title', 'Conversations')
@section('heading', 'Conversation Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-conversations.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>Customer Conversations</h2>
            <p>Manage messages and communication with your customers.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="conversation-summary">
        <div class="conversation-summary-card">
            <span>Total Conversations</span>
            <strong>{{ $conversations->total() }}</strong>
        </div>
    </div>

    @if($conversations->count() > 0)

        <div class="table-responsive">
            <table class="conversation-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Subject</th>
                        <th>Channel</th>
                        <th>Messages</th>
                        <th>Last Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($conversations as $conversation)
                        <tr>
                            <td>
                                <strong>{{ $conversation->customer_name }}</strong>
                                <small>{{ $conversation->customer_email ?? $conversation->customer_phone ?? 'No contact provided' }}</small>
                            </td>

                            <td>{{ $conversation->subject ?? 'General Inquiry' }}</td>

                            <td>
                                <span class="channel-badge">
                                    {{ ucfirst($conversation->channel) }}
                                </span>
                            </td>

                            <td>{{ $conversation->messages->count() }}</td>

                            <td>
                                {{ $conversation->last_message_at
                                    ? $conversation->last_message_at->format('d M Y, h:i A')
                                    : 'No messages yet' }}
                            </td>

                            <td>
                                <span class="conversation-badge {{ $conversation->status }}">
                                    {{ ucfirst($conversation->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('organization.conversations.show', $conversation->id) }}"
                                   class="btn btn-view">
                                    Open Chat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $conversations->links() }}
        </div>

    @else
        <div class="empty-state">
            <h3>No Conversations Yet</h3>
            <p>Customer messages will appear here when someone contacts your organization.</p>
        </div>
    @endif

</div>
@endsection
