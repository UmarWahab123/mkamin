<?php

namespace App\Filament\Resources\ServiceCategoryResource\Pages;

use App\Filament\Resources\ServiceCategoryResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceCategory extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = ServiceCategoryResource::class;
}
