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
        Schema::table('customer_discount', function (Blueprint $table) {
            $table->foreignId('discount_card_template_id')
                ->nullable()
                ->constrained('discount_card_templates')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_discount', function (Blueprint $table) {
            $table->dropForeign(['discount_card_template_id']);
            $table->dropColumn('discount_card_template_id');
        });
    }
};
