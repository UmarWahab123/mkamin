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
        Schema::create('header_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_logo')->nullable();
            $table->string('desktop_logo')->nullable();
            $table->string('header_color')->nullable();
            $table->boolean('is_show_language_switcher')->default(true);
            $table->json('navigation_links')->default(json_encode([
                [
                    'label' => 'Home',
                    'url' => '/',
                    'isDropDown' => false
                ],
                [
                    'label' => 'Menu',
                    'url' => '/about',
                    'isDropDown' => false
                ],
                [
                    'label' => 'Salon Services',
                    'url' => '/menu',
                    'isDropDown' => false
                ],
                [
                    'label' => 'Home Services',
                    'url' => '/services',
                    'isDropDown' => false
                ],
                [
                    'label' => 'More',
                    'isDropDown' => true,
                    'isActive' => true,
                    'subLinks' => [
                        [
                            'label' => 'About',
                            'url' => '/about',
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
                        ]
                    ]
                ],
            ]))->comment('Array of navigation links');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_settings');
    }
};
