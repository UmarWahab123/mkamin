<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TimeInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'day_of_week',
        'start_time',
        'end_time',
        'is_closed',
        'can_visit_home',
        'timeable_id',
        'timeable_type',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_closed' => 'boolean',
        'can_visit_home' => 'boolean',
    ];

    /**
     * Get the parent timeable model (staff or any other model).
     */
    public function timeable(): MorphTo
    {
        return $this->morphTo();
    }
}
