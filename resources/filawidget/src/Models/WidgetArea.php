<?php

namespace Filawidget\Models;

use Filawidget\Observers\WidgetAreaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([WidgetAreaObserver::class])]
class WidgetArea extends Model
{
    protected $table = 'widget_areas';

    protected $fillable = ['name', 'identifier', 'description', 'status', 'order'];

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

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }

    public function scopeWithOrderedWidgets($query)
    {
        return $query->with(['widgets' => function ($query) {
            $query->orderBy('order');
        }]);
    }
}
