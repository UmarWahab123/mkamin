<?php

namespace App\Filament\Resources\ProductAndServiceResource\Pages;

use App\Filament\Resources\ProductAndServiceResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ViewProductAndService extends ViewRecord
{
    protected static string $resource = ProductAndServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
