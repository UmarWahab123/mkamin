<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservationSetting;
use App\Models\Staff;
use App\Models\TimeInterval;
use App\Models\BookedReservationItem;
use App\Models\ProductAndService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Get available dates for a specific point of sale
     *
     * @param int $pointOfSaleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableDates($pointOfSaleId)
    {
        try {
            // Get all enabled dates (where is_closed is 0) for the selected point of sale
            $enabledDates = ReservationSetting::where('point_of_sale_id', $pointOfSaleId)
                ->where('is_closed', 0)
                ->where('date', '>=', Carbon::today())
                ->pluck('date')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();

            // If there are no enabled dates, return empty array
            if (empty($enabledDates)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No available dates found'
                ]);
            }

            // Get all staff members associated with this point of sale
            $staffMembers = Staff::whereHas('pointOfSale', function ($query) use ($pointOfSaleId) {
                $query->where('point_of_sales.id', $pointOfSaleId);
            })->get();

            // Filter dates to only include those where at least one staff member is available
            $availableDates = collect($enabledDates)->filter(function ($date) use ($staffMembers) {
                // Check if any staff member has a time interval for this date
                return $staffMembers->contains(function ($staff) use ($date) {
                    return $staff->timeIntervals()
                        ->where('date', $date)
                        ->where('is_closed', false)
                        ->exists();
                });
            })->values()->toArray();

            return response()->json([
                'success' => true,
                'data' => $availableDates,
                'message' => 'Available dates retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available dates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available dates based on service and location type
     *
     * @param int $pointOfSaleId
     * @param int $serviceId
     * @param string $locationType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableDatesBasedOnServiceAndLocation($pointOfSaleId, $serviceId, $locationType)
    {
        try {
            // Get all enabled dates (where is_closed is 0) for the selected point of sale
            $enabledDates = ReservationSetting::where('point_of_sale_id', $pointOfSaleId)
                ->where('is_closed', 0)
                ->where('date', '>=', Carbon::today())
                ->pluck('date')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();
            // If there are no enabled dates, return empty array
            if (empty($enabledDates)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No available dates found'
                ]);
            }

            // Get the service duration
            $service = ProductAndService::findOrFail($serviceId);
            $serviceDuration = $service->duration_minutes;

            // Get all staff members associated with this point of sale
            $staffMembers = Staff::whereHas('pointOfSale', function ($query) use ($pointOfSaleId) {
                $query->where('point_of_sales.id', $pointOfSaleId);
            })
                ->whereHas('productAndServices', function ($query) use ($serviceId) {
                    $query->where('product_and_services.id', $serviceId);
                })
                ->get();

            // Filter dates to only include those where at least one staff member is available
            $availableDates = collect($enabledDates)->filter(function ($date) use ($staffMembers, $serviceDuration, $locationType) {
                // Check if any staff member has a time interval for this date and can accommodate the service
                return $staffMembers->contains(function ($staff) use ($date, $serviceDuration, $locationType) {
                    $timeInterval = $staff->timeIntervals()
                        ->where('date', $date)
                        ->where('is_closed', false);

                    // Add can_visit_home filter for home location type
                    if ($locationType === 'home') {
                        $timeInterval->where('can_visit_home', true);
                    }

                    $timeInterval = $timeInterval->first();

                    if (!$timeInterval) {
                        return false;
                    }

                    // Get all booked slots for this staff on the selected date
                    $bookedSlots = BookedReservationItem::where('staff_id', $staff->id)

                        ->where('appointment_date', $date)
                        ->whereHas('bookedReservation', function ($query) {
                            $query->whereIn('status', ['completed', 'confirmed']);
                        })
                        ->get()
                        ->map(function ($slot) {
                            return [
                                'start' => Carbon::parse($slot->start_time),
                                'end' => Carbon::parse($slot->end_time)
                            ];
                        });

                    // Check if there's any available time slot that can fit the service
                    $workingStart = Carbon::parse($timeInterval->start_time);
                    $workingEnd = Carbon::parse($timeInterval->end_time);

                    // Sort booked slots by start time
                    $bookedSlots = $bookedSlots->sortBy('start');

                    // Check each potential time slot
                    $currentTime = $workingStart->copy();
                    foreach ($bookedSlots as $slot) {
                        // If there's enough time between current time and the next booking
                        if ($currentTime->copy()->addMinutes($serviceDuration) <= $slot['start']) {
                            return true;
                        }
                        $currentTime = $slot['end'];
                    }

                    // Check if there's enough time after the last booking
                    return $currentTime->copy()->addMinutes($serviceDuration) <= $workingEnd;
                });
            })->values()->toArray();

            return response()->json([
                'success' => true,
                'data' => $availableDates,
                'message' => 'Available dates retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available dates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available staff for a specific date and service
     *
     * @param int $pointOfSaleId
     * @param string $date
     * @param int $serviceId
     * @param string $locationType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableStaff($pointOfSaleId, $date, $serviceId, $locationType)
    {
        try {
            // Validate the date
            $selectedDate = Carbon::parse($date);

            // Get the service duration
            $service = ProductAndService::findOrFail($serviceId);
            $serviceDuration = $service->duration_minutes;

            // Get staff members associated with this point of sale and available on the selected date
            $query = Staff::whereHas('pointOfSale', function ($query) use ($pointOfSaleId) {
                $query->where('point_of_sales.id', $pointOfSaleId);
            })
                ->whereHas('timeIntervals', function ($query) use ($selectedDate, $locationType) {
                    $query->where('date', $selectedDate->format('Y-m-d'))
                        ->where('is_closed', false);

                    // Add can_visit_home filter for home location type
                    if ($locationType === 'home') {
                        $query->where('can_visit_home', true);
                    }
                })
                ->whereHas('productAndServices', function ($query) use ($serviceId) {
                    $query->where('product_and_services.id', $serviceId);
                })
                ->with(['timeIntervals' => function ($query) use ($selectedDate) {
                    $query->where('date', $selectedDate->format('Y-m-d'))
                        ->where('is_closed', false);
                }]);

            $availableStaff = $query->get();

            // Format the result for the frontend and check availability
            $formattedStaff = $availableStaff->map(function ($staff) use ($selectedDate, $serviceDuration) {
                $timeInterval = $staff->timeIntervals->first();

                // Get all booked slots for this staff on the selected date
                $bookedSlots = BookedReservationItem::where('staff_id', $staff->id)

                    ->where('appointment_date', $selectedDate->format('Y-m-d'))
                    ->whereHas('bookedReservation', function ($query) {
                        $query->whereIn('status', ['completed', 'confirmed']);
                    })
                    ->get()
                    ->map(function ($slot) {
                        return [
                            'start' => Carbon::parse($slot->start_time),
                            'end' => Carbon::parse($slot->end_time)
                        ];
                    });

                // Check if there's any available time slot that can fit the service
                $hasAvailableSlot = false;
                $workingStart = Carbon::parse($timeInterval->start_time);
                $workingEnd = Carbon::parse($timeInterval->end_time);

                // Sort booked slots by start time
                $bookedSlots = $bookedSlots->sortBy('start');

                // Check each potential time slot
                $currentTime = $workingStart->copy();
                foreach ($bookedSlots as $slot) {
                    // If there's enough time between current time and the next booking
                    if ($currentTime->copy()->addMinutes($serviceDuration) <= $slot['start']) {
                        $hasAvailableSlot = true;
                        break;
                    }
                    $currentTime = $slot['end'];
                }

                // Check if there's enough time after the last booking
                if (!$hasAvailableSlot && $currentTime->copy()->addMinutes($serviceDuration) <= $workingEnd) {
                    $hasAvailableSlot = true;
                }

                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'position' => $staff->position,
                    'working_hours' => [
                        'start_time' => Carbon::parse($timeInterval->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($timeInterval->end_time)->format('H:i'),
                    ],
                    'is_available' => $hasAvailableSlot
                ];
            })->filter(function ($staff) {
                return $staff['is_available'];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $formattedStaff,
                'message' => 'Available staff retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get staff schedule (working hours and booked slots)
     *
     * @param int $staffId
     * @param string $date
     * @param int $serviceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaffSchedule($staffId, $date, $serviceId)
    {
        try {
            // Validate the date
            $selectedDate = Carbon::parse($date);

            // Get the staff time interval for this date
            $timeInterval = TimeInterval::where('timeable_id', $staffId)
                ->where('timeable_type', Staff::class)
                ->where('date', $selectedDate->format('Y-m-d'))
                ->where('is_closed', false)
                ->first();

            if (!$timeInterval) {
                return response()->json([
                    'success' => false,
                    'message' => 'Staff is not available on this date'
                ], 404);
            }

            // Format working hours
            $workingHours = [
                'start_time' => Carbon::parse($timeInterval->start_time)->format('H:i'),
                'end_time' => Carbon::parse($timeInterval->end_time)->format('H:i'),
                'formatted_start' => Carbon::parse($timeInterval->start_time)->format('h:i A'),
                'formatted_end' => Carbon::parse($timeInterval->end_time)->format('h:i A'),
            ];

            // Get booked slots for this staff and date
            $bookedSlots = BookedReservationItem::where('staff_id', $staffId)
                ->whereHas('bookedReservation', function ($query) {
                    $query->whereIn('status', ['completed', 'confirmed']);
                })
                ->where('appointment_date', $selectedDate->format('Y-m-d'))
                ->get()
                ->map(function ($slot) {
                    return [
                        'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                        'formatted_start' => Carbon::parse($slot->start_time)->format('h:i A'),
                        'formatted_end' => Carbon::parse($slot->end_time)->format('h:i A'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'working_hours' => $workingHours,
                    'booked_slots' => $bookedSlots
                ],
                'message' => 'Staff schedule retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving staff schedule: ' . $e->getMessage()
            ], 500);
        }
    }
}
