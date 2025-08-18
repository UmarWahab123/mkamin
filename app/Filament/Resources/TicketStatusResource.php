<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketStatusResource\Pages;
use App\Filament\Resources\TicketStatusResource\RelationManagers;
use App\Models\TicketStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketStatusResource extends Resource
{
    protected static ?string $model = TicketStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isPOS = $user->pointOfSale !== null;

        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    ->label(__('Name (English)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_ar')
                    ->label(__('Name (Arabic)'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('Color'))
                    ->required(),
                Forms\Components\Select::make('point_of_sale_id')
                    ->label(__('Point of Sale'))
                    ->relationship('pointOfSale', 'name_en', function (Builder $query) use ($user) {
                        if ($user->company_id) {
                            $query->where('company_id', $user->company_id);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->searchable()
                    ->preload()
                    ->disabled($isPOS)
                    ->dehydrated()
                    ->default($isPOS ? $user->pointOfSale->id : null)
                    ->required(),
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
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('Color')),
                Tables\Columns\TextColumn::make('pointOfSale.name_en')
                    ->label(__('Point of Sale'))
                    ->searchable(),
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
            'index' => Pages\ListTicketStatuses::route('/'),
            'create' => Pages\CreateTicketStatus::route('/create'),
            'edit' => Pages\EditTicketStatus::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Ticket Status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Ticket Statuses');
    }

    public static function getNavigationLabel(): string
    {
        return __('Ticket Statuses');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-tag';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Tickets');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
