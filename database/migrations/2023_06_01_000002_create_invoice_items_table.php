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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_and_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('duration');
            $table->integer('quantity');
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('other_taxes_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->date('appointment_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('staff_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('staff_detail')->nullable();
            $table->enum('location_type', ['salon', 'home'])->default('salon');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
