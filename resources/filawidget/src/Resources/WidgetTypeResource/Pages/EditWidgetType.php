<?php

namespace Filawidget\Resources\WidgetTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filawidget\Resources\WidgetTypeResource;

class EditWidgetType extends EditRecord
{
    protected static string $resource = WidgetTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
