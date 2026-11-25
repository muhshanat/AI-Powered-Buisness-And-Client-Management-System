
@extends('organization.layouts.app')

@section('title', 'Conversation Details')
@section('heading', 'Customer Conversation')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/organization-conversations.css') }}">
@endpush

@section('content')
<div class="content-card">

    <div class="page-header">
        <div>
            <h2>{{ $conversation->customer_name }}</h2>
            <p>{{ $conversation->subject ?? 'General Inquiry' }}</p>
        </div>

        <a href="{{ route('organization.conversations.index') }}" class="btn btn-view">
            Back to Conversations
        </a>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="conversation-info-card">
        <h3>Customer Information</h3>

        <p><strong>Name:</strong> {{ $conversation->customer_name }}</p>
        <p><strong>Email:</strong> {{ $conversation->customer_email ?? 'Not provided' }}</p>
        <p><strong>Phone:</strong> {{ $conversation->customer_phone ?? 'Not provided' }}</p>
        <p><strong>Channel:</strong> {{ ucfirst($conversation->channel) }}</p>

        <p>
            <strong>Status:</strong>
            <span class="conversation-badge {{ $conversation->status }}">
                {{ ucfirst($conversation->status) }}
            </span>
        </p>
    </div>

    <div class="chat-container">

        <div class="chat-header">
            <h3>Message History</h3>
        </div>

        <div class="chat-messages">

            @forelse($conversation->messages as $message)

                <div class="message-row {{ $message->sender_type === 'organization' ? 'outgoing' : 'incoming' }}">

                    <div class="message-bubble">

                        <span class="message-sender">
                            {{ $message->sender_type === 'organization' ? 'You' : $conversation->customer_name }}
                        </span>

                        @if($message->message)
                            <p>{{ $message->message }}</p>
                        @endif

                        @if($message->attachment)
                            <a href="{{ asset('storage/' . $message->attachment) }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                View Attachment
                            </a>
                        @endif

                        <small>
                            {{ $message->created_at->format('d M Y, h:i A') }}
                        </small>

                    </div>

                </div>

            @empty
                <div class="empty-chat">
                    <p>No messages in this conversation yet.</p>
                </div>
            @endforelse

        </div>

        @if($conversation->status === 'open')

            <form action="{{ route('organization.conversations.store', $conversation->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="message-form">

                @csrf

                <div class="form-group">
                    <label for="message">Write a message</label>

                    <textarea name="message"
                              id="message"
                              rows="3"
                              placeholder="Type your reply...">{{ old('message') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="attachment">Attach File (Optional)</label>
                    <input type="file" name="attachment" id="attachment">
                    <small>Maximum file size: 5 MB.</small>
                </div>

                @if($errors->any())
                    <div class="error-alert">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="btn btn-view">
                    Send Message
                </button>

            </form>

        @else
            <div class="closed-notice">
                This conversation is closed. Reopen it to send messages.
            </div>
        @endif

    </div>

    <div class="conversation-info-card status-update-card">
        <h3>Manage Conversation</h3>

        <form action="{{ route('organization.conversations.update', $conversation->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="status">Conversation Status</label>

                <select name="status" id="status" required>
                    <option value="open" {{ $conversation->status === 'open' ? 'selected' : '' }}>
                        Open
                    </option>

                    <option value="closed" {{ $conversation->status === 'closed' ? 'selected' : '' }}>
                        Closed
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-view">
                Update Status
            </button>

        </form>
    </div>

</div>
@endsection
