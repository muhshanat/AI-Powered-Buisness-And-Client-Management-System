<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_connections', function (Blueprint $table) {
            $table->unsignedInteger('sync_page')->default(1);
            $table->unsignedInteger('sync_total')->default(0);
            $table->unsignedInteger('sync_processed')->default(0);
            $table->string('sync_status')->default('idle');
        });
    }

    public function down(): void
    {
        Schema::table('website_connections', function (Blueprint $table) {
            $table->dropColumn([
                'sync_page',
                'sync_total',
                'sync_processed',
                'sync_status',
            ]);
        });
    }
};