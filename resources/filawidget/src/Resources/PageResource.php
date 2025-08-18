<?php

namespace Filawidget\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filawidget\Models\Page;
use Filawidget\Resources\PageResource\Pages;
use Illuminate\Database\Eloquent\Builder;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_pages');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Page');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Pages');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Page');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Page');
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
                        TextInput::make('title')
                            ->label(__('filawidget::filawidget.Title'))
                            ->required()
                            ->maxLength(255),
                        Select::make('parent_id')
                            ->label(__('filawidget::filawidget.Root'))
                            ->options(
                                Page::pluck('title', 'id')
                                    ->toArray()
                            )
                            ->default(
                                request()->has('page_id') ? request()->query('page_id') : null
                            )
                            ->searchable(),
                        RichEditor::make('content')
                            ->label(__('filawidget::filawidget.Content'))
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label(__('filawidget::filawidget.Status'))
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filawidget::filawidget.Title'))
                    ->badge()
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.title')
                    ->label(__('filawidget::filawidget.Root'))
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->default('-'),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->latest());
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
