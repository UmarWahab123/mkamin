<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\PointOfSale;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Filament\Forms\Components\LeafletMap;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('Customer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Customers');
    }

    public static function getNavigationLabel(): string
    {
        return __('Customers');
    }

    public static function getNavigationGroup(): string
    {
        return __('Point of Sale');
    }

    public static function form(Form $form): Form
    {
        // Check if current user is a POS
        $user = Auth::user();
        $isPOS = PointOfSale::where('user_id', $user->id)->exists();
        $posId = null;

        if ($isPOS) {
            $pos = PointOfSale::where('user_id', $user->id)->first();
            if ($pos) {
                $posId = $pos->id;
            }
        }

        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateCustomer)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
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
                            ->columnSpan(2)
                            ->countrySearch(true)
                            ->required(),


                        // Hidden field for POS users
                        Forms\Components\Hidden::make('point_of_sale_id')
                            ->default($posId)
                            ->visible($isPOS),

                        // Map component
                        LeafletMap::make('location')
                            ->label('Select Location')
                            ->columnSpanFull()
                            ->defaultZoom(13)
                            ->defaultLocation([24.7136, 46.6753]) // Default location for Riyadh
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                if ($get('latitude') && $get('longitude')) {
                                    $set('location', [
                                        'lat' => (float) $get('latitude'),
                                        'lng' => (float) $get('longitude')
                                    ]);
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if (isset($state['lat'], $state['lng'])) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                }
                                if (isset($state['address'])) {
                                    $set('address', $state['address']);
                                }
                            }),

                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                        // Readonly coordinate fields
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->readonly()
                            ->numeric()
                            ->step('0.0000001')
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
                            ->label('Longitude')
                            ->readonly()
                            ->numeric()
                            ->step('0.0000001')
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
                        // Point of Sale Selection (conditionally visible)
                        Forms\Components\Select::make('point_of_sale_id')
                            ->label('Point of Sale')
                            ->columnSpanFull()
                            ->relationship('pointOfSale', 'name_en', function (Builder $query) use ($user) {
                                if ($user->company_id) {
                                    $query->where('company_id', $user->company_id);
                                }
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable()
                            ->preload()
                            ->visible(!$isPOS),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Name (English)'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('Name (Arabic)'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                PhoneColumn::make('phone_number')
                    ->label(__('Phone Number'))
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->searchable(),
                Tables\Columns\TextColumn::make('pointOfSale.name_en')
                    ->label('Point of Sale')
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en')
                    ->searchable()
                    ->preload(),

                // Combined date filter that handles both preset options and custom range
                Tables\Filters\Filter::make('date_combined')
                    ->form([
                        Forms\Components\Select::make('preset')
                            ->label(__('Preset Date Filter'))
                            ->options([
                                'today' => __('Today'),
                                'yesterday' => __('Yesterday'),
                                'this_week' => __('This Week'),
                                'this_month' => __('This Month'),
                            ])
                            ->placeholder(__('Select a preset filter')),
                        Forms\Components\DatePicker::make('from')
                            ->label(__('From')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $hasPreset = !empty($data['preset']);
                        $hasCustomRange = !empty($data['from']) || !empty($data['until']);

                        // If neither filter is applied, return the original query
                        if (!$hasPreset && !$hasCustomRange) {
                            return $query;
                        }

                        // Start a new query to apply OR conditions
                        return $query->where(function (Builder $query) use ($data, $hasPreset, $hasCustomRange) {
                            // Apply preset filter if selected
                            if ($hasPreset) {
                                if ($data['preset'] === 'today') {
                                    $query->orWhere(function ($q) {
                                        $q->whereDate('created_at', now()->toDateString());
                                    });
                                }

                                if ($data['preset'] === 'yesterday') {
                                    $query->orWhere(function ($q) {
                                        $q->whereDate('created_at', now()->subDay()->toDateString());
                                    });
                                }

                                if ($data['preset'] === 'this_week') {
                                    $query->orWhere(function ($q) {
                                        $q->whereBetween('created_at', [
                                            now()->startOfWeek(),
                                            now()->endOfWeek(),
                                        ]);
                                    });
                                }

                                if ($data['preset'] === 'this_month') {
                                    $query->orWhere(function ($q) {
                                        $q->whereBetween('created_at', [
                                            now()->startOfMonth(),
                                            now()->endOfMonth(),
                                        ]);
                                    });
                                }
                            }

                            // Apply custom date range if provided
                            if ($hasCustomRange) {
                                $query->orWhere(function (Builder $q) use ($data) {
                                    if (!empty($data['from'])) {
                                        $q->whereDate('created_at', '>=', $data['from']);
                                    }

                                    if (!empty($data['until'])) {
                                        $q->whereDate('created_at', '<=', $data['until']);
                                    }
                                });
                            }
                        });
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\DiscountsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $isPOS = PointOfSale::where('user_id', $user->id)->exists();

        $query = parent::getEloquentQuery();

        // If user is a point of sale, only show customers for that POS
        if ($isPOS) {
            $posUser = PointOfSale::where('user_id', $user->id)->first();
            if ($posUser) {
                $query->where('point_of_sale_id', $posUser->id);
            }
        }
        // If user belongs to a company, only show customers for that company's POS
        elseif ($user->company_id) {
            $query->whereHas('pointOfSale', function($query) use ($user) {
                $query->where('company_id', $user->company_id);
            });
        }

        return $query;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_en', 'name_ar', 'email', 'phone_number'];
    }
}
