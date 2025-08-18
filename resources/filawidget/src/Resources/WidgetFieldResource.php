<?php

namespace Filawidget\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filawidget\Models\Field;
use Filawidget\Resources\WidgetFieldResource\Pages;

class WidgetFieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_fields');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Field');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Fields');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Field');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Field');
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
                        Forms\Components\TextInput::make('name')
                            ->label(__('filawidget::filawidget.Field Name'))
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label(__('filawidget::filawidget.Field Type'))
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'number' => 'Number',
                                'select' => 'Select',
                                'checkbox' => 'Checkbox',
                                'radio' => 'Radio',
                                'toggle' => 'Toggle',
                                'color' => 'Color Picker',
                                'date' => 'Date Picker',
                                'datetime' => 'Date Time Picker',
                                'time' => 'Time Picker',
                                'file' => 'File Upload',
                                'image' => 'Image Upload',
                                'richeditor' => 'Rich Editor',
                                'markdown' => 'Markdown Editor',
                                'tags' => 'Tags Input',
                                'password' => 'Password',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('options')
                            ->label(__('filawidget::filawidget.Options'))
                            ->helperText(__('filawidget::filawidget.Provide additional options in JSON format (e.g., {"default": "value", "validation": "required|max:255"})'))
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
                    ->label(__('filawidget::filawidget.Field Name')),
                TextColumn::make('type')
                    ->badge()
                    ->color('warning')
                    ->label(__('filawidget::filawidget.Field Type')),
                TextColumn::make('widgets_count')
                    ->counts('widgets')
                    ->badge()
                    ->color('success')
                    ->label(__('filawidget::filawidget.Used')),
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
            'index' => Pages\ListWidgetFields::route('/'),
            'create' => Pages\CreateWidgetField::route('/create'),
            'edit' => Pages\EditWidgetField::route('/{record}/edit'),
        ];
    }
}
