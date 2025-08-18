<?php

namespace Filawidget\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $fillable = ['name', 'slug', 'description', 'order', 'status', 'widget_area_id', 'widget_type_id'];

    protected $casts = [
        // 'fieldsIds' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public static function updateOrder(array $order)
    {
        foreach ($order as $index => $id) {
            self::where('id', $id)->update(['order' => $index + 1]);
        }
    }

    public function area()
    {
        return $this->belongsTo(WidgetArea::class, 'widget_area_id');
    }

    public function type()
    {
        return $this->belongsTo(WidgetType::class, 'widget_type_id');
    }

    public function values()
    {
        return $this->hasMany(WidgetField::class);
    }

    public function fields()
    {
        //return $this->belongsToMany(Field::class, 'widget_fields', 'widget_id', 'widget_field_id');
        return $this->hasMany(Field::class, 'widget_fields', 'widget_id', 'widget_field_id');
    }
}
