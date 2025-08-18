<?php

namespace App\Services;

use Filawidget\Services\AreaService;
use App\Helpers\WidgetHelper;
use Illuminate\Support\Facades\Cache;
use App\Models\ServiceCategory;
use App\Models\PointOfSale;
use App\Models\ReservationSetting;
use Carbon\Carbon;

class MenupageWidgetContentService
{
    /**
     * Get all menupage widget content data
     *
     * @param int $cacheTime Cache time in minutes
     * @return array
     */
    public function getMenupageContent($cacheTime = 60)
    {
        // Cache the data for specified minutes (default: 60 minutes)
        return Cache::remember('menu_page_data', (int)$cacheTime * 60, function () {
            $areas = AreaService::getAllAreas();

            return [
                'pricingContent' => $this->processPricingSection($areas),
                'workingHoursContent' => $this->processWorkingHoursSection($areas),
                'workingHours' => $this->getWorkingHours(),
            ];
        });
    }

    /**
     * Process Pricing Section widgets
     *
     * @param \Illuminate\Support\Collection $areas
     * @return array
     */
    protected function processPricingSection($areas)
    {
        $pricingArea = $areas->firstWhere('name', 'Menu Page Pricing Section');
        $pricingWidget = $pricingArea && $pricingArea->widgets->isNotEmpty() ? $pricingArea->widgets->first() : null;

        return [
            'title' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing title', 'Our Services'),
            'subtitle' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing subtitle', 'It\'s time to give your hair some love'),
            'buttonText' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing button text', 'Book Online'),
            'buttonUrl' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing button url', route('salon-services')),
            'titleColor' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing title color', '#333333'),
            'subtitleColor' => WidgetHelper::getFieldValue($pricingWidget, 'menu page pricing subtitle color', '#333333'),
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
        $workingHoursArea = $areas->firstWhere('name', 'Menu Page Working Hours Section');
        $workingHoursWidget = $workingHoursArea && $workingHoursArea->widgets->isNotEmpty() ? $workingHoursArea->widgets->first() : null;

        return [
            'smallTitle' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours small title', 'Time Schedule'),
            'title' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours title', 'Working Hours'),
            'description' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours description', 'Visit us during our convenient operating hours to experience our premium salon services. Our professional team is ready to welcome you and provide exceptional care for all your beauty needs. Check our schedule below to plan your visit.'),
            'smallTitleColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours small title color', '#d4af37'),
            'titleColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours title color', '#333333'),
            'descriptionColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours description color', '#333333'),
            'dayNameColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours day name color', '#333333'),
            'timeColor' => WidgetHelper::getFieldValue($workingHoursWidget, 'menu page working hours time color', '#333333'),
        ];
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
