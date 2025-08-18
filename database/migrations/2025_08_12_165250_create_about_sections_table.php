<?php
// database/migrations/2025_08_12_create_about_sections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->unique(); // 'hero', 'about_content', 'center_text', ...
            $table->json('content')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('about_sections');
    }
};