<?php

namespace App\Filament\Resources\BookedReservationResource\Pages;

use App\Filament\Resources\BookedReservationResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;

class QuickCreateBookedReservation extends Page
{
    protected static string $resource = BookedReservationResource::class;

    protected static string $view = 'filament.resources.booked-reservation-resource.pages.quick-create-booked-reservation';

    public function getTitle(): string
    {
        return __('Quick Create Booked Reservation');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('go_back')
                ->label(__('Go back'))
                ->url($this->getResource()::getUrl('index'))
                ->color('secondary'),
        ];
    }
}
