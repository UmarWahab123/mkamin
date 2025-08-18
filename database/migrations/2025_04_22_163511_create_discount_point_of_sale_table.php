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
        Schema::create('discount_point_of_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('point_of_sale_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Add unique constraint to prevent duplicate associations
            $table->unique(['discount_id', 'point_of_sale_id', 'deleted_at'], 'discount_pos_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_point_of_sale');
    }
};
