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
        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('color');
            $table->foreignId('point_of_sale_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Insert initial ticket statuses
        DB::table('ticket_statuses')->insert([
            [
                'id' => 1,
                'name_en' => 'Valid',
                'name_ar' => 'متاح',
                'color' => '#4CAF50',
                'point_of_sale_id' => 1,
                'created_at' => '2025-05-22 06:29:16',
                'updated_at' => '2025-05-22 06:29:16'
            ],
            [
                'id' => 2,
                'name_en' => 'Used',
                'name_ar' => 'مستخدم',
                'color' => '#FFC107',
                'point_of_sale_id' => 1,
                'created_at' => '2025-05-22 06:31:32',
                'updated_at' => '2025-05-22 06:31:32'
            ],
            [
                'id' => 3,
                'name_en' => 'Canceled',
                'name_ar' => 'ملغى',
                'color' => '#F44336',
                'point_of_sale_id' => 1,
                'created_at' => '2025-05-22 06:35:36',
                'updated_at' => '2025-05-22 06:35:36'
            ],
            [
                'id' => 4,
                'name_en' => 'Refunded',
                'name_ar' => 'مسترجع',
                'color' => '#9C27B0',
                'point_of_sale_id' => 1,
                'created_at' => '2025-05-22 06:36:01',
                'updated_at' => '2025-05-22 06:36:01'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_statuses');
    }
};
