<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AboutSection;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            // Add indexes for better performance
            $table->index(['visible', 'order']);
            $table->index('order');
        });
        
        // Fix any existing sections with order 0
        AboutSection::where('order', 0)->update(['order' => 1]);
        
        // Normalize orders to ensure proper sequence (1, 2, 3, etc.)
        $sections = AboutSection::orderBy('order')->orderBy('id')->get();
        $sections->each(function ($section, $index) {
            $section->update(['order' => $index + 1]);
        });
    }

    public function down(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            $table->dropIndex(['visible', 'order']);
            $table->dropIndex(['order']);
        });
    }
};