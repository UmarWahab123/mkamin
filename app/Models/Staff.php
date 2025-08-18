<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'phone_number',
        'address',
        'longitude',
        'latitude',
        'resume',
        'images',
        'is_active',
        'point_of_sale_id',
        'user_id',
        'position_en',
        'position_ar',
        'default_start_time',
        'default_end_time',
        'default_closed_day',
        'default_home_visit_days',
        'can_edit_profile',
    ];

    protected $appends = ['name', 'position'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'default_home_visit_days' => 'array',
        'can_edit_profile' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the localized name based on current locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    /**
     * Get the localized position based on current locale.
     */
    public function getPositionAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["position_{$locale}"] ?? $this->attributes['position_en']; // Fallback to English
    }

    /**
     * Get the staff position that this staff belongs to.
     */
    public function staffPosition(): BelongsTo
    {
        return $this->belongsTo(StaffPosition::class);
    }

    /**
     * Get the user that owns the staff.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    /**
     * Get the reservation settings this staff is assigned to
     */
    public function reservationSettings(): BelongsToMany
    {
        return $this->belongsToMany(ReservationSetting::class, 'reservation_setting_staff');
    }

    /**
     * Get the time intervals for the staff.
     */
    public function timeIntervals(): MorphMany
    {
        return $this->morphMany(TimeInterval::class, 'timeable');
    }

    /**
     * Get the products and services that this staff can provide
     */
    public function productAndServices(): BelongsToMany
    {
        return $this->belongsToMany(ProductAndService::class, 'staff_product_service');
    }

    /**
     * Get the bookings associated with this staff member
     */
    public function bookings()
    {
        return $this->hasMany(BookedReservationItem::class);
    }
}
