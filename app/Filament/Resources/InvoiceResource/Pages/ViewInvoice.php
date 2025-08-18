<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('print')
                ->label(__('Print'))
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn() => route('invoices.print', ['invoice' => $this->record]))
                ->extraAttributes([
                    'onclick' => 'let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); let isAndroid = /Android/i.test(navigator.userAgent); openPrintPreview(this.href, isMobile, !isAndroid); return false;'
                ])
        ];
    }

    public function getFooter(): View
    {
        return view('components.print-preview');
    }
}
