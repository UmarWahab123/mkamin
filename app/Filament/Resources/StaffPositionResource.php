<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffPositionResource\Pages;
use App\Models\StaffPosition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StaffPositionResource extends Resource
{
    protected static ?string $model = StaffPosition::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?int $navigationSort = 12;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    // Translation methods
    public static function getModelLabel(): string
    {
        return __('Staff Position');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Staff Positions');
    }

    public static function getNavigationLabel(): string
    {
        return __('Staff Positions');
    }

    public static function getNavigationGroup(): string
    {
        return __('Staff');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Position Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Position Name (English)'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Position Name (Arabic)'))
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Position Name (English)'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('Position Name (Arabic)'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('staff_count')
                    ->label(__('Staff Count'))
                    ->counts('staff')
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaffPositions::route('/'),
            'create' => Pages\CreateStaffPosition::route('/create'),
            'edit' => Pages\EditStaffPosition::route('/{record}/edit'),
        ];
    }
}
