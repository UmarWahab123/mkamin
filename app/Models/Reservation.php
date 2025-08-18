<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reservation_date',
        'start_time',
        'end_time',
        'total_price',
        'total_price_home',
        'product_and_services_data',
        'status',
        'notes',
        'is_home_service',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'product_and_services_data' => 'array',
        'is_home_service' => 'boolean',
    ];

    /**
     * Get the customer who made the reservation
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get formatted start time
     */
    public function getStartTimeFormattedAttribute()
    {
        return Carbon::parse($this->start_time)->format('h:i A');
    }

    /**
     * Get formatted end time
     */
    public function getEndTimeFormattedAttribute()
    {
        return Carbon::parse($this->end_time)->format('h:i A');
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::saving(function ($reservation) {
            if (empty($reservation->end_time) && !empty($reservation->start_time)) {
                $totalDuration = collect($reservation->product_and_services_data)
                    ->sum('duration_minutes');
                $endTime = Carbon::parse($reservation->start_time)
                    ->addMinutes($totalDuration);
                $reservation->end_time = $endTime->format('H:i:s');
            }
        });
    }

    /**
     * Check if a timeslot is available for a new reservation
     *
     * @param string $date
     * @param string $startTime
     * @param int $durationMinutes
     * @param int|null $currentReservationId
     * @return bool
     */
    public static function isTimeSlotAvailable($date, $startTime, $durationMinutes, $currentReservationId = null)
    {
        $setting = ReservationSetting::getSettingForDate($date);

        if (!$setting || $setting->is_closed) {
            return false;
        }

        // Calculate end time
        $startTimeObj = Carbon::parse($startTime);
        $endTimeObj = (clone $startTimeObj)->addMinutes($durationMinutes);

        // Check if within opening hours
        $openingTimeObj = Carbon::parse($setting->opening_time);
        $closingTimeObj = Carbon::parse($setting->closing_time);

        if ($startTimeObj < $openingTimeObj || $endTimeObj > $closingTimeObj) {
            return false;
        }

        // Count overlapping reservations
        $overlappingCount = self::where('reservation_date', $date)
            ->where('id', '!=', $currentReservationId)
            ->where(function ($query) use ($startTime, $endTimeObj) {
                $endTime = $endTimeObj->format('H:i:s');

                $query->where(function ($q) use ($startTime, $endTime) {
                    // Reservation starts during our slot
                    $q->where('start_time', '>=', $startTime)
                      ->where('start_time', '<', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Reservation ends during our slot
                    $q->where('end_time', '>', $startTime)
                      ->where('end_time', '<=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Reservation completely contains our slot
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>=', $endTime);
                });
            })
            ->count();

        return $overlappingCount < $setting->workers_count;
    }
}
