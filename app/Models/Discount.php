<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'amount',
        'minimum_order_amount',
        'given_to',
        'maximum_uses',
        'times_used',
        'start_date',
        'end_date',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'company_id',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'maximum_uses' => 'integer',
        'times_used' => 'integer',
    ];

    protected $appends = ['name', 'description'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function pointOfSales(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class, 'discount_point_of_sale')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_discount')
            ->withTimestamps()
            ->withPivot(['deleted_at', 'discount_card_template_id']);
    }

    // Accessor for 'name' based on current locale
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    // Accessor for 'description' based on current locale
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["description_{$locale}"] ?? $this->attributes['description_en']; // Fallback to English
    }

    // Check if the discount is active (within date range and has uses left)
    public function isValid(): bool
    {
        $now = now()->startOfDay();
        $startDate = $this->start_date->startOfDay();
        $endDate = $this->end_date->endOfDay();

        $withinDateRange = $now->between($startDate, $endDate);
        $hasUsesLeft = $this->maximum_uses == 0 || $this->times_used < $this->maximum_uses;

        return $withinDateRange && $hasUsesLeft && $this->is_active;
    }

    // TODO:: add a method to check if the discount is valid for a specific customer
    public function isValidForCustomer(Customer $customer): bool
    {
        return $this->isValid() && $this->customers->contains($customer);
    }

    // TODO:: add a method to check if the discount is valid for a specific point of sale
    public function isValidForPointOfSale(PointOfSale $pointOfSale): bool
    {
        return $this->isValid() && $this->pointOfSales->contains($pointOfSale);
    }

    // TODO:: add a method to return formatted start and end dates
    public function getFormattedDatesAttribute(): array
    {
        return [
            'start_date' => $this->start_date->format('d/m/Y'),
            'end_date' => $this->end_date->format('d/m/Y'),
        ];
    }

    // Increment used count
    public function incrementUsed(): self
    {
        $this->times_used++;
        $this->save();

        return $this;
    }
}
