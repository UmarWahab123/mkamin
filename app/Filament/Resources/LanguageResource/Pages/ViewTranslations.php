<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewTranslations extends Page
{
    protected static string $resource = LanguageResource::class;

    protected static string $view = 'filament.resources.language-resource.pages.view-translations';


    public function getTitle(): string|Htmlable
    {
        return __('View Translations');
    }


}
