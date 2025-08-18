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
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->enum('booked_from', ['website', 'point_of_sale'])->default('point_of_sale')->after('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('booked_from', ['website', 'point_of_sale'])->default('point_of_sale')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->dropColumn('booked_from');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('booked_from');
        });
    }
};
