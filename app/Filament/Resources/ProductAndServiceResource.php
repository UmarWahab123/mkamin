<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAndServiceResource\Pages;
use App\Models\ProductAndService;
use App\Models\PointOfSale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;

class ProductAndServiceResource extends Resource
{
    protected static ?string $model = ProductAndService::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    public static function getModelLabel(): string
    {
        return __('Product and Service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Products and Services');
    }

    public static function getNavigationLabel(): string
    {
        return __('Products and Services');
    }

    public static function getNavigationGroup(): string
    {
        return __('Products and Services');
    }

    public static function form(Form $form): Form
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isPosUser = $user && $user->hasRole('point_of_sale');
        $isStaff = $user && $user->hasRole('staff');
        $userPosId = null;

        // Get user's point of sale ID if applicable
        if ($isPosUser) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            if ($pointOfSale) {
                $userPosId = $pointOfSale->id;
            }
        }
        if ($isStaff) {
            $userPosId = $user->staff->point_of_sale_id;
        }

        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name_' . app()->getLocale())
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('Image'))
                            ->image()
                            ->directory('product_and_services')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(fn() => !$isStaff)
                            ->disabled(fn() => $isStaff)
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->required()
                            ->default(0),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label(__('Duration (minutes)'))
                            ->numeric()
                            ->required(fn(Forms\Get $get) => $get('is_product') === false)
                            ->helperText(__('Leave this field empty if this is a product and not a service')),

                        // Hidden field for point_of_sale_id that's always included for POS users
                        Forms\Components\Hidden::make('point_of_sale_id')
                            ->default($userPosId)
                            ->disabled(false)
                            ->visible($isPosUser || $isStaff)
                            ->live()
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('taxes', [])),

                        // Hidden field for added_by
                        Forms\Components\Hidden::make('added_by')
                            ->default(fn() => Auth::id())
                            ->dehydrated(true),
                    ]),

                // Point of Sale assignment section (only visible for admins)
                Forms\Components\Section::make(__('Assignments'))
                    ->schema([
                        Forms\Components\Select::make('point_of_sale_id')
                            ->label(__('Point of Sale'))
                            ->relationship('pointOfSale', 'name_en')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('taxes', [])),
                    ])
                    ->hidden(function () use ($isPosUser, $isStaff) {
                        return $isPosUser || $isStaff;
                    }),

                Forms\Components\Section::make(__('English'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description_en')
                            ->label(__('Description (English)'))
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('Arabic'))
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description_ar')
                            ->label(__('Description (Arabic)'))
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('Product and Service Type'))
                    ->schema([
                        Forms\Components\Toggle::make('is_product')
                            ->label(__('Is this a product?'))
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('can_be_done_at_salon')
                            ->label(__('Can be done at salon?'))
                            ->default(true)
                            ->live(),
                        Forms\Components\Toggle::make('can_be_done_at_home')
                            ->label(__('Can be done at home?'))
                            ->default(false)
                            // ->visible(fn(Forms\Get $get) => !$get('is_product'))
                            ->live(),
                    ]),
                Forms\Components\Section::make(__('Pricing'))
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label(new \Illuminate\Support\HtmlString(__('Price') . ' <span class="icon-saudi_riyal"></span>'))
                            ->numeric()
                            ->prefix('SAR')
                            ->required(fn (Forms\Get $get) => $get('can_be_done_at_salon'))
                            ->visible(fn (Forms\Get $get) => $get('can_be_done_at_salon'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                if (empty($state)) return;
                                self::calculateSalePrice($set, $get);
                            }),
                        Forms\Components\TextInput::make('price_home')
                            ->label(new \Illuminate\Support\HtmlString(__('Home Price') . ' <span class="icon-saudi_riyal"></span>'))
                            ->numeric()
                            ->prefix('SAR')
                            ->visible(fn (Forms\Get $get) => $get('can_be_done_at_home'))
                            ->required(fn (Forms\Get $get) => $get('can_be_done_at_home'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                if (empty($state)) return;
                                self::calculateSalePrice($set, $get);
                            }),
                    ]),

                Forms\Components\Section::make(__('Taxes'))
                    ->schema([
                        Forms\Components\CheckboxList::make('taxes')
                            ->label(__('Taxes'))
                            ->relationship(
                                'taxes',
                                fn() => app()->getLocale() === 'en' ? 'name_en' : 'name_ar'
                            )
                            ->options(function (Forms\Get $get) {
                                $posId = $get('point_of_sale_id');
                                if (!$posId) {
                                    return [];
                                }

                                // Get the company ID from the point of sale
                                $pointOfSale = \App\Models\PointOfSale::find($posId);
                                if (!$pointOfSale || !$pointOfSale->company_id) {
                                    return [];
                                }

                                return \App\Models\Tax::query()
                                    ->where('company_id', $pointOfSale->company_id)
                                    ->where('is_active', true)
                                    ->pluck(app()->getLocale() === 'en' ? 'name_en' : 'name_ar', 'id')
                                    ->toArray();
                            })
                            ->live()
                            ->afterStateUpdated(fn(Forms\Set $set, Forms\Get $get) => self::calculateSalePrice($set, $get))
                            ->helperText(__('Select applicable taxes for this product'))
                            ->columns(),

                        Forms\Components\TextInput::make('sale_price_at_saloon')
                            ->label(new \Illuminate\Support\HtmlString(__('Sale price at Salon (with taxes)') . ' <span class="icon-saudi_riyal"></span>'))
                            ->numeric()
                            ->step(0.1)
                            ->live(onBlur: true)
                            ->dehydrated(true)
                            ->visible(fn (Forms\Get $get) => $get('can_be_done_at_salon'))
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                if (empty($state)) return;
                                self::calculateBasePriceFromSalePrice($set, $get, $state, 'price');
                            }),

                        Forms\Components\TextInput::make('sale_price_at_home')
                            ->label(fn (Forms\Get $get) => $get('is_product')
                                ? new \Illuminate\Support\HtmlString(__('Delivery Sale Price (with taxes)') . ' <span class="icon-saudi_riyal"></span>')
                                : new \Illuminate\Support\HtmlString(__('Sale price at Home (with taxes)') . ' <span class="icon-saudi_riyal"></span>'))
                            ->numeric()
                            ->step(0.1)
                            ->live(onBlur: true)
                            ->dehydrated(true)
                            ->visible(fn (Forms\Get $get) => $get('can_be_done_at_home'))
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                if (empty($state)) return;
                                self::calculateBasePriceFromSalePrice($set, $get, $state, 'price_home');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('category', function ($query) use ($search) {
                            $query->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('Image'))
                    ->circular(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Name (English)'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('Name (Arabic)'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('pointOfSale.name_en')
                    ->label(__('Point of Sale'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_product')
                    ->label(__('Product'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('can_be_done_at_home')
                    ->label(__('Home Service'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_home')
                    ->label(__('Home Price'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('listed_by')
                    ->label(__('Added By'))
                    // ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
                Tables\Filters\TernaryFilter::make('is_product')
                    ->label(__('Product')),
                Tables\Filters\TernaryFilter::make('can_be_done_at_home')
                    ->label(__('Home Service')),
                Tables\Filters\SelectFilter::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('View'))
                    ->before(function (ProductAndService $record) {
                        // Check if user can view this record
                        if (Gate::denies('view', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
                Tables\Actions\EditAction::make()
                    ->label(__('Edit'))
                    ->before(function (ProductAndService $record) {
                        // Check if user can edit this record
                        if (Gate::denies('update', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete'))
                    ->before(function (ProductAndService $record) {
                        // Check if user can delete this record
                        if (Gate::denies('delete', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
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
            'index' => Pages\ListProductAndServices::route('/'),
            'create' => Pages\CreateProductAndService::route('/create'),
            'view' => Pages\ViewProductAndService::route('/{record}'),
            'edit' => Pages\EditProductAndService::route('/{record}/edit'),
        ];
    }

    /**
     * Handle unauthorized access attempts
     */
    public static function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this product or service.'))
            ->danger()
            ->persistent()
            ->send();
    }

    /**
     * Calculate the sale price with taxes applied
     */
    public static function calculateSalePrice(Forms\Set $set, Forms\Get $get): void
    {
        $basePrice = floatval($get('price') ?? 0);
        $homePrice = floatval($get('price_home') ?? 0);
        $selectedTaxIds = $get('taxes');

        if (empty($selectedTaxIds)) {
            $set('sale_price_at_saloon', $basePrice);
            $set('sale_price_at_home', $homePrice);
            return;
        }

        $taxes = \App\Models\Tax::whereIn('id', $selectedTaxIds)->get();

        // Calculate salon price with taxes
        if (!empty($basePrice)) {
            $totalTaxAmount = 0;
            foreach ($taxes as $tax) {
                if ($tax->type === 'percentage') {
                    $totalTaxAmount += $basePrice * (floatval($tax->amount) / 100);
                } else {
                    $totalTaxAmount += floatval($tax->amount);
                }
            }
            $salePrice = $basePrice + $totalTaxAmount;
            $set('sale_price_at_saloon', round($salePrice, 2));
        }

        // Calculate home price with taxes
        if (!empty($homePrice)) {
            $totalTaxAmount = 0;
            foreach ($taxes as $tax) {
                if ($tax->type === 'percentage') {
                    $totalTaxAmount += $homePrice * (floatval($tax->amount) / 100);
                } else {
                    $totalTaxAmount += floatval($tax->amount);
                }
            }
            $salePrice = $homePrice + $totalTaxAmount;
            $set('sale_price_at_home', round($salePrice, 2));
        }
    }

    /**
     * Calculate base price from sale price with taxes
     *
     * @param Forms\Set $set The set state function
     * @param Forms\Get $get The get state function
     * @param mixed $salePrice The sale price including taxes
     * @param string $targetField The field to update with calculated base price
     * @return void
     */
    public static function calculateBasePriceFromSalePrice(Forms\Set $set, Forms\Get $get, $salePrice, string $targetField): void
    {
        $salePrice = floatval($salePrice);
        $selectedTaxIds = $get('taxes');

        if (empty($selectedTaxIds)) {
            $set($targetField, round($salePrice, 2));
            return;
        }

        $taxes = \App\Models\Tax::whereIn('id', $selectedTaxIds)->get();
        $taxMultiplier = 0;
        $fixedTaxes = 0;

        foreach ($taxes as $tax) {
            if ($tax->type === 'percentage') {
                $taxMultiplier += (floatval($tax->amount) / 100);
            } else {
                $fixedTaxes += floatval($tax->amount);
            }
        }

        // Calculate base price from sale price
        // Formula: basePrice = (salePrice - fixedTaxes) / (1 + taxMultiplier)
        $basePrice = ($salePrice - $fixedTaxes) / (1 + $taxMultiplier);
        $set($targetField, round($basePrice, 2));
    }
}
