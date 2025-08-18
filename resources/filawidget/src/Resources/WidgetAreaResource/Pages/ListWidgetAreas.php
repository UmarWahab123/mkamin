<?php

namespace Filawidget\Resources\WidgetAreaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filawidget\Resources\WidgetAreaResource;
use Filawidget\Resources\WidgetAreaResource\Widgets\WidgetAreaStatsOverview;

class ListWidgetAreas extends ListRecords
{
    protected static string $resource = WidgetAreaResource::class;

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
            WidgetAreaStatsOverview::class,
        ];
    }
}
