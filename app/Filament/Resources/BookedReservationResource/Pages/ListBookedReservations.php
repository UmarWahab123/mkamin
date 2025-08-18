<?php

namespace App\Filament\Resources\BookedReservationResource\Pages;

use App\Filament\Resources\BookedReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListBookedReservations extends ListRecords
{
    protected static string $resource = BookedReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('quickCreate')
                ->label(__('Quick Create'))
                ->url(static::getResource()::getUrl('quick-create'))
                ->icon('heroicon-m-plus-circle'),
            // Actions\Action::make('create')
            //     ->label(__('Create'))
            //     ->url(static::getResource()::getUrl('create'))
            //     ->icon('heroicon-m-plus-circle'),
        ];
    }

    public function getFooter(): View
    {
        return view('components.print-preview');
    }

}
