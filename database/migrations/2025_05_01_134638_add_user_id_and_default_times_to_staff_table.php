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
        Schema::table('staff', function (Blueprint $table) {
            // Add user_id field with foreign key constraint
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();

            // Add default time fields
            $table->time('default_start_time')->nullable()->after('is_active')->default('09:00:00');
            $table->time('default_end_time')->nullable()->after('default_start_time')->default('17:00:00');

            // Add default closed day (0 = Sunday, 6 = Saturday)
            $table->unsignedTinyInteger('default_closed_day')->nullable()->after('default_end_time');

            // Add default home visit days as JSON array
            $table->json('default_home_visit_days')->nullable()->after('default_closed_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Drop the added columns
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'default_start_time', 'default_end_time', 'default_closed_day', 'default_home_visit_days']);
        });
    }
};
