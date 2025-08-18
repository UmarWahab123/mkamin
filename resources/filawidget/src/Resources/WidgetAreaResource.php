<?php

namespace Filawidget\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filawidget\Models\WidgetArea;
use Filawidget\Resources\WidgetAreaResource\Pages;
use Illuminate\Database\Eloquent\Builder;

class WidgetAreaResource extends Resource
{
    protected static ?string $model = WidgetArea::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_widget_areas');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Widget Area');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Widget Areas');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Widget Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Widget Area');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filawidget::filawidget.Appearance Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filawidget::filawidget.Area Name'))
                            ->required(),
                        TextInput::make('identifier')
                            ->label(__('filawidget::filawidget.Identifier'))
                            ->unique(ignoreRecord: true)
                            ->helperText(__('filawidget::filawidget.This identifier is used to reference the widget area in your code.'))
                            ->required(),
                        RichEditor::make('description')
                            ->label(__('filawidget::filawidget.Description'))
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label(__('filawidget::filawidget.Status'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->color('success')
                    ->label(__('filawidget::filawidget.Area Name')),
                TextColumn::make('identifier')
                    ->badge()
                    ->color('primary')
                    ->label(__('filawidget::filawidget.Identifier')),
                ToggleColumn::make('status')
                    ->label(__('filawidget::filawidget.Status')),
                TextColumn::make('created_at')
                    ->dateTime('d, M Y h:s A')
                    ->badge()
                    ->color('success')
                    ->label(__('filawidget::filawidget.Created at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->ordered());
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
            'index' => Pages\ListWidgetAreas::route('/'),
            'create' => Pages\CreateWidgetArea::route('/create'),
            'edit' => Pages\EditWidgetArea::route('/{record}/edit'),
        ];
    }
}
