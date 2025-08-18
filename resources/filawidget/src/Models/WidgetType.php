<?php

namespace Filawidget\Models;

use Filawidget\Observers\WidgetTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([WidgetTypeObserver::class])]
class WidgetType extends Model
{
    protected $table = 'widget_types';

    protected $fillable = ['name', 'config', 'slug', 'fieldsIds'];

    protected $casts = [
        'config' => 'array',
        'fieldsIds' => 'array',
    ];

    public function widgets()
    {
        return $this->hasMany(Widget::class, 'widget_type_id');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'widget_type_fields', 'widget_type_id', 'field_id');
    }
}
