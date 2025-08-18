<?php

namespace App\Filament\Resources\HeaderSettingsResource\Pages;

use App\Filament\Resources\HeaderSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeaderSettings extends ListRecords
{
    protected static string $resource = HeaderSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
