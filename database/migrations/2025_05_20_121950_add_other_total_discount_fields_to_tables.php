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
        // Add columns to booked_reservations table
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->decimal('other_total_discount_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('other_total_discount_value', 10, 2)->default(0)->after('other_total_discount_amount');
            $table->string('other_total_discount_type')->nullable()->after('other_total_discount_value');
        });

        // Add columns to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('other_total_discount_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('other_total_discount_value', 10, 2)->default(0)->after('other_total_discount_amount');
            $table->string('other_total_discount_type')->nullable()->after('other_total_discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from booked_reservations table
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->dropColumn('other_total_discount_amount');
            $table->dropColumn('other_total_discount_value');
            $table->dropColumn('other_total_discount_type');
        });

        // Remove columns from invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('other_total_discount_amount');
            $table->dropColumn('other_total_discount_value');
            $table->dropColumn('other_total_discount_type');
        });
    }
};
