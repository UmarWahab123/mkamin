<?php

namespace App\Filament\Resources\StaffPositionResource\Pages;

use App\Filament\Resources\StaffPositionResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\CreateRecord;

class CreateStaffPosition extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = StaffPositionResource::class;
}
