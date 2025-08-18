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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            
                // Social Media with default values
            $table->json('social_links')->default(json_encode([
                [
                    'platform' => 'Instagram',
                    'url' => 'https://www.instagram.com/alwanmaya_salon',
                    'active' => true,
                    'icon' => 'fa-instagram'
                ],
                [
                    'platform' => 'Snapchat',
                    'url' => 'https://snapchat.com/t/ek8fmyCF',
                    'active' => true,
                    'icon' => 'fa-snapchat'
                ],
                [
                    'platform' => 'TikTok',
                    'url' => 'https://www.tiktok.com/@alwanmayaa',
                    'active' => true,
                    'icon' => 'fa-tiktok'
                ]
            ]))->comment('Array of social media links');
                    
                        // Navigation Links with default values
            $table->json('navigation_links')->default(json_encode([
                [
                    'label' => 'About',
                    'url' => '/about',
                    'active' => true
                ],
                [
                    'label' => 'Menu',
                    'url' => '/menu',
                    'active' => true
                ],
                [
                    'label' => 'Services',
                    'url' => '/services',
                    'active' => true
                ],
                [
                    'label' => 'Testimonials',
                    'url' => '/testimonials',
                    'active' => true
                ],
                [
                    'label' => 'FAQ',
                    'url' => '/faq',
                    'active' => true
                ],
                [
                    'label' => 'Contact',
                    'url' => '/contact',
                    'active' => true
                ]
            ]))->comment('Array of navigation links');
            
            // Copyright
            $table->string('copyright_text')->default('2025 mcs.sa. All Rights Reserved');
            $table->string('designer_text')->default('Designed by SWU');
            $table->string('designer_url')->default('https://www.swu.sa');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
