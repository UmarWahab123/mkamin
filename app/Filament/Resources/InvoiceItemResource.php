<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceItemResource\Pages;
use App\Models\InvoiceItem;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceItemResource extends Resource
{
    protected static ?string $model = InvoiceItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function getModelLabel(): string
    {
        return __('Invoice Item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Invoice Items');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('invoice_id')
                    ->label(__('Invoice'))
                    ->relationship('invoice', 'invoice_number')
                    ->required(),

                Forms\Components\TextInput::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->required(),

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
                    ->numeric(),

                Forms\Components\TextInput::make('vat_amount')
                    ->label(__('VAT Amount'))
                    ->numeric()
                    ->prefix(Setting::get('currency_symbol', '$')),

                Forms\Components\TextInput::make('other_taxes_amount')
                    ->label(__('Other Taxes'))
                    ->numeric()
                    ->prefix(Setting::get('currency_symbol', '$')),

                Forms\Components\TextInput::make('total')
                    ->label(__('Total'))
                    ->required()
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

                Forms\Components\Textarea::make('staff_detail')
                    ->label(__('Staff Details'))
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money(fn ($record) => Setting::get('currency', 'USD'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money(fn ($record) => Setting::get('currency', 'USD'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->label(__('Appointment Date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceItems::route('/'),
            'create' => Pages\CreateInvoiceItem::route('/create'),
            'edit' => Pages\EditInvoiceItem::route('/{record}/edit'),
        ];
    }
}
