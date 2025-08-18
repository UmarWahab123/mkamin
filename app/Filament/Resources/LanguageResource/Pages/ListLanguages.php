<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('quickCreate')
                ->label(__('View All Translations'))
                ->url(static::getResource()::getUrl('view-translations'))
                ->icon('heroicon-m-plus-circle'),
        ];
    }
}
