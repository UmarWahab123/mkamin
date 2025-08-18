<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tax_number',
        'website',
        'email',
        'phone_number',
        'logo',
        'logo_dark',
        'is_active',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // public function users(): HasMany
    // {
    //     return $this->hasMany(User::class);
    // }

    public function pointOfSales(): HasMany
    {
        return $this->hasMany(PointOfSale::class);
    }
}
