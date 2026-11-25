
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat | TanovaFlow</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eef3f9;
            color: #263445;
            padding: 25px;
        }

        .chat-container {
            max-width: 850px;
            margin: 30px auto;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        }

        .chat-header {
            background: #3287d8;
            color: white;
            padding: 25px;
        }

        .chat-header h2 {
            margin-bottom: 8px;
        }

        .chat-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .status {
            display: inline-block;
            margin-top: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            background: white;
            color: #3287d8;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .messages {
            padding: 25px;
            min-height: 350px;
            max-height: 500px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .message {
            max-width: 75%;
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 12px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .customer {
            background: #dcecff;
            margin-left: auto;
        }

        .organization {
            background: white;
            border: 1px solid #e0e7ef;
            margin-right: auto;
        }

        .sender {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
            color: #3287d8;
        }

        .message-time {
            display: block;
            margin-top: 8px;
            font-size: 11px;
            color: #8492a6;
        }

        .reply-section {
            padding: 25px;
            border-top: 1px solid #e5eaf0;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 13px;
            border: 1px solid #d5deea;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: #3287d8;
        }

        button {
            margin-top: 12px;
            padding: 12px 25px;
            background: #3287d8;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #246db5;
        }

        button:disabled {
            background: #9caec0;
            cursor: not-allowed;
        }

        .error {
            color: #dc3545;
            margin-top: 8px;
            font-size: 13px;
        }

        .success {
            background: #e2f7e9;
            color: #18733a;
            padding: 12px;
            margin: 15px 25px 0;
            border-radius: 7px;
        }

        .closed-message {
            color: #718096;
            text-align: center;
            padding: 15px;
        }

        .attachment {
            display: inline-block;
            margin-top: 8px;
            color: #246db5;
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .message {
                max-width: 90%;
            }
        }
    </style>
</head>

<body>

<div class="chat-container">

    <div class="chat-header">
        <h2>{{ $conversation->organization->name }}</h2>

        <p>
            Conversation with
            {{ $conversation->customer_name }}
        </p>

        <span class="status">
            {{ $conversation->status }}
        </span>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="messages">

        @forelse($conversation->messages as $message)

            <div class="message {{ $message->sender_type === 'customer' ? 'customer' : 'organization' }}">

                <div class="sender">
                    {{ $message->sender_type === 'customer' ? 'You' : $conversation->organization->name }}
                </div>

                <p>{{ $message->message }}</p>

                @if($message->attachment)
                    <a class="attachment"
                       href="{{ asset('storage/' . $message->attachment) }}"
                       target="_blank">
                        View attachment
                    </a>
                @endif

                <span class="message-time">
                    {{ $message->created_at->format('d M Y, h:i A') }}
                </span>

            </div>

        @empty

            <p>No messages yet.</p>

        @endforelse

    </div>

    <div class="reply-section">

        @if($conversation->status === 'open')

            <form action="{{ route('public.chat.reply', $conversation->public_token) }}"
                  method="POST">

                @csrf

                <textarea name="message"
                          placeholder="Write your message..."
                          required>{{ old('message') }}</textarea>

                @error('message')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit">Send Message</button>

            </form>

        @else

            <div class="closed-message">
                This conversation has been closed by the organization.
            </div>

        @endif

    </div>

</div>

</body>
</html>
