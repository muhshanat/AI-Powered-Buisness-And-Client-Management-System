<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // External website variation ID
            $table->string('source_variant_id');

            $table->string('title')->nullable();
            $table->string('sku')->nullable();
            $table->string('weight')->nullable();

            // Variation status
            $table->boolean('published')->default(true);
            $table->boolean('stock_status')->default(true);

            // Price and stock
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);

            // Additional variation information
            $table->json('sale_prices')->nullable();
            $table->json('attributes')->nullable();
            $table->json('images')->nullable();
            $table->json('inventory_data')->nullable();

            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            // Prevent duplicate variations for one product
            $table->unique(
                ['product_id', 'source_variant_id'],
                'product_variant_source_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
