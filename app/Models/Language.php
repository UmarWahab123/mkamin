<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_active',
        'show_in_navbar',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_navbar' => 'boolean',
    ];

    /**
     * Get the translations for the language.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }
}
