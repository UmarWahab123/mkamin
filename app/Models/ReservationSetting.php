<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class ReservationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_of_sale_id',
        'date',
        'day_of_week',
        'opening_time',
        'closing_time',
        'workers_count',
        'is_closed'
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    /**
     * Get the point of sale this setting belongs to
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    /**
     * Get the staff members assigned to this reservation setting
     */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'reservation_setting_staff');
    }

    /**
     * Get the setting for a specific date
     *
     * @param string $date
     * @param int $pointOfSaleId
     * @return ReservationSetting|null
     */
    public static function getSettingForDate($date, $pointOfSaleId)
    {
        $dateObj = Carbon::parse($date);
        $dayOfWeek = $dateObj->dayOfWeek;

        // First try to find a specific setting for this date and POS
        $setting = self::where('date', $date)
            ->where('point_of_sale_id', $pointOfSaleId)
            ->first();

        if (!$setting) {
            // If no specific setting, use the default for this day of week and POS
            $setting = self::where('day_of_week', $dayOfWeek)
                ->where('point_of_sale_id', $pointOfSaleId)
                ->orderBy('date', 'desc')
                ->first();
        }

        return $setting;
    }

    /**
     * Find the maximum date settings are available for
     *
     * @param int $pointOfSaleId
     * @return string
     */
    public static function getMaxDate($pointOfSaleId = null)
    {
        $query = self::query();

        if ($pointOfSaleId) {
            $query->where('point_of_sale_id', $pointOfSaleId);
        }

        return $query->max('date') ?? now()->addDays(30);
    }
}
