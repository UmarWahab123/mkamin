<?php

namespace App\Filament\Resources\InvoiceItemResource\Pages;

use App\Filament\Resources\InvoiceItemResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateInvoiceItem extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = InvoiceItemResource::class;
}
