<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'type',
        'amount',
        'company_id',
        'is_active',
    ];

    protected $appends = ['name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productAndServices()
    {
        return $this->belongsToMany(ProductAndService::class, 'tax_product_and_service')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    // Accessor for 'name'
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }
}
