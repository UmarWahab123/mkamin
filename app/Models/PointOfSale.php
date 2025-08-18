<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PointOfSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'name_en',
        'name_ar',
        'city',
        'address',
        'phone_number',
        'email',
        'website',
        'postal_code',
        'latitude',
        'longitude',
        'is_active',
        'is_main_branch',
    ];

    protected $appends = ['name'];

    /**
     * Get the user that owns the point of sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that owns the point of sale.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the staff members associated with the point of sale.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Get the customers associated with the point of sale.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the discounts associated with the point of sale.
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_point_of_sale')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    // Accessor for 'name'
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    public static function getMainBranch()
    {
        return self::where('is_main_branch', 1)->first();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketStatuses()
    {
        return $this->hasMany(TicketStatus::class);
    }
}
