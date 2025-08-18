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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('point_of_sale_id')->nullable()->constrained()->onDelete('set null');
            $table->index('point_of_sale_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['point_of_sale_id']);
            $table->dropIndex(['point_of_sale_id']);
            $table->dropColumn('point_of_sale_id');
        });
    }
};
