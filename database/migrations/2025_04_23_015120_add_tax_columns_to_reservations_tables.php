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
        // Add other_taxes_amount to booked_reservations table
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->decimal('other_taxes_amount', 10, 2)->default(0)->after('vat_amount');

        });

        // Add vat_amount and other_taxes_amount to booked_reservation_items table
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->decimal('vat_amount', 10, 2)->default(0)->after('total');
            $table->decimal('other_taxes_amount', 10, 2)->default(0)->after('vat_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from booked_reservations table
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->dropColumn('other_taxes_amount');
        });

        // Remove columns from booked_reservation_items table
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn(['vat_amount', 'other_taxes_amount']);
        });
    }
};
