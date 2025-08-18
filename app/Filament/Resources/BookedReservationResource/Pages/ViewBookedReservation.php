<?php

namespace App\Filament\Resources\BookedReservationResource\Pages;

use App\Filament\Resources\BookedReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewBookedReservation extends ViewRecord
{
    protected static string $resource = BookedReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('print')
                ->label('Print Reservation')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('reservations.invoice', ['id' => $this->record->id]))
                ->extraAttributes([
                    'onclick' => 'let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); let isAndroid = /Android/i.test(navigator.userAgent); openPrintPreview(this.href, isMobile, !isAndroid); return false;'
                ]),


        ];
    }

    public function getFooter(): View
    {
        return view('components.print-preview');
    }
}
