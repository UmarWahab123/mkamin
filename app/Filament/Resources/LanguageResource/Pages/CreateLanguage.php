<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\CreateRecord;

class CreateLanguage extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = LanguageResource::class;
}
