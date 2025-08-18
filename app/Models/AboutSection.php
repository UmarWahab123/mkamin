<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'content',
        'order',
        'visible'
    ];

    protected $casts = [
        'content' => 'array',
        'visible' => 'boolean',
        'order' => 'integer'
    ];

    // Boot method to automatically assign order
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order)) {
                $model->order = static::max('order') + 1;
            }
        });
    }

    // Scope for visible sections
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    // Scope for ordered sections
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Get sections for frontend display
    public static function getVisibleSections()
    {
        return static::visible()->ordered()->get();
    }

    // Reorder sections
    public function moveToPosition($newPosition)
    {
        $currentPosition = $this->order;
        
        if ($newPosition == $currentPosition) {
            return;
        }

        if ($newPosition < $currentPosition) {
            // Moving up - increment order of sections between new and current position
            static::whereBetween('order', [$newPosition, $currentPosition - 1])
                ->increment('order');
        } else {
            // Moving down - decrement order of sections between current and new position
            static::whereBetween('order', [$currentPosition + 1, $newPosition])
                ->decrement('order');
        }

        $this->update(['order' => $newPosition]);
    }

    // Get next available order number
    public static function getNextOrder()
    {
        return (static::max('order') ?? 0) + 1;
    }

    // Normalize orders (remove gaps)
    public static function normalizeOrders()
    {
        $sections = static::ordered()->get();
        
        $sections->each(function ($section, $index) {
            $section->update(['order' => $index + 1]);
        });
    }

    // Get section by name
    public static function getByName($sectionName)
    {
        return static::where('section_name', $sectionName)->first();
    }

    // Check if section has required content
    public function hasRequiredContent($requiredFields = [])
    {
        if (empty($requiredFields)) {
            return true;
        }

        foreach ($requiredFields as $field) {
            if (!isset($this->content[$field]) || empty($this->content[$field])) {
                return false;
            }
        }

        return true;
    }

    // Get content field with fallback
    public function getContent($field, $default = null)
    {
        return $this->content[$field] ?? $default;
    }

    // Set content field
    public function setContent($field, $value)
    {
        $content = $this->content ?? [];
        $content[$field] = $value;
        $this->update(['content' => $content]);
    }
    
}