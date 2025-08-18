<?php

namespace App\Filament\Resources\HeaderSettingsResource\Pages;

use App\Filament\Resources\HeaderSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeaderSettings extends EditRecord
{
    protected static string $resource = HeaderSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
