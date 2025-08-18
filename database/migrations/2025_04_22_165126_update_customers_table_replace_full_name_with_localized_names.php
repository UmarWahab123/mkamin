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
        // First: Add the new columns
        Schema::table('customers', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('id'); // Make it nullable initially
            $table->string('name_ar')->nullable()->after('name_en');
        });

        // Second: Copy data from full_name to name_en
        if (Schema::hasColumn('customers', 'full_name')) {
            DB::statement('UPDATE customers SET name_en = full_name');
        }

        // Third: Make name_en required (not null) and drop full_name
        Schema::table('customers', function (Blueprint $table) {
            $table->string('name_en')->nullable(false)->change(); // Make it required now
            $table->dropColumn('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First: Add back the full_name column
        Schema::table('customers', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('id');
        });

        // Second: Copy data from name_en to full_name
        DB::statement('UPDATE customers SET full_name = name_en');

        // Third: Make full_name required and drop the new columns
        Schema::table('customers', function (Blueprint $table) {
            $table->string('full_name')->nullable(false)->change();
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
