<?php

namespace App\Filament\Resources\StaffPositionResource\Pages;

use App\Filament\Resources\StaffPositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaffPositions extends ListRecords
{
    protected static string $resource = StaffPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
