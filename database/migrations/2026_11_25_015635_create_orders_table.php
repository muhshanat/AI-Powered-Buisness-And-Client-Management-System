<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Organization
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // Order Information
            $table->string('order_number')->unique();

            // External Customer Information
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('delivery_address');

            // Payment and Amount
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('payment_method')->nullable();

            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'failed',
                'refunded'
            ])->default('unpaid');

            // Order Status
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
