<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\BookedReservationResource\Pages;
use App\Models\BookedReservation;
use App\Models\ProductAndService;
use App\Models\Setting;
use App\Models\PointOfSale;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class BookedReservationResource extends Resource
{
    protected static ?string $model = BookedReservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getModelLabel(): string
    {
        return __('Booked Reservation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Booked Reservations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Booked Reservations');
    }

    public static function getNavigationGroup(): string
    {
        return __('Reservations');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isPosUser = $user && $user->hasRole('point_of_sale');
        $isStaffUser = $user && $user->hasRole('staff');
        $isSuperAdmin = $user && $user->hasRole('super_admin');
        $userPosId = null;

        // Get user's point of sale ID if applicable
        if ($isPosUser) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            if ($pointOfSale) {
                $userPosId = $pointOfSale->id;
            }
        }
        if ($isStaffUser) {
            $userPosId = $user->staff->point_of_sale_id;
        }

        return $form
            ->schema([
                Forms\Components\Section::make(__('Reservation Details'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('point_of_sale_id')
                                    ->label(__('Point of Sale'))
                                    ->relationship('pointOfSale', 'name_en')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $locale = app()->getLocale();
                                        return $record->{"name_{$locale}"} ?? $record->name_en;
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default($userPosId)
                                    ->disabled(!$isSuperAdmin)
                                    ->dehydrated()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set) {
                                        // Reset related fields when POS changes
                                        $set('customer_id', null);
                                    }),

                                Forms\Components\Select::make('customer_id')
                                    ->label(__('Customer'))
                                    ->relationship('customer', 'id', function ($query, callable $get) {
                                        $posId = $get('point_of_sale_id');
                                        if ($posId) {
                                            return $query->where('point_of_sale_id', $posId);
                                        }
                                        return $query;
                                    })
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $customerDetail = json_decode($record->customer_detail, true);
                                        if (!$customerDetail) {
                                            return $record->name ?? __('Unknown');
                                        }
                                        $locale = app()->getLocale();
                                        return $customerDetail['name_' . $locale] ?? $customerDetail['name_en'] ?? $record->name ?? __('Unknown');
                                    })
                                    ->disabled(function (callable $get) {
                                        $posId = $get('point_of_sale_id');
                                        if (!$posId) {
                                            $posId = $get('../../point_of_sale_id');
                                        }
                                        return !$posId;
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Name'))
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->label(__('Email'))
                                            ->email()
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->label(__('Phone'))
                                            ->tel()
                                            ->required()
                                    ]),
                            ]),

                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => __('Pending'),
                                'confirmed' => __('Confirmed'),
                                'completed' => __('Completed'),
                                'cancelled' => __('Cancelled'),
                                'expired' => __('Expired'),
                                'refunded' => __('Refunded'),
                            ])
                            ->default('pending')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // If status is changing from pending to confirmed/completed
                                $id = $get('id');
                                if ($id !== null) {
                                    $reservation = \App\Models\BookedReservation::find($id);
                                    if ($reservation && $reservation->status === 'pending' && in_array($state, ['confirmed', 'completed'])) {
                                        // Update payment method to cash
                                        $set('payment_method', 'cash');

                                        // If there's an invoice, update it too
                                        if ($reservation->invoice) {
                                            $reservation->invoice->update([
                                                'payment_method' => 'cash'
                                            ]);
                                        }
                                    }
                                }
                            }),
                    ])->columns(2),

                Forms\Components\Section::make(__('Services'))
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, callable $get): array {
                                // Pass the point_of_sale_id to new items
                                $data['point_of_sale_id'] = $get('point_of_sale_id');
                                return $data;
                            })
                            ->schema([
                                Forms\Components\Hidden::make('point_of_sale_id')
                                    ->default(fn(callable $get) => $get('../../point_of_sale_id')),

                                Forms\Components\Select::make('product_and_service_id')
                                    ->label(__('Service'))
                                    ->relationship('productAndService', 'name_' . app()->getLocale(), function ($query, callable $get) {
                                        // First try to get POS ID from the item itself
                                        $posId = $get('point_of_sale_id');

                                        // If that fails, try to get it from the parent form
                                        if (!$posId) {
                                            $posId = $get('../../point_of_sale_id');
                                        }

                                        // Apply the filter if we have a POS ID
                                        if ($posId) {
                                            return $query->where('point_of_sale_id', $posId);
                                        }

                                        // If no POS ID or user is admin, don't filter
                                        return $query;
                                    })
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->disabled(function (callable $get) {
                                        // First try to get POS ID from the item itself
                                        $posId = $get('point_of_sale_id');

                                        // If that fails, try to get it from the parent form
                                        if (!$posId) {
                                            $posId = $get('../../point_of_sale_id');
                                        }

                                        return !$posId;
                                    })
                                    ->placeholder(
                                        fn(callable $get) =>
                                        !$get('point_of_sale_id') && !$get('../../point_of_sale_id')
                                            ? __('Please select a Point of Sale first')
                                            : __('Select a service')
                                    )
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if (!$state) return;

                                        $productService = ProductAndService::find($state);
                                        if ($productService) {
                                            $set('price', $productService->price);
                                            $set('duration', $productService->duration_minutes);
                                            $set('name_en', $productService->name_en);
                                            $set('name_ar', $productService->name_ar);
                                            $set('image', $productService->image);

                                            // Calculate totals
                                            $quantity = $get('quantity') ?? 1;
                                            $discountAmount = $get('other_discount_amount') ?? 0;
                                            $total = ($productService->price * $quantity) - $discountAmount;
                                            $set('total', $total);

                                            // Set appointment date to match reservation date
                                            $set('appointment_date', $get('../../reservation_date'));
                                        }
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $price = $get('price') ?? 0;
                                        $discountAmount = $get('other_discount_amount') ?? 0;
                                        $total = ($price * $state) - $discountAmount;
                                        $set('total', max(0, $total));
                                    }),

                                Forms\Components\Select::make('service_location')
                                    ->label(__('Service Location'))
                                    ->options([
                                        'salon' => __('At Salon'),
                                        'home' => __('At Home'),
                                    ])
                                    ->required()
                                    ->default('salon'),

                                Forms\Components\DatePicker::make('appointment_date')
                                    ->label(__('Appointment Date'))
                                    ->default(fn(callable $get) => $get('../../reservation_date'))
                                    ->required()
                                    ->native(false)
                                    ->closeOnDateSelection(),

                                Forms\Components\Select::make('staff_id')
                                    ->label(__('Staff'))
                                    ->relationship('staff', 'name_en', function ($query, callable $get) {
                                        // First try to get POS ID from the item itself
                                        $posId = $get('point_of_sale_id');

                                        // If that fails, try to get it from the parent form
                                        if (!$posId) {
                                            $posId = $get('../../point_of_sale_id');
                                        }

                                        // Apply the filter if we have a POS ID
                                        if ($posId) {
                                            return $query->where('point_of_sale_id', $posId);
                                        }

                                        // If no POS ID or user is admin, don't filter
                                        return $query;
                                    })
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $locale = app()->getLocale();
                                        return $record->{"name_{$locale}"} ?? $record->name_en;
                                    })
                                    ->disabled(function (callable $get) {
                                        // First try to get POS ID from the item itself
                                        $posId = $get('point_of_sale_id');

                                        // If that fails, try to get it from the parent form
                                        if (!$posId) {
                                            $posId = $get('../../point_of_sale_id');
                                        }

                                        return !$posId;
                                    })
                                    ->placeholder(
                                        fn(callable $get) =>
                                        !$get('point_of_sale_id') && !$get('../../point_of_sale_id')
                                            ? __('Please select a Point of Sale first')
                                            : __('Select a staff member')
                                    )
                                    ->preload()
                                    ->searchable(),

                                Forms\Components\TimePicker::make('start_time')
                                    ->label(__('Start Time'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label(__('End Time'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\TextInput::make('duration')
                                    ->label(__('Duration (minutes)'))
                                    ->numeric()
                                    ->required()
                                    ->default(30),

                                Forms\Components\TextInput::make('price')
                                    ->label(__('Price'))
                                    ->numeric()
                                    ->required()
                                    ->reactive(),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('other_discount_value')
                                            ->label(__('Discount Value'))
                                            ->numeric()
                                            ->default(0)
                                            ->reactive()
                                            ->prefix(fn(Forms\Get $get) => $get('other_discount_type') === 'percentage' ? '%' : Setting::get('default_currency') ?? 'SAR')
                                            ->prefixAction(
                                                Forms\Components\Actions\Action::make('changeOtherDiscountType')
                                                    ->icon('heroicon-o-arrows-right-left')
                                                    ->tooltip(fn(Forms\Get $get) => $get('other_discount_type') === 'fixed'
                                                        ? __('Switch to percentage discount (%)')
                                                        : __('Switch to fixed amount discount ($)'))
                                                    ->action(function (Forms\Set $set, Forms\Get $get) {
                                                        // Toggle between fixed and percentage
                                                        $currentType = $get('other_discount_type');
                                                        $newType = $currentType === 'fixed' ? 'percentage' : 'fixed';
                                                        $set('other_discount_type', $newType);

                                                        // Recalculate discount amount
                                                        $discountValue = $get('other_discount_value') ?? 0;
                                                        $price = $get('price') ?? 0;
                                                        $quantity = $get('quantity') ?? 1;

                                                        if ($newType === 'percentage') {
                                                            $discountAmount = ($price * $quantity) * ($discountValue / 100);
                                                        } else {
                                                            $discountAmount = $discountValue;
                                                        }

                                                        $set('other_discount_amount', $discountAmount);
                                                        $total = ($price * $quantity) - $discountAmount;
                                                        $set('total', max(0, $total));
                                                    })
                                            )
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                $discountValue = $state ?? 0;
                                                $price = $get('price') ?? 0;
                                                $quantity = $get('quantity') ?? 1;
                                                $discountType = $get('other_discount_type');

                                                if ($discountType === 'percentage') {
                                                    $discountAmount = ($price * $quantity) * ($discountValue / 100);
                                                } else {
                                                    $discountAmount = $discountValue;
                                                }

                                                $set('other_discount_amount', $discountAmount);
                                                $total = ($price * $quantity) - $discountAmount;
                                                $set('total', max(0, $total));
                                            }),

                                        Forms\Components\Hidden::make('other_discount_type')
                                            ->default('fixed'),

                                        Forms\Components\TextInput::make('other_discount_amount')
                                            ->label(__('Discount Amount'))
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),

                                // Hidden fields that will be populated
                                Forms\Components\Hidden::make('name_en'),
                                Forms\Components\Hidden::make('name_ar'),
                                Forms\Components\Hidden::make('image'),

                                Forms\Components\TextInput::make('total')
                                    ->label(__('Total'))
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                            ])
                            ->columns(4)
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make(__('Discount'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('discount_code')
                                    ->label(__('Discount Code'))
                                    ->reactive()
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('apply_discount')
                                            ->label(__('Apply'))
                                            ->icon('heroicon-m-check')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $discountCode = $get('discount_code');
                                                if (!$discountCode) {
                                                    return;
                                                }

                                                // Find the discount by code
                                                $discount = \App\Models\Discount::where('code', $discountCode)
                                                    ->where('active', true)
                                                    ->where(function ($query) {
                                                        $query->whereNull('expires_at')
                                                            ->orWhere('expires_at', '>=', now());
                                                    })
                                                    ->first();

                                                if ($discount) {
                                                    $subtotal = $get('subtotal') ?: 0;
                                                    $discountAmount = 0;

                                                    // Calculate discount amount
                                                    if ($discount->type === 'percentage') {
                                                        $discountAmount = $subtotal * ($discount->amount / 100);
                                                    } else {
                                                        $discountAmount = $discount->amount;
                                                    }

                                                    // Apply discount
                                                    $set('discount_id', $discount->id);
                                                    $set('discount_amount', $discountAmount);

                                                    // Recalculate total
                                                    $vatAmount = $get('vat_amount') ?: 0;
                                                    $otherTaxes = $get('other_taxes_amount') ?: 0;
                                                    $newTotal = $subtotal + $vatAmount + $otherTaxes - $discountAmount;
                                                    $set('total_price', max(0, $newTotal));
                                                }
                                            })
                                    ),

                                Forms\Components\TextInput::make('other_total_discount_value')
                                    ->label(__('Other Discounts'))
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->live(onBlur: true)
                                    ->prefix(fn(Forms\Get $get) => $get('other_total_discount_type') === 'percentage' ? '%' : Setting::get('default_currency') ?? 'SAR')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('apply_other_discount')
                                            ->label(__('Apply'))
                                            ->icon('heroicon-m-check')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $discountValue = $get('other_total_discount_value') ?? 0;
                                                $discountType = $get('other_total_discount_type');
                                                $subtotal = $get('subtotal') ?: 0;

                                                if ($discountType === 'percentage') {
                                                    $discountAmount = $subtotal * ($discountValue / 100);
                                                } else {
                                                    $discountAmount = $discountValue;
                                                }

                                                $set('other_total_discount_amount', $discountAmount);

                                                // Recalculate total
                                                $vatAmount = $get('vat_amount') ?: 0;
                                                $otherTaxes = $get('other_taxes_amount') ?: 0;
                                                $newTotal = $subtotal + $vatAmount + $otherTaxes - $discountAmount;
                                                $set('total_price', max(0, $newTotal));
                                            })
                                    )
                                    ->suffixActions([
                                        Forms\Components\Actions\Action::make('changeOtherDiscountType')
                                            ->icon('heroicon-o-cog')
                                            ->tooltip(fn(Forms\Get $get) => $get('other_total_discount_type') === 'fixed'
                                                ? __('Switch to percentage discount (%)')
                                                : __('Switch to fixed amount discount ($)'))
                                            ->action(function (Forms\Set $set, Forms\Get $get) {
                                                // Toggle between fixed and percentage
                                                $currentType = $get('other_total_discount_type');
                                                $newType = $currentType === 'fixed' ? 'percentage' : 'fixed';
                                                $set('other_total_discount_type', $newType);
                                            })
                                    ]),

                                Forms\Components\Hidden::make('discount_id'),
                                Forms\Components\Hidden::make('other_total_discount_type')
                                    ->default('fixed'),
                                Forms\Components\Hidden::make('other_total_discount_amount')
                                    ->default(0),
                            ]),
                    ]),

                Forms\Components\Section::make(__('Reservation Summary'))
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label(__('Subtotal'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                // Calculate subtotal based on items
                                $set('subtotal', collect($get('items'))->sum('total') ?: 0);
                            }),

                        Forms\Components\TextInput::make('vat_amount')
                            ->label(__('VAT'))
                            ->helperText(fn() => __('VAT') . ': ' . Setting::get('vat_percentage', 15) . '%')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                $vatPercentage = Setting::get('vat_percentage', 15);
                                $subtotal = $get('subtotal') ?: 0;
                                $set('vat_amount', round($subtotal * ($vatPercentage / 100), 2));
                            }),

                        Forms\Components\TextInput::make('other_taxes_amount')
                            ->label(__('Other Taxes'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label(__('Discount'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),

                        Forms\Components\TextInput::make('total_price')
                            ->label(__('Total'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                $subtotal = $get('subtotal') ?: 0;
                                $vatAmount = $get('vat_amount') ?: 0;
                                $otherTaxes = $get('other_taxes_amount') ?: 0;
                                $discount = $get('discount_amount') ?: 0;

                                $set('total_price', $subtotal + $vatAmount + $otherTaxes - $discount);
                            }),
                    ])->columns(2),

                Forms\Components\Section::make(__('Payment Information'))
                    ->schema([
                        Forms\Components\TextInput::make('total_paid_cash')
                            ->label(__('Total Paid (Cash)'))
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Calculate total amount paid
                                $totalPaid = (float)$state + (float)($get('total_paid_online') ?? 0);
                                $set('total_amount_paid', $totalPaid);

                                // Calculate change
                                $change = max(0, $totalPaid - (float)($get('total_price') ?? 0));
                                $set('change_given', $change);

                                // If this is an edit form and invoice exists, update it
                                $id = $get('id');
                                if ($id !== null) {
                                    $reservation = \App\Models\BookedReservation::find($id);
                                    if ($reservation && $reservation->invoice) {
                                        $reservation->invoice->update([
                                            'total_paid_cash' => $state,
                                            'total_amount_paid' => $totalPaid
                                        ]);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('total_paid_online')
                            ->label(__('Total Paid (Online)'))
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Calculate total amount paid
                                $totalPaid = (float)($get('total_paid_cash') ?? 0) + (float)$state;
                                $set('total_amount_paid', $totalPaid);

                                // Calculate change
                                $change = max(0, $totalPaid - (float)($get('total_price') ?? 0));
                                $set('change_given', $change);

                                // If this is an edit form and invoice exists, update it
                                $id = $get('id');
                                if ($id !== null) {
                                    $reservation = \App\Models\BookedReservation::find($id);
                                    if ($reservation && $reservation->invoice) {
                                        $reservation->invoice->update([
                                            'total_paid_online' => $state,
                                            'total_amount_paid' => $totalPaid
                                        ]);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('total_amount_paid')
                            ->label(__('Total Amount Paid'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('change_given')
                            ->label(__('Change Given'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('payment_method')
                            ->label(__('Payment Method'))
                            ->options([
                                'cash' => __('Cash'),
                                'card' => __('Card'),
                                'bank_transfer' => __('Bank Transfer'),
                                'multiple' => __('Multiple'),
                            ])
                            ->default('cash')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Reset payment fields based on selected method
                                $id = $get('id');
                                if ($state === 'cash') {
                                    $set('total_paid_online', 0);
                                    if ($id !== null) {
                                        $reservation = \App\Models\BookedReservation::find($id);
                                        if ($reservation && $reservation->invoice) {
                                            $reservation->invoice->update([
                                                'total_paid_online' => 0,
                                                'payment_method' => $state
                                            ]);
                                        }
                                    }
                                } elseif ($state === 'card' || $state === 'bank_transfer') {
                                    $set('total_paid_cash', 0);
                                    if ($id !== null) {
                                        $reservation = \App\Models\BookedReservation::find($id);
                                        if ($reservation && $reservation->invoice) {
                                            $reservation->invoice->update([
                                                'total_paid_cash' => 0,
                                                'payment_method' => $state
                                            ]);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\Textarea::make('notes')
                            ->label(__('Notes'))
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_detail')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->alignEnd()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->customer_id) {
                            return __('Guest');
                        } else {
                            $customerData = json_decode($record->customer_detail, true);
                            $locale = app()->getLocale();
                            $name = $customerData['name_' . $locale] ?? $customerData['name_en'];
                            return new \Illuminate\Support\HtmlString("<a href='/admin/customers/{$record->customer_id}/edit' class='text-primary-600 hover:text-primary-500'>{$name}</a>");
                        }
                    })
                    ->html()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('reservation_date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('booked_from')
                    ->label(__('Booked From'))
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => str($state)
                            ->snake()
                            ->replace('_', ' ')
                            ->title()
                            ->toString()
                    )
                    ->color(fn(string $state): string => match ($state) {
                        'website' => 'success',
                        'point_of_sale' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Start Time'))
                    ->time('h:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->time('h:i A')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_duration_minutes')
                    ->label(__('Duration'))
                    ->formatStateUsing(fn(int $state): string => "{$state} " . __('min'))
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total Duration'))
                            ->formatStateUsing(fn($state) => "{$state} " . __('min')),
                    ]),

                Tables\Columns\TextColumn::make('location_type')
                    ->label(__('Location'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'salon' => 'success',
                        'home' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of Subtotal'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('vat_amount')
                    ->label(__('VAT Amount'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of VAT'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('discount_amount')
                    ->label(__('Discount Amount'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of Discount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('other_total_discount_amount')
                    ->label(__('Other Discount'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of Other Discount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('Total'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total Amount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('total_amount_paid')
                    ->label(__('Total Paid'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of Paid Amount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'confirmed' => __('Confirmed'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                        'expired' => __('Expired'),
                        'refunded' => __('Refunded'),
                    ]),

                Tables\Filters\SelectFilter::make('location_type')
                    ->label(__('Location'))
                    ->options([
                        'salon' => __('At Salon'),
                        'home' => __('At Home'),
                    ]),

                Tables\Filters\Filter::make('date_range')
                    ->label(__('Date Range'))
                    ->form([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\ToggleButtons::make('date_type')
                                    ->label(__('Date Type'))
                                    ->options([
                                        'created_at' => __('Created Date'),
                                        'reservation_date' => __('Reservation Date'),
                                    ])
                                    ->default('created_at')
                                    ->inline()
                                    ->grouped()
                                    ->colors([
                                        'created_at' => 'primary',
                                        'reservation_date' => 'gray',
                                    ])
                                    ->reactive(),

                                Forms\Components\Select::make('range')
                                    ->label(__('Select Range'))
                                    ->options([
                                        'today' => __('Today'),
                                        'last_7_days' => __('Last 7 Days'),
                                        'last_30_days' => __('Last 30 Days'),
                                        'all_time' => __('All Time'),
                                        'custom' => __('Custom Range'),
                                    ])
                                    ->reactive(),

                                Forms\Components\DatePicker::make('from')
                                    ->label(__('From'))
                                    ->visible(fn(callable $get) => $get('range') === 'custom'),

                                Forms\Components\DatePicker::make('until')
                                    ->label(__('Until'))
                                    ->visible(fn(callable $get) => $get('range') === 'custom'),
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        $dateField = $data['date_type'] ?? 'created_at';

                        return $query
                            ->when(
                                $data['range'] === 'today',
                                fn($query) => $query->whereDate($dateField, Carbon::today()),
                            )
                            ->when(
                                $data['range'] === 'last_7_days',
                                fn($query) => $query->whereBetween($dateField, [
                                    Carbon::now()->subDays(7),
                                    Carbon::now(),
                                ]),
                            )
                            ->when(
                                $data['range'] === 'last_30_days',
                                fn($query) => $query->whereBetween($dateField, [
                                    Carbon::now()->subDays(30),
                                    Carbon::now(),
                                ]),
                            )
                            ->when(
                                $data['range'] === 'custom',
                                fn($query) => $query
                                    ->when(
                                        $data['from'],
                                        fn($query) => $query->whereDate($dateField, '>=', $data['from']),
                                    )
                                    ->when(
                                        $data['until'],
                                        fn($query) => $query->whereDate($dateField, '<=', $data['until']),
                                    ),
                            );
                    }),

                Tables\Filters\SelectFilter::make('booked_from')
                    ->label(__('Booked From'))
                    ->options([
                        'website' => __('Website'),
                        'point_of_sale' => __('Point of Sale'),
                    ])
                    ->placeholder(__('All Sources')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('View')),
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete')),
                Tables\Actions\Action::make('print')
                    ->label(__('Print'))
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn(BookedReservation $record) => route('reservations.invoice', ['id' => $record->id]))
                    ->extraAttributes([
                        'onclick' => 'openPrintPreview(this.href); return false;'
                    ])
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label(__('Export'))
                    ->fileName('Reservations')
                    ->defaultFormat('pdf')
                    ->disablePreview()
                    ->disableAdditionalColumns()
                    ->defaultPageOrientation('landscape')
                    ->timeFormat('Y-m-d-H-i')
                    ->extraViewData(function ($action) {
                        $query = $action->getRecords();
                        $dateRange = $action->getTable()->getFilters()['date_range']->getState();

                        $from = null;
                        $until = null;
                        $rangeLabel = null;

                        if ($dateRange['range'] === 'today') {
                            $from = Carbon::today();
                            $until = Carbon::today();
                            $rangeLabel = __('Today');
                        } elseif ($dateRange['range'] === 'last_7_days') {
                            $from = Carbon::now()->subDays(7);
                            $until = Carbon::now();
                            $rangeLabel = __('Last 7 Days');
                        } elseif ($dateRange['range'] === 'last_30_days') {
                            $from = Carbon::now()->subDays(30);
                            $until = Carbon::now();
                            $rangeLabel = __('Last 30 Days');
                        } elseif ($dateRange['range'] === 'custom') {
                            $from = $dateRange['from'];
                            $until = $dateRange['until'];
                            $rangeLabel = __('Custom Range');
                        } elseif ($dateRange['range'] === 'all_time') {
                            $rangeLabel = __('All Time');
                        }

                        return [
                            'title' => __('Reservations Report'),
                            'summary' => [
                                'subtotal' => $query->sum('subtotal'),
                                'vat_amount' => $query->sum('vat_amount'),
                                'discount_amount' => $query->sum('discount_amount'),
                                'other_total_discount_amount' => $query->sum('other_total_discount_amount'),
                                'total_price' => $query->sum('total_price'),
                                'total_amount_paid' => $query->sum('total_amount_paid'),
                            ],
                            'currency' => Setting::get('currency', '<span class="icon-saudi_riyal"></span>'),
                            'date_range' => [
                                'range' => $dateRange['range'],
                                'range_label' => $rangeLabel,
                                'from' => $from instanceof Carbon ? $from->format('Y-m-d') : $from,
                                'until' => $until instanceof Carbon ? $until->format('Y-m-d') : $until,
                            ],
                        ];
                    })
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
            'index' => Pages\ListBookedReservations::route('/'),
            'create' => Pages\CreateBookedReservation::route('/create'),
            'quick-create' => Pages\QuickCreateBookedReservation::route('/quick-create'),
            'view' => Pages\ViewBookedReservation::route('/{record}'),
            'edit' => Pages\EditBookedReservation::route('/{record}/edit'),
        ];
    }
}
