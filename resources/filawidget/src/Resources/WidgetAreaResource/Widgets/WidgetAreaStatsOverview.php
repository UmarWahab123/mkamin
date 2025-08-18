<?php

namespace Filawidget\Resources\WidgetAreaResource\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filawidget\Models\Widget;
use Filawidget\Models\WidgetArea;

class WidgetAreaStatsOverview extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        $totalWidgetAreas = WidgetArea::count();

        $activeWidgetAreas = WidgetArea::active()->count();

        $inactiveWidgetAreas = WidgetArea::where('status', false)->count();

        $totalWidgets = Widget::count();

        return [
            Stat::make(__('filawidget::filawidget.Total Widget Areas'), $totalWidgetAreas)
                ->description(__('filawidget::filawidget.Wide Total number of widget areas created Area'))
                ->color('primary'),
            Stat::make(__('filawidget::filawidget.Active Widget Areas'), $activeWidgetAreas)
                ->description(__('filawidget::filawidget.Number of active widget areas'))
                ->color('success'),
            Stat::make(__('filawidget::filawidget.Inactive Widget Areas'), $inactiveWidgetAreas)
                ->description(__('filawidget::filawidget.Number of inactive widget areas'))
                ->color('danger'),
            Stat::make(__('filawidget::filawidget.Total Widgets'), $totalWidgets)
                ->description(__('filawidget::filawidget.Number of widgets in all areas'))
                ->color('warning'),
        ];
    }
}
