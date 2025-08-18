<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('name_en')->after('product_and_service_id')->nullable();
            $table->string('name_ar')->after('name_en')->nullable();
        });

        // Copy data from name to name_en for invoice_items
        DB::statement('UPDATE invoice_items SET name_en = name');

        // Remove name column from invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // Update booked_reservation_items table
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->string('name_en')->after('product_and_service_id')->nullable();
            $table->string('name_ar')->after('name_en')->nullable();
        });

        // Copy data from name to name_en for booked_reservation_items
        DB::statement('UPDATE booked_reservation_items SET name_en = name');

        // Remove name column from booked_reservation_items
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add name column back to invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('name')->after('product_and_service_id')->nullable();
        });

        // Copy data from name_en back to name for invoice_items
        DB::statement('UPDATE invoice_items SET name = name_en');

        // Remove name_en and name_ar columns from invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        // Add name column back to booked_reservation_items
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->string('name')->after('product_and_service_id')->nullable();
        });

        // Copy data from name_en back to name for booked_reservation_items
        DB::statement('UPDATE booked_reservation_items SET name = name_en');

        // Remove name_en and name_ar columns from booked_reservation_items
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
