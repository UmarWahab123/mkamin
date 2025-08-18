<?php

namespace App\Filament\Resources\UserMessageResource\Pages;

use App\Filament\Resources\UserMessageResource;
use App\Filament\Traits\HasCloseAndRedirect;
use Filament\Resources\Pages\CreateRecord;

class CreateUserMessage extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = UserMessageResource::class;
}
