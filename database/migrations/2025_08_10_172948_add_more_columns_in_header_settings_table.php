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
            $table->string('header_text_color')->nullable();
            $table->string('header_text_hover_color')->nullable();
            $table->string('header_text_dropdown_color')->nullable();
            $table->string('header_text_dropdown_hover_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('header_settings', function (Blueprint $table) {
            $table->dropColumn([
                'header_text_color',
                'header_text_hover_color',
                'header_text_dropdown_color',
                'header_text_dropdown_hover_color',
            ]);
        });
    }
};
