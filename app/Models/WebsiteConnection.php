<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteConnection extends Model
{
    protected $fillable = [
    'organization_id',
    'website_name',
    'api_url',
    'bearer_token',
    'is_connected',
    'last_synced_at',

    // Synchronization progress
    'sync_page',
    'sync_total',
    'sync_processed',
    'sync_status',
];

    protected function casts(): array
    {
        return [
            'bearer_token' => 'encrypted',
            'is_connected' => 'boolean',
            'last_synced_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
