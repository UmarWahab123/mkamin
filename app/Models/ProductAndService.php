<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\BookedReservationItem;

class ProductAndService extends Model
{
    use HasFactory;

    protected $table = 'product_and_services';

    protected $fillable = [
        'category_id',
        'point_of_sale_id',
        'added_by',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
        'price',
        'price_home',
        'is_product',
        'can_be_done_at_home',
        'can_be_done_at_salon',
        'duration_minutes',
        'is_active',
        'sort_order',
        'sale_price_at_saloon',
        'sale_price_at_home',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_product' => 'boolean',
        'can_be_done_at_home' => 'boolean',
        'can_be_done_at_salon' => 'boolean',
        'price' => 'decimal:2',
        'price_home' => 'decimal:2',
        'sale_price_at_saloon' => 'decimal:2',
        'sale_price_at_home' => 'decimal:2',
    ];

    protected $appends = ['name', 'description', 'listed_by', 'is_available_at_salon', 'is_available_at_home']; // Ensures these attributes are available in JSON responses

    // Accessor for 'name'
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    // Accessor for 'description'
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["description_{$locale}"] ?? $this->attributes['description_en']; // Fallback to English
    }

    // Accessor for 'added_by'
    public function getListedByAttribute()
    {
        // dd($this->category);
        if (!$this->addedBy()) {
            return __('System');
        }

        $addedByUser = $this->addedBy()->first();
        if (!$addedByUser) {
            return __('System');
        }

        $roleName = $addedByUser->roles->first()?->name;
        if (!$roleName) {
            return $addedByUser->name;
        }

        $roleName = str_replace('_', ' ', $roleName);
        $roleName = ucwords($roleName);

        return $addedByUser->name . ' (' . $roleName . ')';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'tax_product_and_service')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    /**
     * Scope to get only active products/services with available staff who have future time intervals
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $locationType Either 'home', 'salon', or null for any location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAvailableStaff(Builder $query, ?string $locationType = null, $date = null)
    {
        $now = Carbon::now();

        $query->where('is_active', true)
            ->whereHas('staff', function (Builder $staffQuery) use ($now, $locationType, $date) {
                $staffQuery->where('is_active', true)->whereHas('timeIntervals', function (Builder $timeQuery) use ($now, $locationType, $date) {
                    $timeQuery->where('date', '>=', $date ?? $now->toDateString());

                    // Ensure there's at least 1 hour left before staff's end time
                    if ($date == $now->toDateString()) {
                        $timeQuery->whereTime('end_time', '>', $now->copy()->addHour()->format('H:i:s'));
                    }

                    // If location type is specified, apply additional filters
                    if ($locationType === 'home') {
                        $timeQuery->where('can_visit_home', true);
                    }
                });
            });

        // If home services are requested, further filter products/services
        if ($locationType === 'home') {
            $query->where('can_be_done_at_home', true);
        } else {
            $query->where('can_be_done_at_salon', true);
        }

        return $query;
    }

    /**
     * Calculate VAT amount for the product
     *
     * @param string $locationType Either 'salon' or 'home'
     * @return float
     */
    public function getVatAmount($locationType = 'salon')
    {
        $vatTax = $this->taxes()->where('name_en', 'VAT')->first();

        if (!$vatTax) {
            return 0;
        }

        // Use appropriate price based on location type
        $price = ($locationType === 'home' && $this->price_home) ?
            floatval($this->price_home) : floatval($this->price);

        if ($vatTax->type === 'percentage') {
            return $price * (floatval($vatTax->amount) / 100);
        }

        return floatval($vatTax->amount);
    }

    /**
     * Calculate sum of all taxes except VAT
     *
     * @param string $locationType Either 'salon' or 'home'
     * @return float
     */
    public function getOtherTaxesAmount($locationType = 'salon')
    {
        $otherTaxes = $this->taxes()->where('name_en', '!=', 'VAT')->get();
        $taxesTotal = 0;

        // Use appropriate price based on location type
        $price = ($locationType === 'home' && $this->price_home) ?
            floatval($this->price_home) : floatval($this->price);

        foreach ($otherTaxes as $tax) {
            if ($tax->type === 'percentage') {
                $taxesTotal += $price * (floatval($tax->amount) / 100);
            } else {
                $taxesTotal += floatval($tax->amount);
            }
        }

        return $taxesTotal;
    }

    /**
     * Get VAT percentage
     * If VAT is fixed amount, calculates equivalent percentage based on price
     *
     * @param string $locationType Either 'salon' or 'home'
     * @return float Percentage value (e.g., 15 for 15%)
     */
    public function getVatPercentage($locationType = 'salon')
    {
        $vatTax = $this->taxes()->where('name_en', 'VAT')->first();

        if (!$vatTax) {
            return 0;
        }


        if ($vatTax->type === 'percentage') {
            return floatval($vatTax->amount);
        } else {
            // Use appropriate price based on location type
            $price = ($locationType === 'home' && $this->price_home) ?
                floatval($this->price_home) : floatval($this->price);
            // For fixed amount, calculate the equivalent percentage
            if ($price > 0) {
                return (floatval($vatTax->amount) / $price) * 100;
            }
            return 0;
        }
    }

    /**
     * Get other taxes percentage (combined)
     * For fixed amounts, calculates equivalent percentage based on price
     *
     * @param string $locationType Either 'salon' or 'home'
     * @return float Combined percentage value
     */
    public function getOtherTaxesPercentage($locationType = 'salon')
    {
        $otherTaxes = $this->taxes()->where('name_en', '!=', 'VAT')->get();

        if ($otherTaxes->isEmpty()) {
            return 0;
        }

        // Use appropriate price based on location type
        $price = ($locationType === 'home' && $this->price_home) ?
            floatval($this->price_home) : floatval($this->price);

        if ($price <= 0) {
            return 0;
        }

        $percentageTotal = 0;

        foreach ($otherTaxes as $tax) {
            if ($tax->type === 'percentage') {
                $percentageTotal += floatval($tax->amount);
            } else {
                // For fixed amount, calculate the equivalent percentage
                $percentageTotal += (floatval($tax->amount) / $price) * 100;
            }
        }

        return $percentageTotal;
    }

    /**
     * Get the staff members who can provide this product or service
     */
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_product_service');
    }

    /**
     * Get all booked reservation items for this product/service
     */
    public function bookedReservationItems()
    {
        return $this->hasMany(BookedReservationItem::class);
    }

    /**
     * Get the user who added this product/service.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }


    /**
     * Check if the product/service is available for booking
     *
     * @param string|null $locationType Either 'home', 'salon', or null for any location
     * @return bool
     */
    public function isAvailableForBooking(?string $locationType = null): bool
    {
        $now = Carbon::now();

        // Check if product/service is active
        if (!$this->is_active) {
            return false;
        }

        // If home services are requested, ensure product/service can be done at home
        if ($locationType === 'home' && !$this->can_be_done_at_home) {
            return false;
        }
        if ($locationType === 'salon' && !$this->can_be_done_at_salon) {
            return false;
        }
        // Check if there are available staff members
        foreach ($this->staff as $staffMember) {
            if (!$staffMember->is_active) {
                continue;
            }

            foreach ($staffMember->timeIntervals as $timeInterval) {
                // Check if the date is in the future
                if ($timeInterval->date < $now->toDateString()) {
                    continue;
                }
                // Check if there's at least 1 hour left before end time
                if ($timeInterval->date == $now->toDateString() && Carbon::parse($timeInterval->end_time)->format('H:i:s') <= $now->copy()->addHour()->format('H:i:s')) {
                    continue;
                }
                // If location type is home, check if staff can visit home
                if ($locationType === 'home' && !$timeInterval->can_visit_home) {
                    continue;
                }

                // Get staff's booked reservation items for this date
                $bookedReservationItems = $staffMember->bookings()
                    ->whereHas('bookedReservation', function ($query) use ($timeInterval) {
                        $query->where('appointment_date', $timeInterval->date)
                              ->whereNotIn('status', ['pending', 'cancelled']);
                    })
                    ->get();

                // Convert time interval start and end to Carbon objects for easier comparison
                $intervalStart = Carbon::parse($timeInterval->start_time);
                $intervalEnd = Carbon::parse($timeInterval->end_time);

                // Get the service duration in minutes
                $serviceDuration = $this->duration_minutes;

                // Find available time slots
                $availableSlots = $this->findAvailableSlots(
                    $intervalStart,
                    $intervalEnd,
                    $bookedReservationItems,
                    $serviceDuration
                );

                // If we found at least one available slot, return true
                if (!empty($availableSlots)) {
                    return true;
                }
            }
        }

        // No available staff or time slots found
        return false;
    }

    /**
     * Find available time slots within a time interval, considering booked reservations
     *
     * @param Carbon $intervalStart Start time of the interval
     * @param Carbon $intervalEnd End time of the interval
     * @param Collection $bookedReservationItems Collection of booked reservation items
     * @param int $serviceDuration Required duration in minutes
     * @return array Array of available time slots [start, end]
     */
    private function findAvailableSlots(Carbon $intervalStart, Carbon $intervalEnd, $bookedReservationItems, int $serviceDuration): array
    {
        // If no service duration is specified, assume it's not available
        if ($serviceDuration <= 0) {
            return [];
        }

        // If no bookings, the entire interval is available
        if ($bookedReservationItems->isEmpty()) {
            // Check if the interval is long enough for the service
            if ($intervalEnd->diffInMinutes($intervalStart) >= $serviceDuration) {
                return [[$intervalStart, $intervalEnd]];
            }
            return [];
        }

        // Create a list of all occupied time slots
        $occupiedSlots = [];
        foreach ($bookedReservationItems as $item) {
            $startTime = Carbon::parse($item->start_time);
            $endTime = Carbon::parse($item->end_time);
            $occupiedSlots[] = [$startTime, $endTime];
        }

        // Sort occupied slots by start time
        usort($occupiedSlots, function ($a, $b) {
            return $a[0]->lt($b[0]) ? -1 : 1;
        });

        // Merge overlapping occupied slots
        $mergedOccupiedSlots = [];
        foreach ($occupiedSlots as $slot) {
            if (empty($mergedOccupiedSlots)) {
                $mergedOccupiedSlots[] = $slot;
                continue;
            }

            $lastSlot = &$mergedOccupiedSlots[count($mergedOccupiedSlots) - 1];

            // If current slot overlaps with the last merged slot, extend the last slot
            if ($slot[0]->lte($lastSlot[1])) {
                if ($slot[1]->gt($lastSlot[1])) {
                    $lastSlot[1] = $slot[1];
                }
            } else {
                // No overlap, add as a new occupied slot
                $mergedOccupiedSlots[] = $slot;
            }
        }

        // Find available slots between occupied slots
        $availableSlots = [];
        $currentStart = clone $intervalStart;

        foreach ($mergedOccupiedSlots as $occupied) {
            // If there's enough time before this booking, add it as an available slot
            if ($occupied[0]->diffInMinutes($currentStart) >= $serviceDuration) {
                $availableSlots[] = [$currentStart, clone $occupied[0]];
            }

            // Move current start to after this booking
            $currentStart = clone $occupied[1];
        }

        // Check if there's available time after the last booking
        if ($intervalEnd->diffInMinutes($currentStart) >= $serviceDuration) {
            $availableSlots[] = [$currentStart, clone $intervalEnd];
        }

        return $availableSlots;
    }

    /**
     * Get all products and services that are available for booking
     *
     * @param string|null $locationType Either 'home', 'salon', or null for any location
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableForBooking(?string $locationType = null)
    {
        $query = self::where('is_active', true);

        // If home services are requested, filter by products/services that can be done at home
        if ($locationType === 'home') {
            $query->where('can_be_done_at_home', true);
        } else {
            $query->where('can_be_done_at_salon', true);
        }

        // Get all potentially available products/services
        $potentialProducts = $query->get();

        // Filter to only those that are actually available (have available staff and time slots)
        return $potentialProducts->filter(function($product) use ($locationType) {
            return $product->isAvailableForBooking($locationType);
        });
    }


    /**
     * Accessor for 'is_available_at_salon'
     *
     * @return bool
     */
    public function getIsAvailableAtSalonAttribute()
    {
        return $this->isAvailableForBooking('salon');
    }

    /**
     * Accessor for 'is_available_at_home'
     *
     * @return bool
     */
    public function getIsAvailableAtHomeAttribute()
    {
        return $this->isAvailableForBooking('home');
    }

    /**
     * Scope to get only products/services that are available for booking
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $locationType Either 'home', 'salon', or null for any location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAvailableForBooking(Builder $query, ?string $locationType = null)
    {
        // Get IDs of products that are available for booking
        $availableProductIds = self::getAvailableForBooking($locationType)->pluck('id');

        // Filter query to only include these products
        return $query->whereIn('id', $availableProductIds);
    }
}
