<?php
// database/migrations/2025_08_14_create_home_sections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->unique(); // 'hero_banner', 'services_highlight', 'about_preview', etc.
            $table->json('content')->nullable();
            $table->integer('order')->default(1); // Start from 1 instead of 0
            $table->boolean('visible')->default(true);
            $table->timestamps();
            
            // Add indexes for better performance from the start
            $table->index(['visible', 'order']); // Compound index for common queries
            $table->index('order'); // Separate index for ordering operations
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('home_sections');
    }
};