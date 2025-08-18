<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Models\ServiceCategory;
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
use Illuminate\Database\Eloquent\Builder;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Add translation methods
    public static function getModelLabel(): string
    {
        return __('Service Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Service Categories');
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
        $isStaffUser = $user && $user->hasRole('staff');
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
                Forms\Components\Section::make(__('English'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description_en')
                            ->label(__('Description (English)'))
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make(__('Arabic'))
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description_ar')
                            ->label(__('Description (Arabic)'))
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make(__('Settings'))
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(fn() => !$isStaffUser)
                            ->disabled(fn() => $isStaffUser)
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),
                        // Hidden field for point_of_sale_id that's always included for POS users
                        Forms\Components\Hidden::make('point_of_sale_id')
                            ->default($userPosId)
                            ->disabled(false)
                            ->visible($isPosUser || $isStaffUser)
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
                    ->hidden(function () use ($isPosUser, $isStaffUser) {
                        return $isPosUser || $isStaffUser;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('listed_by')
                    ->label(__('Listed By'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('addedBy', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
                Tables\Filters\SelectFilter::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('View'))
                    ->before(function (ServiceCategory $record) {
                        // Check if user can view this record
                        if (Gate::denies('view', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
                Tables\Actions\EditAction::make()
                    ->label(__('Edit'))
                    ->before(function (ServiceCategory $record) {
                        // Check if user can edit this record
                        if (Gate::denies('update', $record)) {
                            static::handleRecordBelongsToAnotherPOS();
                            $this->halt();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete'))
                    ->before(function (ServiceCategory $record) {
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
            'index' => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'view' => Pages\ViewServiceCategory::route('/{record}'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }

    /**
     * Handle unauthorized access attempts
     */
    public static function handleRecordBelongsToAnotherPOS(): void
    {
        Notification::make()
            ->title(__('Access Denied'))
            ->body(__('You do not have permission to access this service category.'))
            ->danger()
            ->persistent()
            ->send();

        redirect()->route('filament.admin.resources.service-categories.index');
    }
}
