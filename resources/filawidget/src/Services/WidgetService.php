<?php

namespace Filawidget\Services;

use Filawidget\Models\Widget;
use Illuminate\Database\Eloquent\Collection;

class WidgetService
{
    public static function getAllWidgets(): ?Collection
    {
        return Widget::active()->get();
    }

    public static function getWidgetBySlug(string $slug): ?Widget
    {
        return Widget::active()->with(['type', 'area'])->where('slug', $slug)->first();
    }
}
