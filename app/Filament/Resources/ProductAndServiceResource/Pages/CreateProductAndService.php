<?php

namespace App\Filament\Resources\ProductAndServiceResource\Pages;

use App\Filament\Resources\ProductAndServiceResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\CreateRecord;

class CreateProductAndService extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = ProductAndServiceResource::class;
}
