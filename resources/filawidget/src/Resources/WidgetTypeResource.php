<?php

namespace Filawidget\Resources;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filawidget\Models\Field as WidgetsField;
use Filawidget\Models\WidgetType;
use Filawidget\Resources\WidgetTypeResource\Pages;

class WidgetTypeResource extends Resource
{
    protected static ?string $model = WidgetType::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_widget_types');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Widget Type');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Widget Types');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Widget Type');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Widget Type');
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
                            ->label(__('filawidget::filawidget.Name'))
                            ->required(),
                        Select::make('fieldsIds')
                            ->label(__('filawidget::filawidget.Fields'))
                            ->options(
                                WidgetsField::pluck('name', 'id')->toArray()
                            )
                            ->multiple()
                            ->reactive()
                            ->required(),
                        Repeater::make('fields')
                            ->schema(function (callable $get) {

                                $fields = WidgetsField::whereIn('id', $get('fieldsIds'))->get(['name', 'type', 'options', 'id'])->toArray();

                                return collect($fields)->map(function ($field) {
                                    $component = match ($field['type']) {
                                        'text' => Forms\Components\TextInput::make("config.{$field['name']}"),
                                        'textarea' => Forms\Components\Textarea::make("config.{$field['name']}"),
                                        'number' => Forms\Components\TextInput::make("config.{$field['name']}")->numeric(),
                                        'select' => Forms\Components\Select::make("config.{$field['name']}")
                                            ->options($field['options'] ?? []),
                                        'checkbox' => Forms\Components\Checkbox::make("config.{$field['name']}"),
                                        'radio' => Forms\Components\Radio::make("config.{$field['name']}")
                                            ->options($field['options'] ?? []),
                                        'toggle' => Forms\Components\Toggle::make("config.{$field['name']}"),
                                        'color' => Forms\Components\ColorPicker::make("config.{$field['name']}"),
                                        'date' => Forms\Components\DatePicker::make("config.{$field['name']}"),
                                        'datetime' => Forms\Components\DateTimePicker::make("config.{$field['name']}"),
                                        'time' => Forms\Components\TimePicker::make("config.{$field['name']}"),
                                        'file' => Forms\Components\FileUpload::make("config.{$field['name']}"),
                                        'image' => Forms\Components\FileUpload::make("config.{$field['name']}")->image(),
                                        'richeditor' => Forms\Components\RichEditor::make("config.{$field['name']}"),
                                        'markdown' => Forms\Components\MarkdownEditor::make("config.{$field['name']}"),
                                        'tags' => Forms\Components\TagsInput::make("config.{$field['name']}"),
                                        'password' => Forms\Components\TextInput::make("config.{$field['name']}")->password(),
                                        default => Forms\Components\TextInput::make("config.{$field['name']}"),
                                    };

                                    if (isset($field['default'])) {
                                        $component->default($field['default']);
                                    }

                                    if (isset($field['validation'])) {
                                        $component->rules($field['validation']);
                                    }

                                    return $component->label(ucfirst(str_replace('_', ' ', $field['name'])));
                                })->toArray();
                            })
                            ->label(__('filawidget::filawidget.Configurations'))
                            ->maxItems(1)
                            ->minItems(1)
                            ->reorderable(false)
                            ->deletable(false)
                            ->required()
                            ->reactive()
                            ->defaultItems(1)
                            ->addActionLabel(__('filawidget::filawidget.Display Fields'))
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
                    ->label(__('filawidget::filawidget.Name')),
                TextColumn::make('widgets_count')
                    ->badge()
                    ->color('warning')
                    ->counts('widgets')
                    ->label(__('filawidget::filawidget.Widgets')),
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
            'index' => Pages\ListWidgets::route('/'),
            'create' => Pages\CreateWidget::route('/create'),
            'edit' => Pages\EditWidget::route('/{record}/edit'),
        ];
    }
}
