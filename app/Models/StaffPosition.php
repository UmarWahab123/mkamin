<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffPosition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    /**
     * Get the staff that belong to this position.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function getNameAttribute()
    {
        return $this->attributes['name_' . app()->getLocale()] ?? $this->attributes['name_en'];
    }
}
