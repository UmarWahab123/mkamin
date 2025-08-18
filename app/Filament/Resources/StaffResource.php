<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LeafletMap;
use App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource\RelationManagers;
use App\Models\Staff;
use App\Models\PointOfSale;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Added translation methods
    public static function getModelLabel(): string
    {
        return __('Staff');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Staff');
    }

    public static function getNavigationLabel(): string
    {
        return __('Staff');
    }

    public static function getNavigationGroup(): string
    {
        return __('Point of Sale');
    }

    protected static ?int $navigationSort = 11;



    /**
     * Custom handler for unauthorized actions
     */
    public static function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this staff member.'))
            ->danger()
            ->persistent()
            ->send();
    }

    public static function form(Form $form): Form
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isPosUser = $user && $user->hasRole('point_of_sale');
        $userPosId = null;
        $isStaffEditingOwnProfile = $user && $user->hasRole('staff') && $user->staff && $user->staff->id === request()->route('record');

        // Get user's point of sale ID if applicable
        if ($isPosUser) {
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            if ($pointOfSale) {
                $userPosId = $pointOfSale->id;
            }
        }

        return $form
            ->schema([
                Forms\Components\Section::make(__('Staff Information'))
                    ->schema([
                        // Hidden field for point_of_sale_id that's always included for POS users
                        Forms\Components\Hidden::make('point_of_sale_id')
                            ->default($userPosId)
                            ->disabled(false)
                            ->visible($isPosUser)
                            ->reactive()
                            ->columnSpanFull()
                            ->afterStateUpdated(fn(callable $set) => $set('product_and_services', [])),
                        Forms\Components\Section::make(__('Assignments'))
                            ->schema([
                                Forms\Components\Select::make('point_of_sale_id')
                                    ->label(__('Point of Sale'))
                                    ->relationship('pointOfSale', 'name_en')
                                    ->required()
                                    ->preload()
                                    ->searchable()
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set) => $set('product_and_services', []))
                                    // ->hidden($isStaffEditingOwnProfile)
                                    ->disabled(function () {
                                        /** @var User|null $user */
                                        $user = Auth::user();
                                        return !($user && $user->hasRole('super_admin'));
                                    }),
                            ])
                            ->hidden(function () {
                                /** @var User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole('point_of_sale');
                            })
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name_en')
                                    ->label(__('Name (English)'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name_ar')
                                    ->label(__('Name (Arabic)'))
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('position_en')
                                    ->label(__('Position (English)'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('position_ar')
                                    ->label(__('Position (Arabic)'))
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label(__('Email'))
                                    ->email()
                                    ->maxLength(255)
                                    ->required(),
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
                            ]),

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

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('resume')
                                    ->label(__('Resume'))
                                    ->disk('public')
                                    ->directory('staff/resumes')
                                    ->visibility('public')
                                    ->openable()
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(10240),
                                Forms\Components\FileUpload::make('images')
                                    ->label(__('Images'))
                                    ->disk('public')
                                    ->directory('staff/images')
                                    ->visibility('public')
                                    ->image()
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->openable()
                                    ->maxSize(5120),
                            ]),

                        Forms\Components\Select::make('product_and_services')
                            ->label(__('Services & Products'))
                            ->relationship(
                                name: 'productAndServices',
                                modifyQueryUsing: function (Builder $query, callable $get) {
                                    $pointOfSaleId = $get('point_of_sale_id');
                                    $query->orderBy('created_at', 'desc');
                                    if ($pointOfSaleId) {
                                        return $query->where('point_of_sale_id', $pointOfSaleId);
                                    }
                                    return $query;
                                }
                            )
                            ->multiple()
                            ->preload()
                            ->required()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name}" . ($record->is_product ? " (" . __('Product') . ")" : " (" . __('Service') . ")"))
                            ->helperText(__('Select the services and products this staff member can provide'))
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('Status'))
                                    ->default(true)
                                    ->hidden(function () {
                                        /** @var User|null $user */
                                        $user = Auth::user();
                                        return !($user && ($user->hasRole('super_admin') || $user->hasRole('point_of_sale')));
                                    }),
                                Forms\Components\Toggle::make('can_edit_profile')
                                    ->label(__('Can Edit Profile'))
                                    ->default(false)
                                    ->helperText(__('Allow this staff member to edit their own profile'))
                                    ->hidden(function () {
                                        /** @var User|null $user */
                                        $user = Auth::user();
                                        return !($user && ($user->hasRole('super_admin') || $user->hasRole('point_of_sale')));
                                    }),
                            ]),
                    ]),

                Forms\Components\Section::make(__('Account Information'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label(__('Password'))
                                    ->password()
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->maxLength(255)
                                    ->confirmed(),
                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label(__('Confirm Password'))
                                    ->password()
                                    ->dehydrated(false)
                                    ->required(fn(string $context): bool => $context === 'create'),
                            ]),
                    ]),

                Forms\Components\Section::make(__('Default Working Hours'))
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TimePicker::make('default_start_time')
                                    ->label(__('Default Start Time'))
                                    ->seconds(false)
                                    ->required()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if (empty($state)) {
                                            $component->state(Setting::get('default_start_time'));
                                        }
                                    }),
                                Forms\Components\TimePicker::make('default_end_time')
                                    ->label(__('Default End Time'))
                                    ->seconds(false)
                                    ->required()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if (empty($state)) {
                                            $component->state(Setting::get('default_end_time'));
                                        }
                                    }),
                                Forms\Components\Select::make('default_closed_day')
                                    ->label(__('Default Day Off'))
                                    ->options([
                                        '0' => __('Sunday'),
                                        '1' => __('Monday'),
                                        '2' => __('Tuesday'),
                                        '3' => __('Wednesday'),
                                        '4' => __('Thursday'),
                                        '5' => __('Friday'),
                                        '6' => __('Saturday'),
                                    ])
                                    ->placeholder(__('Select day'))
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if (empty($state)) {
                                            $component->state(Setting::get('default_closed_day'));
                                        }
                                    }),
                            ]),
                        Forms\Components\Select::make('default_home_visit_days')
                            ->label(__('Default Home Visit Days'))
                            ->multiple()
                            ->options([
                                '0' => __('Sunday'),
                                '1' => __('Monday'),
                                '2' => __('Tuesday'),
                                '3' => __('Wednesday'),
                                '4' => __('Thursday'),
                                '5' => __('Friday'),
                                '6' => __('Saturday'),
                            ])
                            ->placeholder(__('Select days'))
                            ->helperText(__('These default values will not effect your current week schedule, it will only be used for automatically generated schedules.'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name (English)'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($query) use ($search) {
                            $query->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label(__('Position'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($query) use ($search) {
                            $query->where('position_en', 'like', "%{$search}%")
                                ->orWhere('position_ar', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                // Tables\Columns\TextColumn::make('phone_number')
                //     ->label(__('Phone Number'))
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('pointOfSale.name')
                //     ->label(__('Point of Sale'))
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Status')),
                Tables\Columns\IconColumn::make('can_edit_profile')
                    ->boolean()
                    ->label(__('Can Edit Profile')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Status')),
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)')),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)')),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['name_en']) && $data['name_en']) {
                            $query->where('name_en', 'like', "%{$data['name_en']}%");
                        }

                        if (isset($data['name_ar']) && $data['name_ar']) {
                            $query->where('name_ar', 'like', "%{$data['name_ar']}%");
                        }
                    }),

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
                Tables\Actions\ViewAction::make()
                    ->label(__('View')),
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('Delete'))
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Check if user can delete ALL selected records
                            foreach ($records as $record) {
                                if (!static::canManageStaff($record)) {
                                    static::handleRecordBelongsToAnotherPOS();
                                    $this->halt();
                                    break;
                                }

                                // Delete associated time intervals
                                $record->timeIntervals()->delete();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Only show relation managers for super_admin and point_of_sale users
        // if (!($user && ($user->hasRole('super_admin') || $user->hasRole('point_of_sale')))) {
        //     return [];
        // }

        return [
            RelationManagers\TimeIntervalsRelationManager::class,
            RelationManagers\BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'view' => Pages\ViewStaff::route('/{record}'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }

    // Mutate form data before create
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $timeIntervals = $data['time_intervals'] ?? [];
        unset($data['time_intervals']);

        return $data;
    }

    // Mutate form data before save
    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['time_intervals'])) {
            unset($data['time_intervals']);
        }

        return $data;
    }

    protected static function afterUpdating(Staff $staff, array $data): void
    {
        static::saveTimeIntervals($staff, $data);
    }

    protected static function saveTimeIntervals(Staff $staff, array $data): void
    {
        if (isset($data['time_intervals']) && is_array($data['time_intervals'])) {
            // Get the dates for the intervals being saved
            $dates = array_map(function ($interval) {
                return $interval['date'] ?? null;
            }, $data['time_intervals']);

            // Filter out any nulls
            $dates = array_filter($dates);

            // If we have dates, delete any existing intervals for these dates
            if (!empty($dates)) {
                $staff->timeIntervals()
                    ->whereIn('date', $dates)
                    ->delete();
            }

            // Create the new intervals
            foreach ($data['time_intervals'] as $timeInterval) {
                if (isset($timeInterval['date'])) {
                    $staff->timeIntervals()->create($timeInterval);
                }
            }
        }
    }

    /**
     * Determine if the authenticated user can perform actions on the given staff.
     */
    public static function canManageStaff(Staff $staff): bool
    {
        return Gate::allows('delete', $staff);
    }

    protected static function afterCreate(Staff $staff, array $data): void
    {
        // Handle the product_and_services relationship
        if (isset($data['product_and_services'])) {
            try {
                Log::info('Creating staff_product_service relations: ' . json_encode($data['product_and_services']));
                $staff->productAndServices()->sync($data['product_and_services']);
                Log::info('Successfully synced product_and_services');
            } catch (\Exception $e) {
                Log::error('Failed to sync product_and_services: ' . $e->getMessage());
            }
        }

        static::saveTimeIntervals($staff, $data);
    }

    protected static function afterSave(Staff $staff, array $data): void
    {
        Log::info('Starting afterSave for staff: ' . $staff->id);

        // Handle the product_and_services relationship
        if (isset($data['product_and_services'])) {
            try {
                Log::info('Syncing product_and_services: ' . json_encode($data['product_and_services']));
                $staff->productAndServices()->sync($data['product_and_services']);
                Log::info('Successfully synced product_and_services');
            } catch (\Exception $e) {
                Log::error('Failed to sync product_and_services: ' . $e->getMessage());
            }
        }
    }
}
