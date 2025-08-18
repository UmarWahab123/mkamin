<?php

namespace App\Filament\Resources\StaffResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $title = 'Bookings';

    public static function getModelLabel(): string
    {
        return __('Booking');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bookings');
    }


    // public function canView(\Illuminate\Database\Eloquent\Model $record): bool
    // {
    //     return auth()->user()->staff !== null;
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Client Details'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')
                            ->label(__('Client Name'))
                                    ->disabled()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->bookedReservation) {
                                            // First try to get from customer_detail JSON
                                            $customerDetail = json_decode($record->bookedReservation->customer_detail, true);
                                            if ($customerDetail && isset($customerDetail['name_'.app()->getLocale()])) {
                                                $component->state($customerDetail['name_'.app()->getLocale()]);
                                                return;
                                            }

                                            // If not found in JSON, try to get from Customer relationship
                                            if ($record->bookedReservation->customer) {
                                                $component->state($record->bookedReservation->customer->name);
                                                return;
                                            }
                                        }
                                        $component->state(__('Guest Customer'));
                                    }),
                                Forms\Components\TextInput::make('customer_phone')
                                    ->label(__('Client Phone'))
                                    ->disabled()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->bookedReservation) {
                                            // First try to get from customer_detail JSON
                                            $customerDetail = json_decode($record->bookedReservation->customer_detail, true);
                                            if ($customerDetail && isset($customerDetail['phone'])) {
                                                $component->state($customerDetail['phone']);
                                                return;
                                            }

                                            // If not found in JSON, try to get from Customer relationship
                                            if ($record->bookedReservation->customer) {
                                                $component->state($record->bookedReservation->customer->phone_number);
                                                return;
                                            }
                                        }
                                        $component->state(__('N/A'));
                                    }),
                            ]),
                    ]),

                Forms\Components\Section::make(__('Service Details'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Service Name'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('service_location')
                                    ->label(__('Service Location'))
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label(__('Price'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('duration')
                                    ->label(__('Duration'))
                            ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('total')
                                    ->label(__('Total'))
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('vat_amount')
                                    ->label(__('VAT Amount'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('other_taxes_amount')
                                    ->label(__('Other Taxes'))
                            ->disabled(),
                            ]),
                        // Home service location details
                        Forms\Components\Section::make(__('Home Service Details'))
                            ->schema([
                                Forms\Components\TextInput::make('bookedReservation.address')
                                    ->label(__('Address'))
                                    ->disabled()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->bookedReservation) {
                                            $component->state($record->bookedReservation->address);
                                        } else if ($record) {
                                                $record->load('bookedReservation');
                                                if ($record->bookedReservation) {
                                                    $component->state($record->bookedReservation->address);
                                            }
                                        }
                                    }),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('bookedReservation.longitude')
                                            ->label(__('Longitude'))
                                            ->disabled()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->bookedReservation) {
                                                    $component->state($record->bookedReservation->longitude);
                                                } else if ($record) {
                                                    $record->load('bookedReservation');
                                                    if ($record->bookedReservation) {
                                                        $component->state($record->bookedReservation->longitude);
                                                    }
                                                }
                                            }),
                                        Forms\Components\TextInput::make('bookedReservation.latitude')
                                            ->label(__('Latitude'))
                                            ->disabled()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->bookedReservation) {
                                                    $component->state($record->bookedReservation->latitude);
                                                } else if ($record) {
                                                    $record->load('bookedReservation');
                                                    if ($record->bookedReservation) {
                                                        $component->state($record->bookedReservation->latitude);
                                                    }
                                                }
                                            }),
                                    ]),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('getDirections')
                                        ->label(__('Get Directions'))
                                        ->icon('heroicon-o-map')
                                        ->color('primary')
                                        ->url(function ($record) {
                                            if ($record && $record->bookedReservation) {
                                                $latitude = $record->bookedReservation->latitude;
                                                $longitude = $record->bookedReservation->longitude;
                                                if ($latitude && $longitude) {
                                                    return "https://www.google.com/maps/dir/?api=1&destination={$latitude},{$longitude}";
                                                }
                                            }
                                            return null;
                                        })
                                        ->openUrlInNewTab()
                                        ->visible(function ($record) {
                                            return $record &&
                                                   $record->bookedReservation &&
                                                   $record->bookedReservation->latitude &&
                                                   $record->bookedReservation->longitude;
                                        }),
                                ]),
                            ])
                            ->visible(fn ($record) => $record && $record->service_location === 'home'),
                    ]),

                Forms\Components\Section::make(__('Appointment Details'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                        Forms\Components\DatePicker::make('appointment_date')
                            ->label(__('Appointment Date'))
                            ->disabled(),
                                Forms\Components\TimePicker::make('start_time')
                                    ->label(__('Start Time'))
                                    ->seconds(false)
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('end_time')
                                    ->label(__('End Time'))
                                    ->seconds(false)
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('bookedReservation'))
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('Client Name'))
                    ->getStateUsing(function ($record) {
                        return $record->customer?->name ?? __('Guest Customer');
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Service/Product'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Time'))
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label(__('Duration (min)'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_location')
                    ->label(__('Location'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money('SAR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('Created From')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('Created Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('View'))
                    ->mutateRecordDataUsing(function (array $data) {
                        if (!isset($data['customer']['name'])) {
                            $data['customer']['name'] = __('Guest Customer');
                        }
                        return $data;
                    })
                    ->before(function ($record) {
                        $record->load('bookedReservation');
                    }),
            ]);
    }
}
