<?php

namespace Filawidget\Resources\WidgetTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filawidget\Resources\WidgetTypeResource;

class ListWidgets extends ListRecords
{
    protected static string $resource = WidgetTypeResource::class;

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
}
