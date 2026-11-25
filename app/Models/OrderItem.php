<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    // Item belongs to an order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Item belongs to a product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
