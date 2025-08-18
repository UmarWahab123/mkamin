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
            $table->string('payment_method')->default('cash')->after('notes');
            $table->decimal('total_amount_paid', 10, 2)->default(0)->after('total_paid_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_reservations', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('total_amount_paid');
        });
    }
};
