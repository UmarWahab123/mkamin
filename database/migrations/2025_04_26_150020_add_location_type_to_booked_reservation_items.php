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
            $table->enum('location_type', ['salon', 'home'])->default('salon')->after('staff_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_reservation_items', function (Blueprint $table) {
            $table->dropColumn('location_type');
        });
    }
};
