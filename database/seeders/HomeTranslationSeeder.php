<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class HomeTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get language IDs
        $englishLanguage = Language::where('code', 'en')->first();
        $arabicLanguage = Language::where('code', 'ar')->first();

        if (!$englishLanguage || !$arabicLanguage) {
            $this->command->info('Please run language seeder first.');
            return;
        }

        $translations = [
            // Hero section
            'Indulge Yourself' => [
                'en' => 'Indulge Yourself',
                'ar' => 'دلل نفسك'
            ],
            'Making Your Look Awesome And Manly' => [
                'en' => 'Making Your Look Awesome And Manly',
                'ar' => 'جعل مظهرك رائعًا ورجوليًا'
            ],
            'Discover More' => [
                'en' => 'Discover More',
                'ar' => 'اكتشف المزيد'
            ],
            'Declare Your Style' => [
                'en' => 'Declare Your Style',
                'ar' => 'أعلن عن أسلوبك'
            ],
            'Feel Free To Express And Choose Your Style' => [
                'en' => 'Feel Free To Express And Choose Your Style',
                'ar' => 'لا تتردد في التعبير واختيار أسلوبك'
            ],
            'View Salon Menu' => [
                'en' => 'View Salon Menu',
                'ar' => 'عرض قائمة الصالون'
            ],
            'Shear. Shave. Shine' => [
                'en' => 'Shear. Shave. Shine',
                'ar' => 'قص. حلاقة. تألق'
            ],
            'Traditional Service In A Modern Manner' => [
                'en' => 'Traditional Service In A Modern Manner',
                'ar' => 'خدمة تقليدية بأسلوب عصري'
            ],
            'Online Booking' => [
                'en' => 'Online Booking',
                'ar' => 'حجز عبر الإنترنت'
            ],

            // Text Content sections
            'Empower your look. Elevate your game' => [
                'en' => 'Empower your look. Elevate your game',
                'ar' => 'عزز مظهرك. ارفع مستوى لعبتك'
            ],
            'Your Hair, Your Style' => [
                'en' => 'Your Hair, Your Style',
                'ar' => 'شعرك، أسلوبك'
            ],
            'Services for progressive or traditional gentlemen' => [
                'en' => 'Services for progressive or traditional gentlemen',
                'ar' => 'خدمات للرجال التقدميين أو التقليديين'
            ],
            'Don\'t be ordinary, be extraordinary' => [
                'en' => 'Don\'t be ordinary, be extraordinary',
                'ar' => 'لا تكن عاديًا، كن استثنائيًا'
            ],

            // Services section
            'Haircut' => [
                'en' => 'Haircut',
                'ar' => 'قصة شعر'
            ],
            'Moustache Trim' => [
                'en' => 'Moustache Trim',
                'ar' => 'تشذيب الشارب'
            ],
            'Face Shave' => [
                'en' => 'Face Shave',
                'ar' => 'حلاقة الوجه'
            ],
            'Beard Trim' => [
                'en' => 'Beard Trim',
                'ar' => 'تشذيب اللحية'
            ],

            // Come Relax section
            'Come, Relax and Enjoy' => [
                'en' => 'Come, Relax and Enjoy',
                'ar' => 'تعال، استرخِ واستمتع'
            ],
            'Place where you will feel peaceful' => [
                'en' => 'Place where you will feel peaceful',
                'ar' => 'مكان حيث ستشعر بالسلام'
            ],
            'Book an Appointment' => [
                'en' => 'Book an Appointment',
                'ar' => 'احجز موعداً'
            ],

            // Pricing section
            'You\'ll Like It Here!' => [
                'en' => 'You\'ll Like It Here!',
                'ar' => 'ستعجبك هنا!'
            ],
            'Our Services & Prices' => [
                'en' => 'Our Services & Prices',
                'ar' => 'خدماتنا وأسعارنا'
            ],
            'Haircut & Style' => [
                'en' => 'Haircut & Style',
                'ar' => 'قصة شعر وتصفيف'
            ],
            'Buzz Cut' => [
                'en' => 'Buzz Cut',
                'ar' => 'قصة بز'
            ],
            'Straight Razor Shave' => [
                'en' => 'Straight Razor Shave',
                'ar' => 'حلاقة بالشفرة المستقيمة'
            ],
            'Head Shave' => [
                'en' => 'Head Shave',
                'ar' => 'حلاقة الرأس'
            ],
            'Kids Cuts (10 & under)' => [
                'en' => 'Kids Cuts (10 & under)',
                'ar' => 'قصات الأطفال (10 سنوات وأقل)'
            ],
            'Back of Neck Razor Cleanup' => [
                'en' => 'Back of Neck Razor Cleanup',
                'ar' => 'تنظيف مؤخرة الرقبة بالشفرة'
            ],
            'Luxury Face Shave' => [
                'en' => 'Luxury Face Shave',
                'ar' => 'حلاقة وجه فاخرة'
            ],
            'Beard Trim with Razor' => [
                'en' => 'Beard Trim with Razor',
                'ar' => 'تشذيب اللحية بالشفرة'
            ],
            'Line Up/Beard Trim' => [
                'en' => 'Line Up/Beard Trim',
                'ar' => 'ترتيب الخطوط/تشذيب اللحية'
            ],
            'Signature Bespoke Facial' => [
                'en' => 'Signature Bespoke Facial',
                'ar' => 'علاج الوجه المخصص الخاص'
            ],
            'Ear & Nose Waxing' => [
                'en' => 'Ear & Nose Waxing',
                'ar' => 'إزالة شعر الأذن والأنف بالشمع'
            ],
            'Weddings Packages' => [
                'en' => 'Weddings Packages',
                'ar' => 'باقات الزفاف'
            ],
            'View All Prices' => [
                'en' => 'View All Prices',
                'ar' => 'عرض جميع الأسعار'
            ],

            // Are you ready section
            'Try Different Things' => [
                'en' => 'Try Different Things',
                'ar' => 'جرب أشياء مختلفة'
            ],
            'Are you ready to make a big change?' => [
                'en' => 'Are you ready to make a big change?',
                'ar' => 'هل أنت مستعد لإجراء تغيير كبير؟'
            ],

            // Working Hours section
            'Time Schedule' => [
                'en' => 'Time Schedule',
                'ar' => 'جدول المواعيد'
            ],
            'Working Hours' => [
                'en' => 'Working Hours',
                'ar' => 'ساعات العمل'
            ],

            // Testimonials section
            'Testimonials' => [
                'en' => 'Testimonials',
                'ar' => 'آراء العملاء'
            ],
            'Comments & Reviews' => [
                'en' => 'Comments & Reviews',
                'ar' => 'التعليقات والمراجعات'
            ],

            // Contact section
            'Salon Hours:' => [
                'en' => 'Salon Hours:',
                'ar' => 'ساعات الصالون:'
            ],
            'Our Location:' => [
                'en' => 'Our Location:',
                'ar' => 'موقعنا:'
            ],

            // Page title (from the view file)
            'mcs.sa - Home' => [
                'en' => 'mcs.sa - Home',
                'ar' => 'رين - الرئيسية'
            ],

            // Additional translations found in the view that weren't in the seeder
            'Monday' => [
                'en' => 'Monday',
                'ar' => 'الإثنين'
            ],
            'Tuesday' => [
                'en' => 'Tuesday',
                'ar' => 'الثلاثاء'
            ],
            'Wednesday' => [
                'en' => 'Wednesday',
                'ar' => 'الأربعاء'
            ],
            'Thursday' => [
                'en' => 'Thursday',
                'ar' => 'الخميس'
            ],
            'Friday' => [
                'en' => 'Friday',
                'ar' => 'الجمعة'
            ],
            'Saturday' => [
                'en' => 'Saturday',
                'ar' => 'السبت'
            ],
            'Sunday' => [
                'en' => 'Sunday',
                'ar' => 'الأحد'
            ],
            'Mon – Wed' => [
                'en' => 'Mon – Wed',
                'ar' => 'الإثنين - الأربعاء'
            ],
            'Sun - Sun' => [
                'en' => 'Sun - Sun',
                'ar' => 'الأحد - الأحد'
            ]
        ];

        // Create translations for both languages
        foreach ($translations as $key => $values) {
            // English
            Translation::updateOrCreate(
                [
                    'language_id' => $englishLanguage->id,
                    'key_name' => $key,
                ],
                [
                    'group' => 'home',
                    'translation' => $values['en'],
                ]
            );

            // Arabic
            Translation::updateOrCreate(
                [
                    'language_id' => $arabicLanguage->id,
                    'key_name' => $key,
                ],
                [
                    'group' => 'home',
                    'translation' => $values['ar'],
                ]
            );
        }

        $this->command->info('Home page translations created successfully for English and Arabic languages.');
    }
}
