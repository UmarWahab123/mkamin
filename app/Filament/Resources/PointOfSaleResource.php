<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LeafletMap;
use App\Filament\Resources\PointOfSaleResource\Pages;
use App\Filament\Resources\PointOfSaleResource\RelationManagers;
use App\Models\PointOfSale;
use App\Models\User;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class PointOfSaleResource extends Resource
{
    protected static ?string $model = PointOfSale::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function getModelLabel(): string
    {
        return __('Point of Sale');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Point of Sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('Point of Sales');
    }

    public static function getNavigationGroup(): string
    {
        return __('Point of Sale');
    }

    protected static ?int $navigationSort = 10;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label(__('Company'))
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->label(__('City'))
                            ->required()
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
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('website')
                            ->label(__('Website'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('postal_code')
                            ->label(__('Postal Code'))
                            ->maxLength(50),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Status'))
                            ->default(true),
                    ]),

                Forms\Components\Section::make(__('Location'))
                    ->schema([
                        LeafletMap::make('location')
                            ->label(__('Location Map'))
                            ->required()
                            ->defaultLocation([24.7136, 46.6753])
                            ->defaultZoom(8)
                            ->columnSpanFull()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (isset($state['lat'], $state['lng'])) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                }
                                if (isset($state['address'])) {
                                    $set('address', $state['address']);
                                }
                            }),


                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // If address is updated manually, we could potentially trigger a geocoding operation
                                // This would require a custom Livewire method in the edit/create pages
                            })
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label(__('Latitude'))
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('longitude')) {
                                            $location = $get('location') ?: [];
                                            $location['lat'] = (float) $state;
                                            $location['lng'] = (float) $get('longitude');
                                            $set('location', $location);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('longitude')) {
                                            $location = $get('location') ?: [];
                                            $location['lat'] = (float) $state;
                                            $location['lng'] = (float) $get('longitude');
                                            $set('location', $location);
                                        }
                                    }),
                                Forms\Components\TextInput::make('longitude')
                                    ->label(__('Longitude'))
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('latitude')) {
                                            $location = $get('location') ?: [];
                                            $location['lat'] = (float) $get('latitude');
                                            $location['lng'] = (float) $state;
                                            $set('location', $location);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('latitude')) {
                                            $location = $get('location') ?: [];
                                            $location['lat'] = (float) $get('latitude');
                                            $location['lng'] = (float) $state;
                                            $set('location', $location);
                                        }
                                    }),
                            ]),
                    ]),

                Forms\Components\Section::make(__('User Account'))
                    ->schema([
                        Forms\Components\TextInput::make('user.email')
                            ->label(__('Login Email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ]),

                // Hidden fields for latitude and longitude
                Forms\Components\Hidden::make('latitude'),
                Forms\Components\Hidden::make('longitude'),
            ]);
    }

    // Process form data before saving
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return static::processLocationData($data);
    }

    public static function mutateFormDataBeforeUpdate(array $data): array
    {
        return static::processLocationData($data);
    }

    protected static function processLocationData(array $data): array
    {
        // Process location data before saving to database
        if (isset($data['location']) && is_array($data['location'])) {
            $data['latitude'] = $data['location']['lat'] ?? null;
            $data['longitude'] = $data['location']['lng'] ?? null;

            if (isset($data['location']['address']) && !isset($data['address'])) {
                $data['address'] = $data['location']['address'];
            }

            // Remove location from data to avoid saving it directly to the database
            unset($data['location']);
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Name (English)'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('Name (Arabic)'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('Login Email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('Phone Number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Status')),
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
                    ->label(__('Status')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete'))
                    ->before(function (Model $record) {
                        // Delete the associated user when the point of sale is deleted
                        if ($record->user) {
                            $record->user->delete();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('Delete'))
                        ->before(function (Collection $records) {
                            // Delete associated users when point of sales are bulk deleted
                            foreach ($records as $record) {
                                if ($record->user) {
                                    $record->user->delete();
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StaffRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPointOfSales::route('/'),
            'create' => Pages\CreatePointOfSale::route('/create'),
            'edit' => Pages\EditPointOfSale::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    protected static function beforeDelete(Model $record): void
    {
        // Delete the associated user when the point of sale is deleted
        if ($record->user) {
            $record->user->delete();
        }
    }
}
