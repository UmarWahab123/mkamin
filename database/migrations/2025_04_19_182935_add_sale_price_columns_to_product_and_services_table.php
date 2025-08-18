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
        Schema::table('product_and_services', function (Blueprint $table) {
            $table->decimal('sale_price_at_saloon', 10, 2)->nullable()->after('price');
            $table->decimal('sale_price_at_home', 10, 2)->nullable()->after('price_home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_and_services', function (Blueprint $table) {
            $table->dropColumn('sale_price_at_saloon');
            $table->dropColumn('sale_price_at_home');
        });
    }
};
