<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booked_reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booked_reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_and_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('duration');
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->date('appointment_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('staff_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('staff_detail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_reservation_items');
    }
};
