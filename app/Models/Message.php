<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'message',
        'attachment',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    // Message belongs to a conversation
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
