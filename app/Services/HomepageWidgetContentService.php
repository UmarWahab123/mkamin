<?php

namespace App\Services;

use Filawidget\Services\AreaService;
use App\Helpers\WidgetHelper;
use Illuminate\Support\Facades\Cache;
use App\Models\ServiceCategory;
use App\Models\PointOfSale;
use App\Models\ReservationSetting;
use Carbon\Carbon;

class HomepageWidgetContentService
{
    /**
     * Get all homepage widget content data
     *
     * @param int $cacheTime Cache time in minutes
     * @return array
     */
    public function getHomepageContent($cacheTime = 60)
    {
        // Cache the data for specified minutes (default: 60 minutes)
        return Cache::remember('home_page_data', $cacheTime * 60, function () {
            $areas = AreaService::getAllAreas();

            return [
                'areas' => $this->getOtherAreas($areas),
                'heroWidgets' => $this->processHeroWidgets($areas),
                'textContent' => $this->processTextContent1($areas),
                'textContent2' => $this->processTextContent2($areas),
                'servicesContent' => $this->processServicesSection($areas),
                'textContent3' => $this->processTextContent3($areas),
                'textContent4' => $this->processTextContent4($areas),
                'workingHoursContent' => $this->processWorkingHoursSection($areas),
                'contactSectionContent' => $this->processContactSection($areas),
                'serviceCategories' => $this->getServiceCategories(),
                'wideImage' => $this->processWideImageSection($areas),
                'buttonTextColor' => $this->getButtonTextColor($this->processHeroWidgets($areas)),
                'workingHours' => $this->getWorkingHours(),
            ];
        });
    }

    /**
     * Process Hero Section widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processHeroWidgets($areas)
    {
        $heroArea = $areas->firstWhere('name', 'Home Page Hero Section');
        $heroWidgets = $heroArea ? $heroArea->widgets : collect([]);

        // Process the heroWidgets to extract all needed data for each slide
        $processedHeroWidgets = [];
        foreach ($heroWidgets as $key => $widget) {
            $processedHeroWidgets[] = [
                'key' => $key,
                'smallTitle' => WidgetHelper::getFieldValue($widget, 'Slide Small Title'),
                'title' => WidgetHelper::getFieldValue($widget, 'Slide Title'),
                'buttonText' => WidgetHelper::getFieldValue($widget, 'Slide Button Text'),
                'buttonUrl' => WidgetHelper::getFieldValue($widget, 'Slide Button URL'),
                'smallTitleColor' => WidgetHelper::getFieldValue($widget, 'Slide Small Title Color', '#ffffff'),
                'titleColor' => WidgetHelper::getFieldValue($widget, 'Slide Title Color', '#ffffff'),
                'buttonTextColor' => WidgetHelper::getFieldValue($widget, 'Slide Button Text Color', '#ffffff'),
                'buttonBgColor' => WidgetHelper::getFieldValue($widget, 'Slide Button Background Color', '#d4af37'),
                'bgImage' => WidgetHelper::getFieldValue($widget, 'Slide Background Image'),
            ];
        }

        return $processedHeroWidgets;
    }

    /**
     * Process Text Content 1 widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processTextContent1($areas)
    {
        $textContentArea = $areas->firstWhere('name', 'Home Page Text Content 1');
        $textWidget = $textContentArea && $textContentArea->widgets->isNotEmpty() ? $textContentArea->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($textWidget, 'text content 1 small title'),
            'title' => WidgetHelper::getFieldValue($textWidget, 'text content 1 title'),
            'description' => WidgetHelper::getFieldValue($textWidget, 'text content 1 description'),
            'image' => WidgetHelper::getFieldValue($textWidget, 'text content 1 image'),
        ];
    }

    /**
     * Process Text Content 2 widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processTextContent2($areas)
    {
        $textContent2Area = $areas->firstWhere('name', 'Home Page Text Content 2');
        $textWidget2 = $textContent2Area && $textContent2Area->widgets->isNotEmpty() ? $textContent2Area->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 small title'),
            'title' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 title'),
            'cardTitle' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 card title'),
            'cardDescription' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 card description'),
            'image1' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 image 1'),
            'image2' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 image 2'),
            'image3' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 image 3'),
            'smallTitleColor' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 small title color', '#d4af37'),
            'titleColor' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 title color', '#333333'),
            'cardTitleColor' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 card title color', '#333333'),
            'cardDescriptionColor' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 card description color', '#333333'),
            'cardBackgroundColor' => WidgetHelper::getFieldValue($textWidget2, 'text content 2 card background color', '#f5f5f5'),
        ];
    }

    /**
     * Process Services Section widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processServicesSection($areas)
    {
        $servicesArea = $areas->firstWhere('name', 'Home Page Services Section');
        $servicesWidget = $servicesArea && $servicesArea->widgets->isNotEmpty() ? $servicesArea->widgets->first() : null;

        return [
            'services' => [
                [
                    'title' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 1 title'),
                    'description' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 1 description'),
                    'image' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 1 image'),
                    'icon' => 'flaticon-hairstyle-1'
                ],
                [
                    'title' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 2 title'),
                    'description' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 2 description'),
                    'image' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 2 image'),
                    'icon' => 'flaticon-razor'
                ],
                [
                    'title' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 3 title'),
                    'description' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 3 description'),
                    'image' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 3 image'),
                    'icon' => 'flaticon-shaving-brush'
                ],
                [
                    'title' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 4 title'),
                    'description' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 4 description'),
                    'image' => WidgetHelper::getFieldValue($servicesWidget, 'home page service 4 image'),
                    'icon' => 'flaticon-electric-razor'
                ]
            ],
            'titleColor' => WidgetHelper::getFieldValue($servicesWidget, 'home page all services title color', '#333333'),
            'descriptionColor' => WidgetHelper::getFieldValue($servicesWidget, 'home page all services description color', '#333333')
        ];
    }

    /**
     * Process Text Content 3 widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processTextContent3($areas)
    {
        $textContent3Area = $areas->firstWhere('name', 'Home Page Text Content 3');
        $textWidget3 = $textContent3Area && $textContent3Area->widgets->isNotEmpty() ? $textContent3Area->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 small title'),
            'title' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 title'),
            'description' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 description'),
            'buttonText' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 button text'),
            'buttonUrl' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 button url'),
            'smallTitleColor' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 small title color', '#ffffff'),
            'titleColor' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 title color', '#ffffff'),
            'descriptionColor' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 description color', '#ffffff'),
            'buttonTextColor' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 button text color', '#ffffff'),
            'buttonBgColor' => WidgetHelper::getFieldValue($textWidget3, 'home page text content 3 button background color', '#d4af37'),
        ];
    }

    /**
     * Process Wide Image Section widget
     *
     * @param \Illuminate\Support\Collection $areas
     * @return string|null
     */
    protected function processWideImageSection($areas)
    {
        $wideImageArea = $areas->firstWhere('name', 'Home Page Wide Image Section');
        $wideImageWidget = $wideImageArea && $wideImageArea->widgets->isNotEmpty() ? $wideImageArea->widgets->first() : null;

        return WidgetHelper::getFieldValue($wideImageWidget, 'home page wide image section image');
    }

    /**
     * Process Text Content 4 widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processTextContent4($areas)
    {
        $textContent4Area = $areas->firstWhere('name', 'Home Page Text Content 4');
        $textWidget4 = $textContent4Area && $textContent4Area->widgets->isNotEmpty() ? $textContent4Area->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 small title'),
            'title' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 title'),
            'description1' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 description1'),
            'description2' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 description2'),
            'image' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 image'),
            'backgroundColor' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 background color', '#f5f5f5'),
            'smallTitleColor' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 small title color', '#d4af37'),
            'titleColor' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 title color', '#333333'),
            'description1Color' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 description1 color', '#333333'),
            'description2Color' => WidgetHelper::getFieldValue($textWidget4, 'home page text content 4 description2 color', '#333333'),
        ];
    }

    /**
     * Process Working Hours Section widget
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processWorkingHoursSection($areas)
    {
        $workingHoursArea = $areas->firstWhere('name', 'Home Page Working Hours Section');
        $workingHoursWidget = $workingHoursArea && $workingHoursArea->widgets->isNotEmpty() ? $workingHoursArea->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours small title'),
            'title' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours title'),
            'description' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours description'),
            'smallTitleColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours small title color', '#d4af37'),
            'titleColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours title color', '#333333'),
            'descriptionColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours description color', '#333333'),
            'dayNameColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours day name color', '#333333'),
            'timeColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'home page working hours time color', '#333333'),
        ];
    }

    /**
     * Process Contact Section widget
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processContactSection($areas)
    {
        $contactSectionArea = $areas->firstWhere('name', 'Home Pag Contact Section');
        $contactSectionWidget = $contactSectionArea && $contactSectionArea->widgets->isNotEmpty() ? $contactSectionArea->widgets->first() : null;

        return [
            'hoursTitle' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section hours title'),
            'locationTitle' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section location title'),
            'locationEn' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section location en'),
            'locationAr' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section location ar'),
            'phoneNo1' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section phone no 1'),
            'phoneNo2' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section phone no 2'),
            'mapSrc' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section embaded map src link'),
            'textColor' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section text color', '#ffffff'),
            'backgroundColor' => WidgetHelper::getFieldValue($contactSectionWidget, 'home page contact section background color', '#000000'),
        ];
    }

    /**
     * Get other areas not processed individually
     *
     * @param \Illuminate\Support\Collection $areas
     * @return \Illuminate\Support\Collection
     */
    protected function getOtherAreas($areas)
    {
        return $areas->filter(function($area) {
            return !in_array($area->name, [
                'Home Page Hero Section',
                'Home Page Text Content 1',
                'Home Page Text Content 2',
                'Home Page Services Section',
                'Home Page Text Content 3',
                'Home Page Wide Image Section',
                'Home Page Text Content 4',
                'Home Page Working Hours Section',
                'Home Pag Contact Section'
            ]);
        });
    }

    /**
     * Get service categories with products and services for pricing display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getServiceCategories()
    {
        return ServiceCategory::with(['productAndServices' => function($query) {
            $query->where('is_active', true)
                ->orderBy('sort_order')
                ->take(6); // Limit to 6 services per category
        }])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->take(2) // Get only 2 categories
        ->get();
    }

    /**
     * Get button text color from hero widgets
     *
     * @param array $processedHeroWidgets
     * @return string
     */
    protected function getButtonTextColor($processedHeroWidgets)
    {
        return isset($processedHeroWidgets[0]) ? $processedHeroWidgets[0]['buttonTextColor'] : '#ffffff';
    }

    /**
     * Get working hours data
     *
     * @return array
     */
    protected function getWorkingHours()
    {
        // Initialize workingHours array with default values
        $workingHours = [
            0 => ['day' => 'Monday', 'time' => 'Closed'],
            1 => ['day' => 'Tuesday', 'time' => 'Closed'],
            2 => ['day' => 'Wednesday', 'time' => 'Closed'],
            3 => ['day' => 'Thursday', 'time' => 'Closed'],
            4 => ['day' => 'Friday', 'time' => 'Closed'],
            5 => ['day' => 'Saturday', 'time' => 'Closed'],
            6 => ['day' => 'Sunday', 'time' => 'Closed'],
        ];

        $mainBranchPos = PointOfSale::where('is_main_branch', true)
            ->where('is_active', true)
            ->first();

        if ($mainBranchPos) {
            // Get the next 7 days settings for the main branch
            $today = now()->startOfDay();
            $reservationSettings = ReservationSetting::where('point_of_sale_id', $mainBranchPos->id)
                ->where('date', '>=', $today)
                ->orderBy('date')
                ->take(7)
                ->get();

            foreach ($reservationSettings as $setting) {
                $dayOfWeek = (int) $setting->day_of_week;
                $dayIndex = $dayOfWeek == 0 ? 6 : $dayOfWeek - 1; // Convert to 0-6 index where 0 is Monday

                if ($setting->is_closed) {
                    $workingHours[$dayIndex]['time'] = 'Closed';
                } else {
                    $openingTime = Carbon::parse($setting->opening_time)->format('g:i A');
                    $closingTime = Carbon::parse($setting->closing_time)->format('g:i A');
                    $workingHours[$dayIndex]['time'] = "{$openingTime} - {$closingTime}";
                }
            }
        }

        return $workingHours;
    }
}
