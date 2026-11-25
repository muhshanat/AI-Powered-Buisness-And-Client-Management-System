
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Organization
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            // Service
            $table->foreignId('service_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Appointment Information
            $table->string('appointment_number')->unique();
            $table->string('service_name');
            $table->decimal('service_price', 10, 2)->default(0);

            // External Customer Information
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');

            // Booking Schedule
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();

            // Appointment Status
            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
