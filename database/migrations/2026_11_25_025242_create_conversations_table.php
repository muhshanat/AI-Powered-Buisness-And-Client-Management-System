<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // Organization
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // External Customer Information
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            // Conversation Information
            $table->string('subject')->nullable();

            $table->enum('channel', [
                'website',
                'facebook'
            ])->default('website');

            $table->enum('status', [
                'open',
                'closed'
            ])->default('open');

            $table->timestamp('last_message_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
