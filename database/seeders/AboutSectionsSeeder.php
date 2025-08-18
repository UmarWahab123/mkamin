<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;

class AboutSectionsSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'section_name' => 'hero',
                'order' => 1,
                'visible' => 1,
                'content' => [
                    'title' => 'About mcs.sa',
                    'description' => 'Luxury salon where you will feel unique and special',
                    // optionally you can set a storage path: 'background_image' => 'about/hero-bg.jpg'
                ],
            ],
            [
                'section_name' => 'about_content',
                'order' => 2,
                'visible' => 1,
                'content' => [
                    'left_section_id' => 'Mind, Body and Soul',
                    'left_title' => 'Luxury salon where you will feel unique',
                    'left_body' => 'Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty. Experience tranquility and rejuvenation in our meticulously designed space created with your comfort in mind.',
                    'left_image' => '/assets/images/beauty_08.jpg',
                    'right_body' => 'At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect. Our talented team continuously trains in the latest trends and methods to ensure you receive the highest quality care with every visit.',
                    'right_image' => '/assets/images/salon_04.jpg',
                ],
            ],
            [
                'section_name' => 'center_text',
                'order' => 3,
                'visible' => 1,
                'content' => [
                    'section_id' => 'Indulge Yourself',
                    'title_center' => 'Feel Yourself More Beautiful',
                    'body_center' => 'Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.',
                ],
            ],
            [
                'section_name' => 'services_preview',
                'order' => 4,
                'visible' => 1,
                'content' => [
                    'services' => [
                        ['name' => 'Facials', 'icon_class' => 'flaticon-facial-treatment'],
                        ['name' => 'Eyelash', 'icon_class' => 'flaticon-eyelashes'],
                        ['name' => 'Eyebrow', 'icon_class' => 'flaticon-eyebrow'],
                        ['name' => 'Waxing', 'icon_class' => 'flaticon-wax'],
                        ['name' => 'Nails', 'icon_class' => 'flaticon-foundation'],
                        ['name' => 'Make-Up', 'icon_class' => 'flaticon-cosmetics'],
                    ],
                    'button_text' => 'View Our Menu',
                ],
            ],
            [
                'section_name' => 'features_accordion',
                'order' => 5,
                'visible' => 1,
                'content' => [
                    'features_section_id' => 'You Are Beauty',
                    'features_title' => 'Give the pleasure of beautiful to yourself',
                    'features_image' => '/assets/images/woman_02.jpg',
                    'features_accordion' => [
                        ['title' => 'Certified Stylists', 'content' => 'Our team consists of highly trained professionals with international certifications and years of experience in the beauty industry.'],
                        ['title' => '100% Organic Cosmetics', 'content' => 'We use only premium organic and cruelty-free products that nourish your skin and hair while being kind to the environment.'],
                        ['title' => 'Easy Online Booking', 'content' => 'Book your appointments with ease through our convenient online system, available 24/7 with instant confirmation and reminders.'],
                    ],
                ],
            ],
            [
                'section_name' => 'working_hours',
                'order' => 6,
                'visible' => 1,
                'content' => [
                    'working_hours' => [
                        ['day' => 'Monday', 'time' => '09:00 - 18:00'],
                        ['day' => 'Tuesday', 'time' => '09:00 - 18:00'],
                        ['day' => 'Wednesday', 'time' => '09:00 - 18:00'],
                        ['day' => 'Thursday', 'time' => '09:00 - 18:00'],
                        ['day' => 'Friday', 'time' => '09:00 - 17:00'],
                        ['day' => 'Saturday', 'time' => '10:00 - 16:00'],
                        ['day' => 'Sunday', 'time' => 'Closed'],
                    ],
                    'smallTitle' => 'Working Hours',
                    'title' => 'Visit Us Today',
                    'description' => 'We\'re ready to help you look and feel your best. Walk-ins welcome or book online.',
                    'dayNameColor' => '#111',
                    'timeColor' => '#111',
                    'smallTitleColor' => '#8c8c8c',
                    'titleColor' => '#222',
                    'descriptionColor' => '#555',
                ],
            ],
            [
                'section_name' => 'wide_image',
                'order' => 7,
                'visible' => 1,
                'content' => [
                    'image' => '/assets/images/wide_image.jpg' // optional, fallback exists in CSS/HTML
                ],
            ],
            [
                'section_name' => 'about5',
                'order' => 8,
                'visible' => 1,
                'content' => [
                    'image_1' => '/assets/images/beauty_02.jpg',
                    'small_title' => 'Be Irresistible',
                    'title' => 'The Ultimate Relaxation for Your Mind and Body',
                    'image_2' => '/assets/images/beauty_03.jpg',
                    'image_3' => '/assets/images/beauty_04.jpg'
                ],
            ],
            [
                'section_name' => 'banner_promo',
                'order' => 9,
                'visible' => 1,
                'content' => [
                    'small_title' => 'This Week Only',
                    'title' => 'Get 30% OFF',
                    'subtitle' => 'Manicure + Gel Polish',
                    'button_text' => 'Book an Appointment',
                    'button_link' => '/salon-services',
                    'background' => '/assets/images/banner_bg.jpg',
                ],
            ],
        ];

        foreach ($sections as $s) {
            AboutSection::updateOrCreate(
                ['section_name' => $s['section_name']],
                [
                    'content' => $s['content'],
                    'order' => $s['order'],
                    'visible' => $s['visible'],
                ]
            );
        }
    }
}
