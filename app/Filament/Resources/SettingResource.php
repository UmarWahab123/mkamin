<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use App\Models\PointOfSale;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Add translation methods
    public static function getModelLabel(): string
    {
        return __('Setting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Other Settings');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    // Disable create functionality
    // public static function canCreate(): bool
    // {
    //     return false;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label(__('Key'))
                    ->disabled(fn ($record) => $record !== null)
                    ->required(),
                Forms\Components\Select::make('field_type')
                    ->label(__('Field Type'))
                    ->options([
                        'text' => __('Text'),
                        'text_area' => __('Text Area'),
                        'rich_text_editor' => __('Rich Text Editor'),
                        'image' => __('Image'),
                        'color_picker' => __('Color Picker'),
                        'date' => __('Date'),
                        'time' => __('Time'),
                        'day' => __('Day of Week'),
                    ])
                    ->default('text')
                    ->required()
                    ->live(),
                Forms\Components\Section::make()
                    ->schema(function (Get $get) {
                        $fieldType = $get('field_type');

                        return match ($fieldType) {
                            'text' => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                            'text_area' => [
                                Forms\Components\Textarea::make('value')
                                    ->label(__('Value'))
                                    ->rows(5)
                                    ->required(),
                            ],
                            'rich_text_editor' => [
                                Forms\Components\RichEditor::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                            'image' => [
                                Forms\Components\FileUpload::make('value')
                                    ->label(__('Value'))
                                    ->image()
                                    ->directory('settings')
                                    ->required(),
                            ],
                            'color_picker' => [
                                Forms\Components\ColorPicker::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                            'date' => [
                                Forms\Components\DatePicker::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                            'time' => [
                                Forms\Components\TimePicker::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                            'day' => [
                                Forms\Components\Select::make('value')
                                    ->label(__('Value'))
                                    ->options([
                                        '0' => __('Sunday'),
                                        '1' => __('Monday'),
                                        '2' => __('Tuesday'),
                                        '3' => __('Wednesday'),
                                        '4' => __('Thursday'),
                                        '5' => __('Friday'),
                                        '6' => __('Saturday'),
                                    ])
                                    ->required(),
                            ],
                            default => [
                                Forms\Components\TextInput::make('value')
                                    ->label(__('Value'))
                                    ->required(),
                            ],
                        };
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label(__('Key'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('field_type')
                    ->label(__('Field Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\ViewColumn::make('value')
                    ->label(__('Value'))
                    ->view('filament.tables.columns.setting-value-column'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Delete')),
            ])
            ->bulkActions([
                // Removed bulk actions to prevent deletion
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
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
            'create' => Pages\CreateSetting::route('/create'),
        ];
    }

    /**
     * Check if the setting requires updating time intervals
     *
     * @param Setting $record The setting record
     * @return bool
     */
    public static function shouldUpdateTimeIntervals(Setting $record): bool
    {
        $schedulingKeys = [
            'default_start_time',
            'default_end_time',
            'default_closed_day',
            'advance_booking_days_limit'
        ];

        return in_array($record->key, $schedulingKeys);
    }

    /**
     * Update time intervals for all points of sale
     *
     * @param Setting $record The setting record
     * @return void
     */
    public static function updateTimeIntervals(Setting $record): void
    {
        if (!self::shouldUpdateTimeIntervals($record)) {
            return;
        }

        // Get the point of sale ID from the authenticated user
        $pointOfSaleId = null;
        $user = Auth::user();

        if ($user && $user->pointOfSale) {
            $pointOfSaleId = $user->pointOfSale->id;
        } else {
            // Get the main branch if user doesn't have a point of sale
            $mainBranch = PointOfSale::getMainBranch();
            if ($mainBranch) {
                $pointOfSaleId = $mainBranch->id;
            }
        }

        // Call the helper function to update time intervals if we have a point of sale ID
        if ($pointOfSaleId) {
            createPosTimeIntervals($pointOfSaleId);
            $staffs = Staff::where('point_of_sale_id', $pointOfSaleId)->get();
            foreach ($staffs as $staff) {
                createStaffTimeIntervals($staff->id);
            }
        }
    }
}
