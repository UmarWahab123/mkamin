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
        Schema::table('time_intervals', function (Blueprint $table) {
            $table->boolean('can_visit_home')->default(false)->after('is_closed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_intervals', function (Blueprint $table) {
            $table->dropColumn('can_visit_home');
        });
    }
};
