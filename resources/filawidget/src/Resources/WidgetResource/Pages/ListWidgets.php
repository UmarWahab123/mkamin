<?php

namespace Filawidget\Resources\WidgetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filawidget\Resources\WidgetResource;
use Filawidget\Resources\WidgetResource\Widgets\WidgetStatsOverview;

class ListWidgets extends ListRecords
{
    protected static string $resource = WidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('appearance')
                ->url(route('filament.admin.pages.appearance'))
                ->icon('heroicon-o-paint-brush')
                ->color('success')
                ->label(__('filawidget::filawidget.Appearance')),
            Actions\CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WidgetStatsOverview::class,
        ];
    }
}
