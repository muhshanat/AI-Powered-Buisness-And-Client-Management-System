<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'subtotal',
        'delivery_charge',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    // Order belongs to an organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Order contains multiple items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
