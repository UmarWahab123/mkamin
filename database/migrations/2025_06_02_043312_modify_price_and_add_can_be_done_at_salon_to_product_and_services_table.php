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
            $table->decimal('price', 8, 2)->nullable()->change();
            $table->boolean('can_be_done_at_salon')->default(true)->after('can_be_done_at_home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_and_services', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable(false)->change();
            $table->dropColumn('can_be_done_at_salon');
        });
    }
};
