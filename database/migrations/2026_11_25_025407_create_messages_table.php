<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Parent Conversation
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Message Sender
            $table->enum('sender_type', [
                'customer',
                'organization'
            ]);

            // Message Content
            $table->text('message');

            // Optional Attachment
            $table->string('attachment')->nullable();

            // Message Read Status
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
