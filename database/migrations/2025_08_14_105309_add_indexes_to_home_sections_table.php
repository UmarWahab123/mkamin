<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HomeSection;

return new class extends Migration
{
    public function up(): void
    {
        // Since indexes are already added in the main migration, 
        // this migration focuses on data normalization
        
        // Fix any existing sections with order 0 (if any)
        HomeSection::where('order', 0)->update(['order' => 1]);
        
        // Normalize orders to ensure proper sequence (1, 2, 3, etc.)
        $sections = HomeSection::orderBy('order')->orderBy('id')->get();
        $sections->each(function ($section, $index) {
            $section->update(['order' => $index + 1]);
        });
    }

    public function down(): void
    {
        // No need to drop indexes since they're part of the main table structure
        // This migration is mainly for data normalization
    }
};