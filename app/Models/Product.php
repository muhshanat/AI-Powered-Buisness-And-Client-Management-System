<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'source_product_id',
        'source_url',
        'sku',
        'brand',
        'summary',
        'weight',
        'source_published',
        'is_draft',
        'featured',
        'purchasable',
        'tags',
        'categories',
        'attributes',
        'images',
        'sale_prices',
        'inventory_data',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'source_published' => 'boolean',
            'is_draft' => 'boolean',
            'featured' => 'boolean',
            'purchasable' => 'boolean',
            'tags' => 'array',
            'categories' => 'array',
            'attributes' => 'array',
            'images' => 'array',
            'sale_prices' => 'array',
            'inventory_data' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
