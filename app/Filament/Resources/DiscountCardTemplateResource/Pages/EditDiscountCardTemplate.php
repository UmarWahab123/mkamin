<?php

namespace App\Filament\Resources\DiscountCardTemplateResource\Pages;

use App\Filament\Resources\DiscountCardTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class EditDiscountCardTemplate extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = DiscountCardTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
