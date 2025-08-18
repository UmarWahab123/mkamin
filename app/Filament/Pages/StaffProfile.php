<?php

namespace App\Filament\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Filament\Resources\StaffResource\RelationManagers\TimeIntervalsRelationManager;
use App\Filament\Resources\StaffResource\RelationManagers\BookingsRelationManager;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Pages\Concerns\HasRelationManagers;
use App\Filament\Forms\Components\LeafletMap;
use Filament\Forms\Components\Hidden;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class StaffProfile extends Page
{
    use InteractsWithForms;
    use HasRelationManagers;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $slug = 'staff-profile';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.staff-profile';

    public ?array $data = [];
    public $record = null;

    public static function getNavigationLabel(): string
    {
        return __('Profile');
    }

    public static function getModelLabel(): string
    {
        return __('Profile');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Profiles');
    }

    public function getHeading(): string
    {
        return __('Profile');
    }

    public $activeTab = 'profile';

    protected static string $resource = StaffResource::class;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->staff;
    }

    public function mount(): void
    {
        $user = Auth::user();

        if (!$user || !$user->staff) {
            Notification::make()
                ->title('Access Denied')
                ->body('You do not have permission to access this page.')
                ->danger()
                ->send();

            redirect()->route('filament.admin.pages.dashboard');
            return;
        }

        $this->activeTab = request('tab', 'profile');
        $this->record = $user->staff;
        $data = $this->record->toArray();

        // Load the existing product and services
        $data['product_and_services'] = $this->record->productAndServices->pluck('id')->toArray();

        $this->form->fill($data);

        // Show notification if profile is not active
        if (!$this->record->is_active) {
            $this->showInactiveProfileNotification();
        }
    }

    protected function showInactiveProfileNotification(): void
    {
        Notification::make()
            ->title(__('Profile Status: Pending Approval'))
            ->body(__('Your staff profile is currently awaiting administrative review. While your profile is being reviewed, you can continue to update your information and complete your profile details. Once approved, your profile will become visible to users.'))
            ->warning()
            ->persistent()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label(__('Save'))
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function getRelationManagers(): array
    {
        return [
            BookingsRelationManager::class,
            TimeIntervalsRelationManager::class,
        ];
    }

    public function getRecord(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->record;
    }

    public function getResourceForm(): Form
    {
        return $this->form;
    }

    public function getResourceRecord(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->record;
    }

    public function getResourceRecordTitle(): ?string
    {
        return $this->record?->name_en;
    }

    public function getResourceRecordTitleAttribute(): ?string
    {
        return 'name_en';
    }

    public function getResourceRecordLabel(): ?string
    {
        return __('Staff');
    }

    public function getResourceRecordPluralLabel(): ?string
    {
        return __('Staff');
    }

    public function getResource(): string
    {
        return static::$resource;
    }

    public function getPageClass(): string
    {
        return static::class;
    }

    public function form(Form $form): Form
    {
        if (!$this->record) {
            return $form->schema([]);
        }

        $user = Auth::user();
        $pointOfSaleId = $user->staff->point_of_sale_id;

        return $form
            ->schema([
                Section::make(__('Staff Information'))
                    ->schema([
                        TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        TextInput::make('position_en')
                            ->label(__('Position (English)'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        TextInput::make('position_ar')
                            ->label(__('Position (Arabic)'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(6),
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
                            ->columnSpan(6)
                            ->formatAsYouType(true)
                            ->countrySearch(true)
                            ->required(),
                        Textarea::make('address')
                            ->label(__('Address'))
                            ->maxLength(65535)
                            ->columnSpan(12),
                        Hidden::make('latitude')
                            ->label(__('Latitude'))
                            ->columnSpanFull(),
                        Hidden::make('longitude')
                            ->label(__('Longitude'))
                            ->columnSpanFull(),
                        LeafletMap::make('location')
                            ->label(__('Location Map'))
                            ->required()
                            ->defaultLocation([24.7136, 46.6753])
                            ->defaultZoom(8)
                            ->reactive()
                            ->afterStateHydrated(fn($state, callable $set) => $this->loadMapLocation($state, $set))
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (isset($state['lat'], $state['lng'])) {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                }
                                if (isset($state['address'])) {
                                    $set('address', $state['address']);
                                }
                            })
                            ->columnSpan(12),
                        FileUpload::make('resume')
                            ->label(__('Resume'))
                            ->disk('public')
                            ->directory('staff/resumes')
                            ->visibility('public')
                            ->openable()
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->columnSpan(6),
                        FileUpload::make('images')
                            ->label(__('Images'))
                            ->disk('public')
                            ->directory('staff/images')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->panelLayout('grid')
                            ->openable()
                            ->openable()
                            ->maxSize(5120)
                            ->columnSpan(6),
                        Select::make('product_and_services')
                            ->label(__('Services & Products'))
                            ->options(function () use ($pointOfSaleId) {
                                return \App\Models\ProductAndService::where('point_of_sale_id', $pointOfSaleId)
                                    ->get()
                                    ->sortByDesc('created_at')
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => "{$item->name}" . ($item->is_product ? " (" . __('Product') . ")" : " (" . __('Service') . ")")
                                    ]);
                            })
                            ->multiple()
                            ->preload()
                            ->required()
                            ->searchable()
                            ->prefixAction(
                                \Filament\Forms\Components\Actions\Action::make('create')
                                    ->icon('heroicon-m-plus')
                                    ->url(fn() => route('filament.admin.resources.product-and-services.create', [
                                        'redirect' => route('filament.admin.pages.staff-profile'),
                                        'closeAfterCreating' => true
                                    ]))
                                    ->openUrlInNewTab()
                            )
                            ->columnSpan(12)
                            ->helperText(__('Select the services and products this staff member can provide')),
                        // Toggle::make('is_active')
                        //     ->label(__('Active Status'))
                        //     ->default(true)
                        //     ->visible(fn() => $this->record?->is_active)
                        //     ->columnSpan(3),
                    ])
                    ->columns(12),


                Section::make(__('Account Information'))
                    ->schema([
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->minLength(8)
                            ->maxLength(255)
                            ->confirmed()
                            ->columnSpan(6),
                        TextInput::make('password_confirmation')
                            ->label(__('Confirm Password'))
                            ->password()
                            ->dehydrated(false)
                            ->columnSpan(6),
                    ])
                    ->columns(12),

                Section::make(__('Default Working Hours'))
                    ->schema([
                        TextInput::make('default_start_time')
                            ->label(__('Default Start Time'))
                            ->type('time')
                            ->required()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (empty($state)) {
                                    $component->state(Setting::get('default_start_time'));
                                }
                            }),
                        TextInput::make('default_end_time')
                            ->label(__('Default End Time'))
                            ->type('time')
                            ->required()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (empty($state)) {
                                    $component->state(Setting::get('default_end_time'));
                                }
                            }),
                        Select::make('default_closed_day')
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
                            ->required()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (empty($state)) {
                                    $component->state(Setting::get('default_closed_day'));
                                }
                            }),
                        Select::make('default_home_visit_days')
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
                    ])
                    ->columns(3)
                    ->extraAttributes(['style' => 'margin-bottom: 1.5rem']),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = Auth::user();
        if (!$user || !$user->staff) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('No staff profile found.')
                ->send();
            return;
        }

        $this->record = $user->staff;
        $data = $this->form->getState();

        try {
            DB::beginTransaction();

            // Update staff record
            $this->record->update([
                'name_en' => $data['name_en'],
                'name_ar' => $data['name_ar'],
                'position_en' => $data['position_en'],
                'position_ar' => $data['position_ar'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'address' => $data['address'],
                'resume' => $data['resume'] ?? null,
                'images' => $data['images'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'default_start_time' => $data['default_start_time'],
                'default_end_time' => $data['default_end_time'],
                'default_closed_day' => $data['default_closed_day'],
                'default_home_visit_days' => $data['default_home_visit_days'] ?? [],
            ]);

            // Sync product and services
            if (isset($data['product_and_services'])) {
                $this->record->productAndServices()->sync($data['product_and_services']);
            }

            // Update user record
            $user = $this->record->user;
            if ($user) {
                $updateData = [
                    'name' => $data['name_' . app()->getLocale()],
                    'email' => $data['email'],
                ];

                if (isset($data['password']) && $data['password']) {
                    $updateData['password'] = Hash::make($data['password']);
                }

                $user->update($updateData);
            }
            createStaffTimeIntervals($this->record->id);

            DB::commit();

            Notification::make()
                ->success()
                ->title(__('Profile updated'))
                ->body(__('Your profile has been updated successfully.'))
                ->send();

            redirect()->to(route('filament.admin.pages.dashboard'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title(__('Failed to update profile'))
                ->body(__('Could not update your profile: ' . $e->getMessage()))
                ->send();
        }
    }

    protected function loadMapLocation($state, $set)
    {
        $record = $this->record;

        \Illuminate\Support\Facades\Log::info('StaffProfile Location Values:', [
            'record_id' => $record?->id ?? 'null',
            'latitude' => $record?->latitude ?? 'null',
            'longitude' => $record?->longitude ?? 'null',
            'state' => $state
        ]);

        if ($record && $record->latitude && $record->longitude) {
            $set('location', [
                'lat' => (float) $record->latitude,
                'lng' => (float) $record->longitude
            ]);
        }
    }
}
