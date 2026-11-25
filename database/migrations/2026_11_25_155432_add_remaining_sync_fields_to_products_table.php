<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('weight')->nullable();

            $table->boolean('source_published')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('purchasable')->default(true);

            $table->json('tags')->nullable();
            $table->json('categories')->nullable();
            $table->json('attributes')->nullable();
            $table->json('images')->nullable();
            $table->json('sale_prices')->nullable();
            $table->json('inventory_data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'brand',
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
            ]);
        });
    }
};
