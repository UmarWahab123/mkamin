<?php

namespace App\Filament\Resources\DiscountCardTemplateResource\Pages;

use App\Filament\Resources\DiscountCardTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateDiscountCardTemplate extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = DiscountCardTemplateResource::class;
}
