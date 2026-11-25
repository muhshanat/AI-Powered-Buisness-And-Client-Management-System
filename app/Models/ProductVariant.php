<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'source_variant_id',
        'title',
        'sku',
        'weight',
        'published',
        'stock_status',
        'price',
        'stock',
        'sale_prices',
        'attributes',
        'images',
        'inventory_data',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'stock_status' => 'boolean',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'sale_prices' => 'array',
            'attributes' => 'array',
            'images' => 'array',
            'inventory_data' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
