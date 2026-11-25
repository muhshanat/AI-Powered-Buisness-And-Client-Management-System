<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_connections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('website_name');
            $table->string('api_url');
            $table->text('bearer_token');

            $table->boolean('is_connected')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            $table->unique('organization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_connections');
    }
};
