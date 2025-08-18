<?php

namespace App\Filament\Resources\StaffPositionResource\Pages;

use App\Filament\Resources\StaffPositionResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\EditRecord;

class EditStaffPosition extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = StaffPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
