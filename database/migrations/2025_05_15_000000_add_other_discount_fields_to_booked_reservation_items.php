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
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            // Add other_discount_value column (can store both percentage or fixed amount)
            $table->decimal('other_discount_value', 10, 2)->nullable()->after('total');

            // Add other_discount_type column (percentage or fixed)
            $table->string('other_discount_type', 20)->nullable()->after('other_discount_value');

            // Add other_discount_amount column (calculated amount)
            $table->decimal('other_discount_amount', 10, 2)->nullable()->after('other_discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn('other_discount_value');
            $table->dropColumn('other_discount_type');
            $table->dropColumn('other_discount_amount');
        });
    }
};
