<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'organization_id',
    'public_token',
    'customer_name',
    'customer_email',
    'customer_phone',
    'subject',
    'channel',
    'status',
    'last_message_at',
];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    // Conversation belongs to an organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Conversation contains multiple messages
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
