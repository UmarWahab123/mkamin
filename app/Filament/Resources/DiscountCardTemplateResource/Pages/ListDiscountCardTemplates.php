<?php

namespace App\Filament\Resources\DiscountCardTemplateResource\Pages;

use App\Filament\Resources\DiscountCardTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountCardTemplates extends ListRecords
{
    protected static string $resource = DiscountCardTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
