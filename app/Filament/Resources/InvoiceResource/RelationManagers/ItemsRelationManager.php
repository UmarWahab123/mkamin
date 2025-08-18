<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_and_service_id')
                    ->label(__('Product/Service'))
                    ->relationship('productAndService', 'name'),

                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->required()
                    ->numeric()
                    ->prefix(Setting::get('currency_symbol', '$')),

                Forms\Components\TextInput::make('duration')
                    ->label(__('Duration (minutes)'))
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->required()
                    ->numeric()
                    ->default(1),

                Forms\Components\TextInput::make('vat_amount')
                    ->label(__('VAT Amount'))
                    ->numeric()
                    ->prefix(Setting::get('currency_symbol', '$')),

                Forms\Components\TextInput::make('other_taxes_amount')
                    ->label(__('Other Taxes'))
                    ->numeric()
                    ->prefix(Setting::get('currency_symbol', '$')),

                Forms\Components\DatePicker::make('appointment_date')
                    ->label(__('Appointment Date'))
                    ->required(),

                Forms\Components\TimePicker::make('start_time')
                    ->label(__('Start Time'))
                    ->seconds(false),

                Forms\Components\TimePicker::make('end_time')
                    ->label(__('End Time'))
                    ->seconds(false),

                Forms\Components\Select::make('staff_id')
                    ->label(__('Staff'))
                    ->relationship('staff', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Service'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money(fn ($record) => Setting::get('currency', 'USD'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Quantity')),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money(fn ($record) => Setting::get('currency', 'USD')),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->label(__('Date'))
                    ->date(),

                Tables\Columns\TextColumn::make('staff.name')
                    ->label(__('Staff')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                        // Auto-assign invoice_number from parent
                        $data['invoice_number'] = $livewire->getOwnerRecord()->invoice_number;

                        // Calculate total
                        $data['total'] = $data['price'] * $data['quantity'];

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
