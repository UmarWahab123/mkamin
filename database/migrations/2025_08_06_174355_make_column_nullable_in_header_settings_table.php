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
        Schema::table('header_settings', function (Blueprint $table) {
            $table->string('mobile_logo')->nullable()->change();
            $table->string('desktop_logo')->nullable()->change();
            $table->string('header_color')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('header_settings', function (Blueprint $table) {
            $table->string('mobile_logo')->nullable(false)->change();
            $table->string('desktop_logo')->nullable(false)->change();
            $table->string('header_color')->nullable(false)->change();
        });
    }
};
