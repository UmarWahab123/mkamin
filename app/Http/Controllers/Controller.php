<?php

namespace App\Http\Controllers;

use App\Models\BookedReservationItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;




        /**
     * Check if there's an overlap between the requested appointment time and existing appointments
     *
     * @param int $staffId The staff ID
     * @param string $appointmentDate The appointment date
     * @param string $startTime The start time
     * @param string $endTime The end time
     * @param int|null $excludeItemId Optional item ID to exclude from check (for updates)
     * @return bool Returns true if there's an overlap, false otherwise
     */
    public function hasAppointmentOverlap($staffId, $appointmentDate, $startTime, $endTime, $excludeItemId = null)
    {
        if (!$staffId || !$appointmentDate || !$startTime || !$endTime) {
            return false; // Cannot check overlap without required data
        }

        // Get all non-pending, non-cancelled reservations with same staff and date
        $query = BookedReservationItem::whereHas('bookedReservation', function($query) {
                $query->whereNotIn('status', ['pending', 'cancelled']);
            })
            ->where('staff_id', $staffId)
            ->where('appointment_date', $appointmentDate);

        // Exclude the current item if provided (useful for updating)
        if ($excludeItemId) {
            $query->where('id', '!=', $excludeItemId);
        }

        $conflictingItems = $query->get();

        foreach ($conflictingItems as $conflictingItem) {
            // Check for time overlap
            $itemStart = strtotime($startTime);
            $itemEnd = strtotime($endTime);
            $conflictStart = strtotime($conflictingItem->start_time);
            $conflictEnd = strtotime($conflictingItem->end_time);

            if (($itemStart < $conflictEnd) && ($itemEnd > $conflictStart)) {
                return true; // Overlap found
            }
        }

        return false; // No overlap
    }
}
