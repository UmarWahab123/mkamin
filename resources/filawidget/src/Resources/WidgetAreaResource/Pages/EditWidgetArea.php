<?php

namespace Filawidget\Resources\WidgetAreaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filawidget\Resources\WidgetAreaResource;

class EditWidgetArea extends EditRecord
{
    protected static string $resource = WidgetAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
