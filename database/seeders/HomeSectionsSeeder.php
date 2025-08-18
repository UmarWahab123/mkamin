<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSection;

class HomeSectionsSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'section_name' => 'hero_section',
                'order' => 1,
                'visible' => 1,
                'content' => [
                    'slides' => [
                        [
                            'smallTitle' => 'Welcome',
                            'title' => 'mcs.sa Beauty Salon',
                            'smallTitleColor' => '#af8855',
                            'titleColor' => '#ffffff',
                            'buttonText' => '',
                            'buttonUrl' => '',
                            'buttonBgColor' => '#af8855',
                            'buttonTextColor' => '#ffffff',
                            // 'bgImage' => 'home/mcs-1.jpeg' // Maps to /assets/images/slider/mcs-1.jpeg
                        ],
                        [
                            'smallTitle' => 'Transform',
                            'title' => 'Your Beauty Journey',
                            'smallTitleColor' => '#af8855',
                            'titleColor' => '#ffffff',
                            'buttonText' => '',
                            'buttonUrl' => '',
                            'buttonBgColor' => '#af8855',
                            'buttonTextColor' => '#ffffff',
                            // 'bgImage' => 'home/mcs-2.jpeg' // Maps to /assets/images/slider/mcs-2.jpeg
                        ],
                    ],
                ],
            ],
            [
                'section_name' => 'trending_services',
                'order' => 2,
                'visible' => 1,
                'content' => [
                    'section_id' => 'Best Selling Services',
                    'title' => 'Trending Services',
                    'small_title_color' => '#af8855',
                    'title_color' => '#363636',
                ],
            ],
            [
                'section_name' => 'text_content_1',
                'order' => 3,
                'visible' => 1,
                'content' => [
                    'smallTitle' => 'Welcome to mcs.sa', // Matches your current textContent
                    'title' => 'Experience Luxury Beauty', // Matches your current textContent  
                    'description' => 'Discover our exceptional beauty services designed to enhance your natural elegance and provide you with the ultimate relaxation experience.',
                    'smallTitleColor' => '#af8855', // color--gold from your template
                    'titleColor' => '#363636',
                    'descriptionColor' => '#666',
                    // 'image' => 'home/mcs-3.jpeg' // Maps to /assets/images/mcs-3.jpeg
                ],
            ],
            [
                'section_name' => 'text_content_2',
                'order' => 4,
                'visible' => 1,
                'content' => [
                    'smallTitle' => 'About mcs.sa', // Matches your current textContent2
                    'title' => 'Our Story of Excellence', // Matches your current textContent2
                    'cardTitle' => 'Professional Care', // Matches your textContent2 card
                    'cardDescription' => 'Expert stylists with years of experience in beauty and wellness.',
                    'smallTitleColor' => '#af8855',
                    'titleColor' => '#363636',
                    'cardBackgroundColor' => '#ffffff',
                    'cardTitleColor' => '#363636',
                    'cardDescriptionColor' => '#666',
                    // 'image1' => 'home/mcs-4.jpeg', // Maps to /assets/images/mcs-4.jpeg
                    // 'image2' => 'home/mcs-5.jpeg', // Maps to /assets/images/mcs-5.jpeg
                    // 'image3' => 'home/mcs-6.jpeg', // Maps to /assets/images/mcs-6.jpeg
                ],
            ],
            [
                'section_name' => 'services_section',
                'order' => 5,
                'visible' => 1,
                'content' => [
                    'services' => [
                        [
                            'title' => 'Facial Treatments', // Matches your current servicesContent
                            'description' => 'Rejuvenating facial treatments for glowing, healthy skin',
                            'icon' => 'flaticon-facial-treatment', // Using your current icon classes
                            // 'image' => 'home/service-1.jpg' // Optional service image
                        ],
                        [
                            'title' => 'Hair Styling',
                            'description' => 'Professional hair cutting, coloring and styling services',
                            'icon' => 'flaticon-hair-brush',
                            // 'image' => 'home/service-2.jpg'
                        ],
                        [
                            'title' => 'Nail Care',
                            'description' => 'Complete manicure and pedicure services',
                            'icon' => 'flaticon-foundation', // Using your current nail icon
                            // 'image' => 'home/service-3.jpg'
                        ],
                        [
                            'title' => 'Makeup Services',
                            'description' => 'Professional makeup for all special occasions',
                            'icon' => 'flaticon-cosmetics', // Using your current makeup icon
                            // 'image' => 'home/service-4.jpg'
                        ],
                    ],
                    'titleColor' => '#363636', // Matches your servicesContent titleColor
                    'descriptionColor' => '#666', // Matches your servicesContent descriptionColor
                ],
            ],
            [
                'section_name' => 'text_content_3',
                'order' => 6,
                'visible' => 1,
                'content' => [
                    'smallTitle' => 'Special Offer', // Matches your current textContent3
                    'title' => 'Exclusive Beauty Package', // Matches your current textContent3
                    'description' => 'Transform yourself with our comprehensive beauty treatments designed for your complete wellness and relaxation.',
                    'buttonText' => 'Learn More', // Matches your current textContent3 buttonText
                    'buttonUrl' => '/salon-services', // Matches your current textContent3 buttonUrl
                    'smallTitleColor' => '#af8855', // Matches your textContent3 colors
                    'titleColor' => '#ffffff',
                    'descriptionColor' => '#ffffff',
                    'buttonBgColor' => '#af8855', // Matches your textContent3 buttonBgColor
                    'buttonTextColor' => '#ffffff', // Matches your textContent3 buttonTextColor
                ],
            ],
            [
                'section_name' => 'pricing_section',
                'order' => 7,
                'visible' => 1,
                'content' => [
                    'sectionTitle' => 'Our Services & Prices', // Matches your current pricing section
                    'buttonText' => 'View All Prices', // Matches current button text
                    'buttonUrl' => '/menu', // Matches route('menu')
                    'showSectionTitle' => true,
                ],
            ],
            [
                'section_name' => 'wide_image_section',
                'order' => 8,
                'visible' => 1,
                'content' => [
                    // 'image' => 'home/mcs-8.jpeg' // Maps to /assets/images/mcs-8.jpeg from your current wideImage
                ],
            ],
            [
                'section_name' => 'text_content_4',
                'order' => 9,
                'visible' => 1,
                'content' => [
                    'smallTitle' => 'Premium Services', // Matches your current textContent4
                    'title' => 'Professional Beauty Care', // Matches your current textContent4
                    'description1' => 'Experience the finest in beauty and wellness with our expert team of professionals.',
                    'description2' => 'We use only premium products and techniques to ensure exceptional results every time.',
                    'backgroundColor' => '#ffffff', // Matches your textContent4 backgroundColor
                    'smallTitleColor' => '#af8855', // Matches your textContent4 colors
                    'titleColor' => '#363636',
                    'description1Color' => '#666',
                    'description2Color' => '#666',
                    // 'image' => 'home/mcs-7.jpeg' // Maps to /assets/images/mcs-7.jpeg
                ],
            ],
            [
                'section_name' => 'working_hours_section',
                'order' => 10,
                'visible' => 1,
                'content' => [
                    'smallTitle' => 'Working Hours', // Matches your workingHoursContent
                    'title' => 'Visit Us Today', // Matches your workingHoursContent
                    'description' => 'We\'re ready to help you look and feel your best. Contact us during our business hours.',
                    'smallTitleColor' => '#e74c3c', // Matches your workingHoursContent colors
                    'titleColor' => '#2c3e50',
                    'descriptionColor' => '#666',
                    'dayNameColor' => '#333', // Matches your workingHoursContent dayNameColor
                    'timeColor' => '#666', // Matches your workingHoursContent timeColor
                ],
            ],
            [
                'section_name' => 'contact_section',
                'order' => 11,
                'visible' => 1,
                'content' => [
                    'hoursTitle' => 'Working Hours', // Matches your contactSectionContent
                    'locationTitle' => 'Our Location', // Matches your contactSectionContent
                    'locationAr' => 'الرياض، المملكة العربية السعودية', // Matches your contactSectionContent
                    'locationEn' => 'Riyadh, Saudi Arabia', // Matches your contactSectionContent
                    'phoneNo1' => '+966 12 345 6789', // Matches your contactSectionContent phoneNo1
                    'phoneNo2' => '+966 50 123 4567', // Matches your contactSectionContent phoneNo2
                    'backgroundColor' => '#f8f9fa', // Matches your contactSectionContent backgroundColor
                    'textColor' => '#333', // Matches your contactSectionContent textColor
                    // 'mapSrc' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.140...' // Add your actual Google Maps embed URL
                ],
            ],
        ];

        foreach ($sections as $s) {
            HomeSection::updateOrCreate(
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