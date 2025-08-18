<?php

namespace Filawidget\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $table = 'fields';

    protected $fillable = ['name', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    public function types()
    {
        return $this->belongsToMany(WidgetType::class, 'widget_type_fields', 'field_id', 'widget_type_id');
    }

    public function widgets()
    {
        return $this->belongsToMany(Widget::class, 'widget_fields', 'widget_field_id', 'widget_id');
    }
}
