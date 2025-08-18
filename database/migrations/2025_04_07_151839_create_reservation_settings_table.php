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
        Schema::create('reservation_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_of_sale_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('day_of_week'); // 0-6 for Sunday-Saturday
            $table->time('opening_time');
            $table->time('closing_time');
            $table->integer('workers_count')->default(1);
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->unique(['date', 'point_of_sale_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_settings');
    }
};
