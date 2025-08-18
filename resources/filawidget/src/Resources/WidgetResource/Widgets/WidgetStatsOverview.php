<?php

namespace Filawidget\Resources\WidgetResource\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filawidget\Models\Widget;

class WidgetStatsOverview extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        $totalWidgets = Widget::count();

        $activeWidgets = Widget::active()->count();

        $inactiveWidgets = Widget::active()->count();

        $widgetTypeStats = Widget::select('widget_type_id')
            ->selectRaw('count(*) as count')
            ->groupBy('widget_type_id')
            ->get();

        return [
            Stat::make(__('filawidget::filawidget.Total Widgets'), $totalWidgets)
                ->description(__('filawidget::filawidget.Total number of widgets created'))
                ->color('primary'),
            Stat::make(__('filawidget::filawidget.Active Widgets'), $activeWidgets)
                ->description(__('filawidget::filawidget.Number of active widgets'))
                ->color('success'),
            Stat::make(__('filawidget::filawidget.Inactive Widgets'), $inactiveWidgets)
                ->description(__('filawidget::filawidget.Number of inactive widgets'))
                ->color('danger'),
            Stat::make(__('filawidget::filawidget.Widgets Per Type'), $widgetTypeStats->pluck('count')->sum())
                ->description(__('filawidget::filawidget.Number of widgets by type'))
                ->color('warning'),
        ];
    }
}
