<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\ProductAndService;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return __('Invoice');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Invoices');
    }

    public static function getNavigationLabel(): string
    {
        return __('Invoices');
    }

    public static function getNavigationGroup(): string
    {
        return __('Reservations');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $vatPercentage = (float) Setting::get('vat_percentage', 15);

        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make(__('Invoice Details'))
                            ->schema([
                                Forms\Components\TextInput::make('invoice_number')
                                    ->label(__('Invoice Number'))
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('customer_id')
                                    ->label(__('Customer'))
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('point_of_sale_id')
                                    ->label(__('Point of Sale'))
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\DatePicker::make('reservation_date')
                                    ->label(__('Reservation Date'))
                                    ->required(),

                                Forms\Components\TimePicker::make('start_time')
                                    ->label(__('Start Time'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label(__('End Time'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\TextInput::make('total_duration_minutes')
                                    ->label(__('Duration (minutes)'))
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label(__('Subtotal'))
                                    ->numeric()
                                    ->required()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\TextInput::make('discount_amount')
                                    ->label(__('Discount Amount'))
                                    ->numeric()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\TextInput::make('discount_code')
                                    ->label(__('Discount Code')),

                                Forms\Components\TextInput::make('vat_amount')
                                    ->label(__('VAT Amount'))
                                    ->numeric()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\TextInput::make('other_taxes_amount')
                                    ->label(__('Other Taxes'))
                                    ->numeric()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\TextInput::make('total_price')
                                    ->label(__('Total Price'))
                                    ->numeric()
                                    ->required()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\Select::make('status')
                                    ->label(__('Status'))
                                    ->options([
                                        'pending' => __('Pending'),
                                        'confirmed' => __('Confirmed'),
                                        'completed' => __('Completed'),
                                        'cancelled' => __('Cancelled'),
                                    ])
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpan('full'),
                            ]),

                        Forms\Components\Section::make(__('Payment Information'))
                            ->schema([
                                Forms\Components\TextInput::make('total_paid_cash')
                                    ->label(__('Cash Payment'))
                                    ->numeric()
                                    ->prefix(Setting::get('currency_symbol', '$')),

                                Forms\Components\TextInput::make('total_paid_online')
                                    ->label(__('Online Payment'))
                                    ->numeric()
                                    ->prefix(Setting::get('currency_symbol', '$')),
                            ]),

                        Forms\Components\Section::make(__('Location Information'))
                            ->schema([
                                Forms\Components\Select::make('location_type')
                                    ->label(__('Location Type'))
                                    ->options([
                                        'salon' => __('Salon'),
                                        'home' => __('Home'),
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('address')
                                    ->label(__('Address')),

                                Forms\Components\TextInput::make('latitude')
                                    ->label(__('Latitude'))
                                    ->numeric(),

                                Forms\Components\TextInput::make('longitude')
                                    ->label(__('Longitude'))
                                    ->numeric(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable()
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

                Tables\Columns\TextColumn::make('customer_detail')
                    ->label(__('Customer'))
                    ->formatStateUsing(function ($state) {
                        $data = json_decode($state, true);
                        $locale = app()->getLocale();
                        return $data["name_{$locale}"] ?? $data['name_en'] ?? '';
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('reservation_date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable()
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
                            ->label(__('Total of Amount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => __('Pending'),
                        'confirmed' => __('Confirmed'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                        default => $state,
                    })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label(__('Notes'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_amount_paid')
                    ->label(__('Total Paid'))
                    ->formatStateUsing(function ($state) {
                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($state, 2));
                    })
                    ->html()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('Total of Paid Amount'))
                            ->formatStateUsing(fn($state) => new \Illuminate\Support\HtmlString(
                                Setting::get('currency', '<span class="icon-saudi_riyal"></span>') . ' ' . number_format($state, 2)
                            )),
                    ]),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('Payment Method'))
                    ->searchable()
                    ->sortable()
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
                    ->multiple()
                    ->options([
                        'pending' => __('Pending'),
                        'confirmed' => __('Confirmed'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                    ])
                    ->default(['confirmed', 'completed']),

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
                    ->url(fn(Invoice $record) => route('invoices.print', ['invoice' => $record]))
                    ->extraAttributes([
                        'onclick' => 'let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); let isAndroid = /Android/i.test(navigator.userAgent); openPrintPreview(this.href, isMobile, !isAndroid); return false;'
                    ])
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label(__('Export'))
                    ->fileName('Invoices')
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
                            'title' => __('Invoices Report'),
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
            \App\Filament\Resources\InvoiceResource\RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
