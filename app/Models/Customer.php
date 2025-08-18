<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'user_id',
        'name_ar',
        'email',
        'phone_number',
        'address',
        'longitude',
        'latitude',
        'point_of_sale_id',
    ];

    protected $appends = ['name'];

    // Mutators to ensure exact values are stored
    public function setLatitudeAttribute($value)
    {
        $this->attributes['latitude'] = $value !== null ? (string) $value : null;
    }

    public function setLongitudeAttribute($value)
    {
        $this->attributes['longitude'] = $value !== null ? (string) $value : null;
    }

    // Accessors to ensure exact values are returned
    public function getLatitudeAttribute($value)
    {
        return $value !== null ? (string) $value : null;
    }

    public function getLongitudeAttribute($value)
    {
        return $value !== null ? (string) $value : null;
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }


    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'customer_discount')
            ->withTimestamps()
            ->withPivot(['deleted_at', 'discount_card_template_id'])
            ->with('discountCardTemplate');
    }

    public function discountCardTemplate()
    {
        return $this->belongsToMany(DiscountCardTemplate::class, 'customer_discount', 'customer_id', 'discount_card_template_id');
    }

    // Accessor for 'name' based on current locale
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    /**
     * Get the user associated with the customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
