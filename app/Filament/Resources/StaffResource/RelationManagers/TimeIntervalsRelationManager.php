<?php

namespace App\Filament\Resources\StaffResource\RelationManagers;

use App\Models\TimeInterval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class TimeIntervalsRelationManager extends RelationManager
{
    protected static string $relationship = 'timeIntervals';

    // Using translated title with property
    protected static ?string $title = 'Working Hours';

    public static function getModelLabel(): string
    {
        return __('Time Interval');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Time Intervals');
    }

    public function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->staff !== null;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label(__('Date'))
                            ->required()
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
                            ->disabled()
                            ->dehydrated(),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TimePicker::make('start_time')
                            ->label(__('Start Time'))
                            ->seconds(false)
                            ->required()
                            ->default('09:00'),
                        Forms\Components\TimePicker::make('end_time')
                            ->label(__('End Time'))
                            ->seconds(false)
                            ->required()
                            ->default('17:00'),
                    ]),

                Forms\Components\Toggle::make('is_closed')
                    ->label(__('Off on this day'))
                    ->default(false)
                    ->reactive(),

                Forms\Components\Toggle::make('can_visit_home')
                    ->label(__('Can Visit Home'))
                    ->default(false)
                    ->helperText(__('Indicates if the staff member can visit client homes during this time slot')),

                Forms\Components\Hidden::make('apply_to_same_days_label')
                    ->default(function() {
                        $dayOfWeek = now()->dayOfWeek;
                        $dayName = getDayName($dayOfWeek);
                        return sprintf(__('Update existing %s settings'), $dayName);
                    }),

                Forms\Components\Toggle::make('apply_to_same_days')
                    ->label(fn (Forms\Get $get): string => $get('apply_to_same_days_label') ?: __('Update all existing future dates with same day'))
                    ->default(false)
                    ->dehydrated(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Don't apply any date filtering by default
                // The show_all filter will completely override this behavior
                return $query;
            })
            ->recordTitleAttribute('day_of_week')
            ->defaultSort('date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label(__('Day of Week'))
                    ->formatStateUsing(fn ($state) => getDayName($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Opening Time'))
                    ->time(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('Closing Time'))
                    ->time(),
                Tables\Columns\IconColumn::make('is_closed')
                    ->label(__('Is Open'))
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
                Tables\Columns\IconColumn::make('can_visit_home')
                    ->label(__('Home Visits'))
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\Filter::make('default_week')
                    ->label(__('Current Week Only'))
                    ->query(function (Builder $query) {
                        // Limit to only show 7 days (today + next 6 days)
                        $today = now()->toDateString();
                        $oneWeekLater = now()->addDays(6)->toDateString();

                        return $query->whereBetween('date', [$today, $oneWeekLater]);
                    })
                    ->default(),
                Tables\Filters\Filter::make('upcoming')
                    ->label(__('Upcoming Dates'))
                    ->query(fn (Builder $query) => $query->where('date', '>=', now()->toDateString())),
                Tables\Filters\Filter::make('past')
                    ->label(__('Past Dates'))
                    ->query(fn (Builder $query) => $query->where('date', '<', now()->toDateString())),
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label(__('Day of Week'))
                    ->options(getDaysOfWeek()),
                Tables\Filters\TernaryFilter::make('is_closed')
                    ->label(__('Closed Days')),
                Tables\Filters\TernaryFilter::make('can_visit_home')
                    ->label(__('Home Visit Availability')),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->label(__('Create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit'))
                    ->after(function ($record, $data) {
                        // Check if apply_to_same_days is enabled
                        $applyToSameDays = $data['apply_to_same_days'] ?? false;

                        if (!$applyToSameDays) {
                            // If toggle is not enabled, we don't need to apply to other days
                            return;
                        }

                        // Apply the same settings to future dates with the same day of week
                        $this->applySettingsToFutureDays($record);
                    }),
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

    /**
     * Apply settings from the current record to all future dates with the same day of the week
     *
     * @param TimeInterval $record The current time interval record
     * @return void
     */
    protected function applySettingsToFutureDays($record): void
    {
        $currentDate = Carbon::parse($record->date);
        $dayOfWeek = $currentDate->dayOfWeek;
        $timeableId = $record->timeable_id;
        $timeableType = $record->timeable_type;

        // Get all future time intervals for this staff member with matching day of week
        $futureIntervals = TimeInterval::where('timeable_id', $timeableId)
            ->where('timeable_type', $timeableType)
            ->where('date', '>', now()->toDateString())
            ->where('day_of_week', (string) $dayOfWeek)
            ->where('date', '!=', $record->date) // Exclude the current record
            ->get();

        $updatedDates = [];

        // Update each matching time interval
        foreach ($futureIntervals as $interval) {
            $interval->update([
                'start_time' => $record->start_time,
                'end_time' => $record->end_time,
                'is_closed' => $record->is_closed,
                'can_visit_home' => $record->can_visit_home,
            ]);
            $updatedDates[] = $interval->date;
        }

        // Prepare detailed message
        $dayName = getDayName($dayOfWeek);
        $debugInfo = [];

        $debugInfo[] = sprintf("Updating all future %s time intervals", $dayName);

        if (!empty($updatedDates)) {
            $debugInfo[] = sprintf("Updated %d time intervals:", count($updatedDates));
            // Sort dates for better readability
            sort($updatedDates);
            // List up to 5 updated dates
            $showDates = array_slice($updatedDates, 0, 5);
            foreach ($showDates as $date) {
                $debugInfo[] = "- {$date}";
            }
            if (count($updatedDates) > 5) {
                $debugInfo[] = "...and " . (count($updatedDates) - 5) . " more";
            }
        } else {
            $debugInfo[] = "No future time intervals found for {$dayName}.";
            $debugInfo[] = "Make sure you've created future time intervals first.";
        }

        // Show a notification with detailed information
        \Filament\Notifications\Notification::make()
            ->title(__('Time Intervals Updated'))
            ->body(implode("\n", $debugInfo))
            ->success()
            ->send();
    }
}
