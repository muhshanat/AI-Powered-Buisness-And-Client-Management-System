<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerConversationController extends Controller
{
    // Display customer contact form
    public function create(int $organizationId)
    {
        $organization = \App\Models\Organization::where('status', 'approved')
            ->findOrFail($organizationId);

        return view('public.chat.create', compact('organization'));
    }

    // Create a new customer conversation
    public function store(Request $request, int $organizationId)
    {
        $organization = \App\Models\Organization::where('status', 'approved')
            ->findOrFail($organizationId);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = $organization->conversations()->create([
            'public_token' => Str::random(64),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'],
            'subject' => $validated['subject'] ?? 'General Inquiry',
            'channel' => 'website',
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $conversation->messages()->create([
            'sender_type' => 'customer',
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('public.chat.show', $conversation->public_token)
            ->with('success', 'Your message has been sent successfully.');
    }

    // Display customer conversation
    public function show(string $token)
    {
        $conversation = Conversation::where('public_token', $token)
            ->where('channel', 'website')
            ->with('messages')
            ->firstOrFail();

        return view('public.chat.show', compact('conversation'));
    }

    // Customer sends another message
    public function reply(Request $request, string $token)
    {
        $conversation = Conversation::where('public_token', $token)
            ->where('channel', 'website')
            ->firstOrFail();

        abort_if($conversation->status === 'closed', 403,
            'This conversation is closed.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversation->messages()->create([
            'sender_type' => 'customer',
            'message' => $validated['message'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()
            ->route('public.chat.show', $conversation->public_token)
            ->with('success', 'Your message has been sent.');
    }
}
