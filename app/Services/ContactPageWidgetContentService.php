<?php

namespace App\Services;

use Filawidget\Services\AreaService;
use App\Helpers\WidgetHelper;
use Illuminate\Support\Facades\Cache;
use App\Models\PointOfSale;
use App\Models\ReservationSetting;
use Carbon\Carbon;

class ContactPageWidgetContentService
{
    /**
     * Get all contact page widget content data
     *
     * @param int $cacheTime Cache time in minutes
     * @return array
     */
    public function getContactPageContent($cacheTime = 60)
    {
        // Cache the data for specified minutes (default: 60 minutes)
        return Cache::remember('contact_page_data', $cacheTime * 60, function () {
            $areas = AreaService::getAllAreas();

            return [
                'workingHoursContent' => $this->processWorkingHoursSection($areas),
                'workingHours' => $this->getWorkingHours(),
                'contactSectionContent' => $this->processContactSection($areas),
            ];
        });
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
