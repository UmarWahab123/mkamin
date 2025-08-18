<?php

namespace Filawidget\Observers;

use Filawidget\Models\Widget;
use Filawidget\Models\WidgetType;
use Illuminate\Support\Str;

class WidgetTypeObserver
{
    public function creating(WidgetType $widgetType)
    {
        $widgetType->slug = Str::slug($widgetType->name, '-');
    }

    public function updated(WidgetType $widgetType): void
    {
        Widget::where('widget_type_id', $widgetType->id)->update([
            'fieldsIds' => $widgetType->fieldsIds,
        ]);
    }
}
