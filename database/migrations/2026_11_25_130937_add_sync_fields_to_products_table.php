<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('source_product_id')->nullable();
            $table->string('source_url')->nullable();
            $table->timestamp('last_synced_at')->nullable();

            $table->unique(
                ['organization_id', 'source_product_id'],
                'products_org_source_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_org_source_unique');

            $table->dropColumn([
                'source_product_id',
                'source_url',
                'last_synced_at',
            ]);
        });
    }
};
