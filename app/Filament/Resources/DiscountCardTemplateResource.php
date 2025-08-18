<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountCardTemplateResource\Pages;
use App\Filament\Resources\DiscountCardTemplateResource\RelationManagers;
use App\Models\DiscountCardTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DiscountCardTemplateResource extends Resource
{
    protected static ?string $model = DiscountCardTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Discount Card Template');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Discount Card Templates');
    }

    public static function getNavigationLabel(): string
    {
        return __('Discount Card Templates');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('Discounts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->columnSpanFull()
                    ->required()
                    ->directory('discount-cards')
                    ->visibility('public')
                    ->disk('public')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    // ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                    ->maxSize(5120)
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '141:200',
                    ])
                    ->imageCropAspectRatio('141:200')
                    ->imageEditorMode(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->height(100),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscountCardTemplates::route('/'),
            'create' => Pages\CreateDiscountCardTemplate::route('/create'),
            'edit' => Pages\EditDiscountCardTemplate::route('/{record}/edit'),
        ];
    }
}
