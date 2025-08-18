<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationSettingResource\Pages;
use App\Models\ReservationSetting;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

class ReservationSettingResource extends Resource
{
    protected static ?string $model = ReservationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function getModelLabel(): string
    {
        return __('Point of Sale\'s Time Intervals');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Point of Sale\'s Time Intervals');
    }

    public static function getNavigationLabel(): string
    {
        return __('Point of Sale\'s Time Intervals');
    }

    public static function getNavigationGroup(): string
    {
        return __('Point of Sale');
    }



    /**
     * Custom handler for unauthorized actions
     */
    public static function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this reservation setting.'))
            ->danger()
            ->persistent()
            ->send();
    }

    /**
     * Determine if the authenticated user can manage the given reservation setting.
     */
    public static function canManageReservationSetting(ReservationSetting $reservationSetting): bool
    {
        return Gate::allows('update', $reservationSetting);
    }

    /**
     * Check if a reservation setting already exists for the given date and point of sale
     *
     * @param string $date
     * @param int $pointOfSaleId
     * @return ReservationSetting|null
     */
    public static function getExistingReservationSetting(string $date, int $pointOfSaleId): ?ReservationSetting
    {
        return ReservationSetting::where('date', $date)
            ->where('point_of_sale_id', $pointOfSaleId)
            ->first();
    }

    public static function form(Form $form): Form
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isPosUser = $user && $user->isPointOfSale();
        $userPosId = null;

        // Get user's point of sale ID if applicable
        if ($isPosUser && $user->pointOfSale) {
            $userPosId = $user->pointOfSale->id;
        }

        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('Schedule Settings'))
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        // Hidden field for point_of_sale_id that's always included for POS users
                                        Forms\Components\Hidden::make('point_of_sale_id')
                                        ->default($userPosId)
                                        ->disabled(false)
                                        ->visible($isPosUser)
                                        ->live()
                                        ->afterStateUpdated(fn(Forms\Set $set) => $set('taxes', [])),

                                        Forms\Components\Select::make('point_of_sale_id')
                                            ->label(__('Point of Sale'))
                                            ->relationship('pointOfSale', 'name_en')
                                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('staff', []))
                                            ->hidden(function () use ($isPosUser) {
                                                return $isPosUser;
                                            }),

                                        Forms\Components\DatePicker::make('date')
                                            ->label(__('Date'))
                                            ->required()
                                            ->default(now()->toDateString())
                                            ->native(false)
                                            ->closeOnDateSelection()
                                            ->afterOrEqual(now()->toDateString())
                                            ->reactive()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                if ($state) {
                                                    $dayOfWeek = Carbon::parse($state)->dayOfWeek;
                                                    $set('day_of_week', (string) $dayOfWeek);

                                                    // Update the apply_to_same_days toggle label
                                                    $dayName = getDayName($dayOfWeek);
                                                    $set('apply_to_same_days_label', sprintf(__('Update existing %s settings'), $dayName));
                                                }
                                            }),

                                        Forms\Components\Select::make('day_of_week')
                                            ->label(__('Day of Week'))
                                            ->options(getDaysOfWeek())
                                            ->default((string)now()->dayOfWeek)
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\TimePicker::make('opening_time')
                                            ->label(__('Opening Time'))
                                            ->seconds(false)
                                            ->default('09:00')
                                            ->required(),

                                        Forms\Components\TimePicker::make('closing_time')
                                            ->label(__('Closing Time'))
                                            ->seconds(false)
                                            ->default('17:00')
                                            ->required()
                                            ->after('opening_time'),

                                        Forms\Components\Hidden::make('workers_count')
                                            ->dehydrated()
                                            ->default(1),

                                        Forms\Components\Toggle::make('is_closed')
                                            ->label(__('Salon closed on this day'))
                                            ->default(false),

                                        Forms\Components\Hidden::make('apply_to_same_days_label')
                                            ->default(function() {
                                                $dayOfWeek = now()->dayOfWeek;
                                                $dayName = getDayName($dayOfWeek);
                                                return sprintf(__('Update existing %s settings'), $dayName);
                                            }),

                                        Forms\Components\Toggle::make('apply_to_same_days')
                                            ->label(fn (Get $get): string => $get('apply_to_same_days_label') ?: __('Update all existing future dates with same day'))
                                            ->default(false)
                                            ->dehydrated(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isPosUser = $user && $user->isPointOfSale();
        $userPosId = null;

        // Get user's point of sale ID if applicable
        if ($isPosUser && $user->pointOfSale) {
            $userPosId = $user->pointOfSale->id;
        }

        $table = $table
            ->modifyQueryUsing(function ($query) {
                // Don't apply any date filtering by default
                // The filter will handle date filtering
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('pointOfSale.name')
                    ->sortable()
                    ->searchable()
                    ->label(__('Point of Sale'))
                    ->visible(!$isPosUser),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label(__('Date')),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => __('Sunday'),
                        '1' => __('Monday'),
                        '2' => __('Tuesday'),
                        '3' => __('Wednesday'),
                        '4' => __('Thursday'),
                        '5' => __('Friday'),
                        '6' => __('Saturday'),
                        default => $state,
                    })
                    ->sortable()
                    ->label(__('Day of Week')),
                Tables\Columns\TextColumn::make('opening_time')
                    ->time('h:i A')
                    ->label(__('Opening Time')),
                Tables\Columns\TextColumn::make('closing_time')
                    ->time('h:i A')
                    ->label(__('Closing Time')),
                Tables\Columns\TextColumn::make('workers_count')
                    ->label(__('Workers Count')),
                Tables\Columns\IconColumn::make('is_closed')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->label(__('Is Open'))
                    ->getStateUsing(fn ($record): bool => $record->is_closed),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Created At')),
            ]);

        $table = $table->filters([
            Tables\Filters\Filter::make('default_week')
                ->label(__('Current Week Only'))
                ->query(function (Builder $query) {
                    // Limit to only show 7 days (today + next 6 days)
                    $today = now()->toDateString();
                    $oneWeekLater = now()->addDays(6)->toDateString();

                    return $query->whereBetween('date', [$today, $oneWeekLater]);
                })
                ->default(),
            Tables\Filters\SelectFilter::make('point_of_sale_id')
                ->relationship('pointOfSale', 'name_en')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                ->label(__('Point of Sale'))
                ->visible(!$isPosUser),
            Tables\Filters\Filter::make('upcoming')
                ->label(__('Upcoming Dates'))
                ->query(fn ($query) => $query->where('date', '>=', now()->toDateString())),
            Tables\Filters\Filter::make('past')
                ->label(__('Past Dates'))
                ->query(fn ($query) => $query->where('date', '<', now()->toDateString())),
            Tables\Filters\SelectFilter::make('day_of_week')
                ->options(getDaysOfWeek())
                ->label(__('Day of Week')),
        ]);

        // If user is a point of sale, restrict to their own records
        if ($isPosUser && $userPosId) {
            $table->modifyQueryUsing(function (Builder $query) use ($userPosId) {
                $query->where('point_of_sale_id', $userPosId);
            });
        }

        return $table
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
            'index' => Pages\ListReservationSettings::route('/'),
            'create' => Pages\CreateReservationSetting::route('/create'),
            'edit' => Pages\EditReservationSetting::route('/{record}/edit'),
        ];
    }

    /**
     * Mutate form data before create/save
     */
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // If user is a point of sale and point_of_sale_id is missing or null, set it
        if ($user && $user instanceof User && $user->isPointOfSale() && empty($data['point_of_sale_id'])) {
            $data['point_of_sale_id'] = $user->pointOfSale->id ?? null;
        }

        return $data;
    }
}
