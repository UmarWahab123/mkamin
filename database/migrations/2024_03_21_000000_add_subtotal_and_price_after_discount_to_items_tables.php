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
        // Add columns to invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('price');
            $table->decimal('price_after_discount', 10, 2)->nullable()->after('subtotal');
        });

        // Add columns to booked_reservation_items table
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('price');
            $table->decimal('price_after_discount', 10, 2)->nullable()->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'price_after_discount']);
        });

        // Remove columns from booked_reservation_items table
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'price_after_discount']);
        });
    }
};
