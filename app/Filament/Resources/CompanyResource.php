<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LeafletMap;
use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';


    public static function getModelLabel(): string
    {
        return __('Company');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Companies');
    }

    public static function getNavigationLabel(): string
    {
        return __('Company');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_number')
                    ->label(__('Tax Number'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->label(__('Website'))
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(255),
                PhoneInput::make('phone_number')
                    ->label(__('Phone Number'))
                    ->defaultCountry('SA')
                                    ->initialCountry('SA')
                    ->displayNumberFormat(PhoneInputNumberType::E164)
                    ->inputNumberFormat(PhoneInputNumberType::E164)
                    ->formatOnDisplay(true)
                    ->validateFor('AUTO')
                    ->separateDialCode(true)
                    ->strictMode(true)
                    ->formatAsYouType(true)
                    ->countrySearch(true)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
                Forms\Components\FileUpload::make('logo')
                    ->label(__('Logo for Light Background'))
                    ->openable()
                    ->image()
                    ->imagePreviewHeight('250')
                    ->directory('companies/logos'),
                Forms\Components\FileUpload::make('logo_dark')
                    ->label(__('Logo for Dark Background'))
                    ->openable()
                    ->imagePreviewHeight('250')
                    ->image()
                    ->directory('companies/logos'),
                Forms\Components\Textarea::make('address')
                    ->label(__('Address'))
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('latitude')
                    ->label(__('Latitude')),
                Forms\Components\Hidden::make('longitude')
                    ->label(__('Longitude')),
                LeafletMap::make('location')
                    ->label(__('Location Map'))
                    ->required()
                    ->defaultLocation([24.7136, 46.6753])
                    ->defaultZoom(8)
                    ->reactive()
                    ->afterStateHydrated(function ($state, callable $set, $record) {
                        if ($record && $record->latitude && $record->longitude) {
                            $set('location', [
                                'lat' => (float) $record->latitude,
                                'lng' => (float) $record->longitude
                            ]);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (isset($state['lat'], $state['lng'])) {
                            $set('latitude', $state['lat']);
                            $set('longitude', $state['lng']);
                        }
                        if (isset($state['address'])) {
                            $set('address', $state['address']);
                        }
                    })
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_number')
                    ->label(__('Tax Number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('Phone Number'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('Delete')),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
