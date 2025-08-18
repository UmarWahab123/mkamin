<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = SettingResource::class;

    protected function afterCreate(): void
    {
        // Update time intervals if needed
        SettingResource::updateTimeIntervals($this->record);
    }
}
