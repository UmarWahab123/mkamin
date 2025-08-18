<?php

namespace Database\Seeders;

use App\Models\HeaderSettings;
use Illuminate\Database\Seeder;

class HeaderSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeaderSettings::create([
            'mobile_logo' => 'assets/images/logo-black-barber.png',
            'desktop_logo' => 'assets/images/logo-black-barber.png',
            'header_color' => '#000000',
            'is_show_language_switcher' => true,
            'navigation_links' => [
                [
                    'label' => 'Home',
                    'url' => '/',
                    'active' => true,
                    'dropdown' => false
                ],
                [
                    'label' => 'Menu',
                    'url' => '/menu',
                    'active' => true,
                    'dropdown' => false
                ],
                [
                    'label' => 'Salon Services',
                    'url' => '/salon-services',
                    'active' => true,
                    'dropdown' => false
                ],
                [
                    'label' => 'Home Services',
                    'url' => '/home-services',
                    'active' => true,
                    'dropdown' => false
                ],
                [
                    'label' => 'More',
                    'dropdown' => true,
                    'active' => true,
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
                ]
            ]
        ]);
    }
}
