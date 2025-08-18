<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateCompany extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = CompanyResource::class;
}
