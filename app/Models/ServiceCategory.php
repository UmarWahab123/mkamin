<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'is_active',
        'sort_order',
        'point_of_sale_id',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name', 'description', 'listed_by']; // Ensures these attributes are available in JSON responses

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

    public function productAndServices(): HasMany
    {
        return $this->hasMany(ProductAndService::class, 'category_id');
    }

    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
