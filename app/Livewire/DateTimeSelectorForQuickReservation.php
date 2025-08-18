<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ReservationSetting;
use App\Models\Staff;
use App\Models\TimeInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DateTimeSelectorForQuickReservation extends Component
{
    public $selectedDate = null;
    public $disabledDates = [];
    public $pointOfSaleId = null;
    public $availableStaff = [];
    public $staffWorkingHours = [];
    public $locationType = 'salon';

    protected $listeners = [
        'pointOfSaleChanged' => 'updatePointOfSale',
        'staffSelected' => 'onStaffSelected',
        'locationTypeChanged' => 'updateLocationType'
    ];

    public function mount($pointOfSaleId = null)
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        // If passed a pointOfSaleId parameter, set it
        if ($pointOfSaleId) {
            $this->pointOfSaleId = $pointOfSaleId;
        }

        $this->loadDisabledDates();
        $this->loadAvailableStaff();
        $this->dispatch('dateSelected', $this->selectedDate);
    }

    public function updatePointOfSale($pointOfSaleId)
    {
        $this->pointOfSaleId = $pointOfSaleId;
        $this->loadDisabledDates();
        $this->loadAvailableStaff();

        // Force UI refresh and pass the disabled dates
        $this->dispatch('refresh-date-selector', $this->disabledDates);
    }

    public function onStaffSelected($staffId, $serviceId)
    {
        $this->loadStaffWorkingHours($staffId, $serviceId);
    }

    public function updateLocationType($locationType)
    {
        $this->locationType = $locationType;
        $this->loadDisabledDates();
        $this->loadAvailableStaff();

        // Force UI refresh and pass the disabled dates
        $this->dispatch('refresh-date-selector', $this->disabledDates);
    }

    public function loadDisabledDates()
    {
        // If no point of sale is selected, don't try to load disabled dates
        if (!$this->pointOfSaleId) {
            // Set all dates as enabled when no point of sale is selected
            $this->disabledDates = [];
            return;
        }

        // Get all enabled dates (where is_closed is 0) for the selected point of sale
        $enabledDates = ReservationSetting::where('point_of_sale_id', $this->pointOfSaleId)
            ->where('is_closed', 0)
            ->where('date', '>=', Carbon::today())
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // If there are no enabled dates, disable all dates
        if (empty($enabledDates)) {
            $this->disabledDates = [
                [
                    'from' => Carbon::today()->format('Y-m-d'),
                    'to' => Carbon::today()->addYears(10)->format('Y-m-d')
                ]
            ];
            return;
        }
        $pointOfSaleId = $this->pointOfSaleId;
        // Get all staff members associated with this point of sale
        $staffMembers = Staff::whereHas('pointOfSale', function ($query) use ($pointOfSaleId) {
            $query->where('point_of_sales.id', $pointOfSaleId);
        })->get();

        // Filter dates to only include those where at least one staff member is available
        $availableDates = collect($enabledDates)->filter(function ($date) use ($staffMembers) {
            // Check if any staff member has a time interval for this date
            return $staffMembers->contains(function ($staff) use ($date) {
                $query = $staff->timeIntervals()
                    ->where('date', $date)
                    ->where('is_closed', false);

                // Add can_visit_home filter for home location type
                if ($this->locationType === 'home') {
                    $query->where('can_visit_home', true);
                }

                return $query->exists();
            });
        })->values()->toArray();
        // Create date ranges for disabled dates
        $disabledRanges = [];
        $startDate = Carbon::today();

        foreach ($availableDates as $enabledDate) {
            $enabledCarbon = Carbon::parse($enabledDate);

            // If there's a gap between startDate and enabledDate, add it as a disabled range
            if ($startDate->lt($enabledCarbon)) {
                $disabledRanges[] = [
                    'from' => $startDate->format('Y-m-d'),
                    'to' => $enabledCarbon->copy()->subDay()->format('Y-m-d')
                ];
            }

            // Move startDate to the day after the enabled date
            $startDate = $enabledCarbon->copy()->addDay();
        }

        // Add the remaining dates after the last enabled date as disabled
        if ($startDate->lte(Carbon::today()->addYears(10))) {
            $disabledRanges[] = [
                'from' => $startDate->format('Y-m-d'),
                'to' => Carbon::today()->addYears(10)->format('Y-m-d')
            ];
        }

        $this->disabledDates = $disabledRanges;
    }

    public function updatedSelectedDate($value)
    {
        $this->dispatch('dateSelected', $value);
        $this->dispatch('dateChanged');
        $this->loadAvailableStaff();
    }

    protected function loadAvailableStaff()
    {
        // If no point of sale is selected, don't try to load staff
        if (!$this->pointOfSaleId) {
            $this->availableStaff = [];
            $this->dispatch('staffAvailable', $this->availableStaff);
            return;
        }

        // Get staff members associated with this point of sale and available on the selected date
        $query = Staff::whereHas('pointOfSale', function ($query) {
            $query->where('point_of_sales.id', $this->pointOfSaleId);
        })
            ->whereHas('timeIntervals', function ($query) {
                $query->where('date', $this->selectedDate)
                    ->where('is_closed', false);

                // Add can_visit_home filter for home location type
                if ($this->locationType === 'home') {
                    $query->where('can_visit_home', true);
                }
            });

        $this->availableStaff = $query->get();

        // Dispatch the available staff to the parent component
        $this->dispatch('staffAvailable', $this->availableStaff);
    }

    protected function loadStaffWorkingHours($staffId, $serviceId = null)
    {
        if (!$staffId || !$this->selectedDate) {
            return;
        }

        $timeInterval = TimeInterval::where('timeable_id', $staffId)
            ->where('timeable_type', Staff::class)
            ->where('date', $this->selectedDate)
            ->where('is_closed', false)
            ->first();

        if ($timeInterval) {
            $workingHours = [
                'staff_id' => $staffId,
                'service_id' => $serviceId,
                'start_time' => Carbon::parse($timeInterval->start_time)->format('h:i A'),
                'end_time' => Carbon::parse($timeInterval->end_time)->format('h:i A')
            ];

            $this->staffWorkingHours[$staffId] = $workingHours;

            $this->dispatch('staffWorkingHoursLoaded', $workingHours);
        } else {
            Log::warning('No time intervals found for staff', [
                'staff_id' => $staffId,
                'service_id' => $serviceId,
                'date' => $this->selectedDate
            ]);
        }
    }

    public function render()
    {
        return view('livewire.date-time-selector-for-quick-reservation');
    }
}
