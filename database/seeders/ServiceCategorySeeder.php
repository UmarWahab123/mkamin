<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Cutting & Styling',
                'name_ar' => 'قص وتصفيف',
                'description_en' => 'Professional hair cutting and styling services',
                'description_ar' => 'خدمات قص وتصفيف الشعر الاحترافية',
                'is_active' => true,
                'sort_order' => 1,
                'point_of_sale_id' => 1,
            ],
            [
                'name_en' => 'Hair Coloring',
                'name_ar' => 'تلوين الشعر',
                'description_en' => 'Professional hair coloring and treatment services',
                'description_ar' => 'خدمات تلوين وعلاج الشعر الاحترافية',
                'is_active' => true,
                'sort_order' => 2,
                'point_of_sale_id' => 1,
            ],
            [
                'name_en' => 'Hair Treatments',
                'name_ar' => 'علاجات الشعر',
                'description_en' => 'Professional hair treatment and care services',
                'description_ar' => 'خدمات علاج والعناية بالشعر الاحترافية',
                'is_active' => true,
                'sort_order' => 3,
                'point_of_sale_id' => 2,
            ],
            [
                'name_en' => 'Hair Extensions',
                'name_ar' => 'تمديد الشعر',
                'description_en' => 'Professional hair extension services',
                'description_ar' => 'خدمات تمديد الشعر الاحترافية',
                'is_active' => true,
                'sort_order' => 4,
                'point_of_sale_id' => 2,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
