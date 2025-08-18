<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use App\Models\Company;
use App\Models\Customer;
use App\Models\PointOfSale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function getModelLabel(): string
    {
        return __('Discount');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Discounts');
    }

    public static function getNavigationLabel(): string
    {
        return __('Discounts');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Discount Information'))
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->options([
                                'fixed' => __('Fixed Amount'),
                                'percentage' => __('Percentage'),
                            ])
                            ->reactive()
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label(fn (callable $get) => $get('type') === 'percentage'
                                ? __('Amount ')
                                : new \Illuminate\Support\HtmlString(__('Amount') . ' <span class="icon-saudi_riyal"></span>'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix(fn (callable $get) => $get('type') === 'percentage' ? '' : 'SAR')
                            ->suffix(fn (callable $get) => $get('type') === 'percentage' ? '%' : ''),

                        Forms\Components\TextInput::make('minimum_order_amount')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('SAR'),

                        Forms\Components\DatePicker::make('start_date')
                            ->default(now())
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->after('start_date'),

                        Forms\Components\TextInput::make('maximum_uses')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->helperText(__('Set to 0 for unlimited uses')),

                        Forms\Components\TextInput::make('times_used')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(true)
                            ->visible(fn ($record) => $record !== null),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make(__('Discount Details'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description_en')
                            ->label(__('Description (English)'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description_ar')
                            ->label(__('Description (Arabic)'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('Discount Availability'))
                    ->schema([
                        Forms\Components\Select::make('given_to')
                            ->options([
                                'any_one' => __('Anyone'),
                                'fixed_customers' => __('Fixed Customers'),
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('company_id')
                            ->label(__('Company'))
                            ->relationship('company', 'name')
                            ->required()
                            ->default(function() {
                                $user = Auth::user();
                                $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

                                if ($pointOfSale && $pointOfSale->company_id) {
                                    return $pointOfSale->company_id;
                                }
                                return null;
                            })
                            ->disabled(function() {
                                return PointOfSale::where('user_id', Auth::id())->exists();
                            })
                            ->dehydrated(),

                        Forms\Components\Select::make('pointOfSales')
                            ->label(__('Point of Sales'))
                            ->multiple()
                            ->relationship('pointOfSales', 'name_en', function (Builder $query) {
                                $user = Auth::user();
                                $isPOS = PointOfSale::where('user_id', $user->id)->exists();
                                $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

                                if ($isPOS && $pointOfSale) {
                                    // If user is a POS, show only their POS
                                    $query->where('point_of_sales.id', $pointOfSale->id);
                                } elseif ($pointOfSale && $pointOfSale->company_id) {
                                    // If user belongs to a company through POS, show only POS from that company
                                    $query->where('point_of_sales.company_id', $pointOfSale->company_id);
                                }
                                // If no conditions are met, don't filter - show all options
                                // This ensures options are always displayed
                            })
                            ->default(function() {
                                $user = Auth::user();
                                $isPOS = PointOfSale::where('user_id', $user->id)->exists();
                                $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

                                if ($isPOS && $pointOfSale) {
                                    // If user is a POS, default to their own POS ID
                                    return [$pointOfSale->id];
                                }

                                return [];
                            })
                            ->disabled(function() {
                                // Disable the field if the user is a POS
                                return PointOfSale::where('user_id', Auth::id())->exists();
                            })
                            ->dehydrated() // Always include the field value when form is submitted
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->preload() // Preload the options to ensure they're displayed
                            ->searchable(),

                        Forms\Components\Select::make('customers')
                            ->label(__('Customers'))
                            ->multiple()
                            ->relationship('customers', 'name_en', function (Builder $query) {
                                $user = Auth::user();
                                $isPOS = PointOfSale::where('user_id', $user->id)->exists();
                                $pointOfSale = PointOfSale::where('user_id', $user->id)->first();

                                if ($isPOS && $pointOfSale) {
                                    // If user is a POS, show only customers of their POS
                                    $query->where('customers.point_of_sale_id', $pointOfSale->id);
                                } elseif ($pointOfSale && $pointOfSale->company_id) {
                                    // If user belongs to a company through POS, show only customers from that company's POS
                                    $query->whereHas('pointOfSale', function ($query) use ($pointOfSale) {
                                        $query->where('point_of_sales.company_id', $pointOfSale->company_id);
                                    });
                                }
                                // If no conditions are met, don't filter - show all options
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->preload() // Preload the options
                            ->searchable()
                            ->visible(fn (callable $get) => $get('given_to') === 'fixed_customers')
                            ->required(fn (callable $get) => $get('given_to') === 'fixed_customers'),

                        Forms\Components\Select::make('discount_card_template_id')
                            ->label(__('Select Discount Card Template'))
                            ->allowHtml()
                            ->searchable()
                            ->preload()
                            ->options(function() {
                                return \App\Models\DiscountCardTemplate::all()->mapWithKeys(function ($template) {
                                    return [$template->id => view('filament.components.template-option', [
                                        'name' => $template->name,
                                        'image' => Storage::url($template->image)
                                    ])->render()];
                                })->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $template = \App\Models\DiscountCardTemplate::find($value);
                                if (!$template) return '';

                                return view('filament.components.template-option', [
                                    'name' => $template->name,
                                    'image' => Storage::url($template->image)
                                ])->render();
                            })
                            ->visible(fn (callable $get) => $get('given_to') === 'fixed_customers')
                            ->required(fn (callable $get) => $get('given_to') === 'fixed_customers'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Name (English)'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'fixed' => 'success',
                        'percentage' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'fixed' => __('Fixed'),
                        'percentage' => __('Percentage'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->type === 'percentage') {
                            return number_format($state, 2) . '%';
                        }

                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('given_to')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'any_one' => 'success',
                        'fixed_customers' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'any_one' => __('Anyone'),
                        'fixed_customers' => __('Fixed Customers'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('times_used')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maximum_uses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'fixed' => __('Fixed Amount'),
                        'percentage' => __('Percentage'),
                    ]),
                Tables\Filters\SelectFilter::make('given_to')
                    ->options([
                        'any_one' => __('Anyone'),
                        'fixed_customers' => __('Fixed Customers'),
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->toggle(),
                Tables\Filters\Filter::make('valid_now')
                    ->label(__('Currently Valid'))
                    ->query(fn (Builder $query): Builder =>
                        $query->where('start_date', '<=', now())
                              ->where('end_date', '>=', now())
                              ->where(function($query) {
                                  $query->where('maximum_uses', 0)
                                        ->orWhereRaw('times_used < maximum_uses');
                              })
                    )
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record) {
                        if (Gate::denies('update', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if (Gate::denies('delete', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
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
            RelationManagers\PointOfSalesRelationManager::class,
            RelationManagers\CustomersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }

    /**
     * Handle unauthorized access attempts
     */
    public static function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this discount.'))
            ->danger()
            ->persistent()
            ->send();
    }
}
