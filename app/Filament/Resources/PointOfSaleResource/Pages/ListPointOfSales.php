<?php

namespace App\Filament\Resources\PointOfSaleResource\Pages;

use App\Filament\Resources\PointOfSaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;

class ListPointOfSales extends ListRecords
{
    protected static string $resource = PointOfSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (Collection $records) {
                        // Delete associated users when point of sales are bulk deleted
                        foreach ($records as $record) {
                            if ($record->user) {
                                $record->user->delete();
                            }
                        }
                    }),
            ]),
        ];
    }
}
