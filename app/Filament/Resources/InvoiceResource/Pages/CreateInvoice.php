<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateInvoice extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = InvoiceResource::class;
}
