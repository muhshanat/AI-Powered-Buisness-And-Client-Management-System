<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    // Get the logged-in organization's approved account
    private function currentOrganization()
    {
        return auth('organization')->user()
            ->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }

    // Display all conversations
    public function index()
    {
        $organization = $this->currentOrganization();

        $conversations = $organization->conversations()
            ->with('messages')
            ->latest('last_message_at')
            ->paginate(10);

        return view('organization.conversations.index', compact(
            'organization',
            'conversations'
        ));
    }

    // Display conversation and message history
    public function show(Conversation $conversation)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $conversation->organization_id === $organization->id,
            404
        );

        $conversation->load('messages');

        return view('organization.conversations.show', compact(
            'organization',
            'conversation'
        ));
    }

    // Send an organization reply
    public function store(Request $request, Conversation $conversation)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $conversation->organization_id === $organization->id,
            404
        );

        abort_if($conversation->status === 'closed', 403,
            'This conversation is closed.');

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return back()->withErrors([
                'message' => 'Please enter a message or attach a file.'
            ]);
        }

        $attachment = null;

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment')
                ->store('conversation-attachments', 'public');
        }

        $conversation->messages()->create([
            'sender_type' => 'organization',
            'message' => $validated['message'] ?? '',
            'attachment' => $attachment,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()
            ->route('organization.conversations.show', $conversation->id)
            ->with('success', 'Message sent successfully.');
    }

    // Update conversation status
    public function update(Request $request, Conversation $conversation)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $conversation->organization_id === $organization->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['open', 'closed'])
            ],
        ]);

        $conversation->update($validated);

        return redirect()
            ->route('organization.conversations.show', $conversation->id)
            ->with('success', 'Conversation status updated successfully.');
    }
}
