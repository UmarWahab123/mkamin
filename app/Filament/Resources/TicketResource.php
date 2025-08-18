<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\TicketStatus;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isPOS = $user->pointOfSale !== null;

        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('Code'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('invoice_item_id')
                    ->label(__('Invoice Item'))
                    ->relationship('invoiceItem', 'id')
                    ->required(),
                Forms\Components\Select::make('ticket_status_id')
                    ->label(__('Ticket Status'))
                    ->relationship('ticketStatus', 'name_en')
                    ->required(),
                Forms\Components\DateTimePicker::make('status_updated_at')
                    ->label(__('Status Updated At'))
                    ->nullable(),
                Forms\Components\Select::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en', function (Builder $query) use ($user) {
                        if ($user->company_id) {
                            $query->where('company_id', $user->company_id);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->searchable()
                    ->preload()
                    ->disabled($isPOS)
                    ->dehydrated()
                    ->default($isPOS ? $user->pointOfSale->id : null)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('qr_code')
                    ->label(__('QR Code'))
                    ->state(function ($record) {
                        $svg = generateQrCode($record->code);
                        return 'data:image/svg+xml;base64,' . base64_encode($svg);
                    })
                    ->square(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label(__('Appointment Date'))
                    ->state(function ($record) {
                        $data = json_decode($record->ticket_detail, true);
                        return $data['appointment_date'] ?? null;
                    })
                    ->formatStateUsing(fn ($state) => $state ? date('Y-m-d', strtotime($state)) : 'N/A')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Start Time'))
                    ->state(function ($record) {
                        $data = json_decode($record->ticket_detail, true);
                        return $data['start_time'] ?? null;
                    })
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return date('h:i A', strtotime($state));
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->state(function ($record) {
                        $data = json_decode($record->ticket_detail, true);
                        return $data['end_time'] ?? null;
                    })
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return date('h:i A', strtotime($state));
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('invoiceItem.invoice.customer_detail')
                    ->label(__('Customer'))
                    ->formatStateUsing(function ($state) {
                        $data = json_decode($state, true);
                        return $data[sprintf('name_%s', app()->getLocale())] ?? '';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('invoiceItem.invoice', function ($query) use ($search) {
                            $query->where('customer_detail->name_en', 'like', "%{$search}%")
                                ->orWhere('customer_detail->name_ar', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('invoiceItem.staff_detail')
                    ->label(__('Staff'))
                    ->formatStateUsing(function ($state) {
                        $data = json_decode($state, true);
                        return $data[sprintf('name_%s', app()->getLocale())] ?? '';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('invoiceItem', function ($query) use ($search) {
                            $query->where('staff_detail->name_en', 'like', "%{$search}%")
                                ->orWhere('staff_detail->name_ar', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('invoiceItem.name')
                    ->label(__('Invoice Item'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticketStatus.name')
                    ->label(__('Ticket Status'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('ticketStatus', function ($query) use ($search) {
                            $query->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('pointOfSale.name_en')
                    ->label(__('Point of Sale'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_updated_at')
                    ->label(__('Status Updated At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('updateStatus')
                    ->label(__('Update Status'))
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('ticket_status_id')
                            ->label(__('Status'))
                            ->options(TicketStatus::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (Ticket $record, array $data): void {
                        $record->update([
                            'ticket_status_id' => $data['ticket_status_id'],
                            'status_updated_at' => now(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Ticket');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tickets');
    }

    public static function getNavigationLabel(): string
    {
        return __('Tickets');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-ticket';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Tickets');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
