<?php

namespace Filawidget\Resources;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filawidget\Models\Field as WidgetsField;
use Filawidget\Models\Widget;
use Filawidget\Models\WidgetArea;
use Filawidget\Models\WidgetField;
use Filawidget\Models\WidgetType;
use Filawidget\Resources\WidgetResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_widgets');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Widget');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Widgets');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Widget');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Widget');
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
                            ->required()
                            ->columnSpanFull(),
                        Select::make('page')
                            ->label(__('filawidget::filawidget.Page'))
                            ->options([
                                'home' => 'Home',
                                'about' => 'About',
                                'contact' => 'Contact',
                                'services' => 'Services',
                                'service' => 'Service',
                                'faq' => 'FAQ',
                                'header_footer' => 'Header & Footer',
                            ])
                            ->required()
                            ->default('home'),
                        Select::make('widget_area_id')
                            ->label(__('filawidget::filawidget.Area'))
                            ->options(
                                WidgetArea::pluck('name', 'id')->toArray()
                            )
                            ->required()
                            ->searchable()
                            ->default(
                                request()->has('area_id') ? request()->query('area_id') : null
                            ),
                        Select::make('widget_type_id')
                            ->label(__('filawidget::filawidget.Widget Type'))
                            ->searchable()
                            ->options(
                                WidgetType::pluck('name', 'id')->toArray()
                            )
                            ->afterStateUpdated(function (callable $set, $state) {
                                $widgetType = WidgetType::find($state);
                                if ($widgetType) {
                                    $set('fieldsIds', $widgetType->fieldsIds);

                                    // Force refresh of the repeater to show fields immediately
                                    $set('values', ['temp' => []]);
                                }
                            })
                            ->reactive()
                            ->required(),
                        RichEditor::make('description')
                            ->label(__('filawidget::filawidget.Description'))
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label(__('filawidget::filawidget.Status')),
                        Hidden::make('fieldsIds')
                            ->reactive()
                            ->dehydrated(false),
                        Repeater::make('values')
                            ->label(__('filawidget::filawidget.Appearance'))
                            ->schema(function (callable $get) {
                                $fieldsIds = $get('fieldsIds') ?? [];
                                $widgetId = $get('id') ?? null;

                                $fields = [];
                                if (is_array($fieldsIds) && count($fieldsIds) > 0) {
                                    $fields = WidgetsField::whereIn('id', $fieldsIds)
                                        ->get(['fields.name', 'fields.type', 'fields.options', 'fields.id'])
                                        ->toArray();
                                }


                                return collect($fields)->map(function ($field) use ($get) {
                                    $options = json_decode($field['options'], true);
                                    $defaultValue = $options['default'] ?? '';

                                    $fieldKey = 'field_' . $field['id'];

                                    $component = match ($field['type']) {
                                        'text' => TextInput::make($fieldKey),
                                        'textarea' => Textarea::make($fieldKey),
                                        'number' => TextInput::make($fieldKey)->numeric(),
                                        'select' => Select::make($fieldKey)
                                            ->options($field['options'] ?? []),
                                        'checkbox' => Checkbox::make($fieldKey),
                                        'radio' => Radio::make($fieldKey)
                                            ->options($field['options'] ?? []),
                                        'toggle' => Toggle::make($fieldKey),
                                        'color' => ColorPicker::make($fieldKey),
                                        'date' => DatePicker::make($fieldKey),
                                        'datetime' => DateTimePicker::make($fieldKey),
                                        'time' => TimePicker::make($fieldKey),
                                        'file' => FileUpload::make($fieldKey),
                                        'image' => FileUpload::make($fieldKey)->image(),
                                        'richeditor' => RichEditor::make($fieldKey),
                                        'markdown' => MarkdownEditor::make($fieldKey),
                                        'tags' => TagsInput::make($fieldKey),
                                        'password' => TextInput::make($fieldKey)->password(),
                                        default => TextInput::make($fieldKey),
                                    };

                                    // Set a default value - we don't need to check for values here
                                    // as they're already provided in the repeater item from EditWidget
                                    $component->default($defaultValue);

                                    if (isset($field['validation'])) {
                                        $component->rules($field['validation']);
                                    }

                                    $component = $component->label(ucfirst(str_replace('_', ' ', $field['name'])))
                                        ->extraAttributes(['data-field-id' => $field['id']]);

                                    return $component;
                                })->toArray();
                            })
                            ->label(__('filawidget::filawidget.Configurations'))
                            ->reorderable(false)
                            ->deletable(false)
                            ->addable(false)
                            ->reactive()
                            ->defaultItems(1)
                            ->collapsed(false)
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
                    ->sortable()
                    ->label(__('filawidget::filawidget.Widget')),
                TextColumn::make('type.name')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->label(__('filawidget::filawidget.Widget Type')),
                TextColumn::make('page')
                    ->badge()
                    ->color('warning')
                    ->label(__('filawidget::filawidget.Page')),
                SelectColumn::make('widget_area_id')
                    ->options(WidgetArea::pluck('name', 'id')->toArray())
                    ->label(__('filawidget::filawidget.Widget Area')),
                ToggleColumn::make('status')
                    ->label(__('filawidget::filawidget.Status')),
                TextColumn::make('created_at')
                    ->dateTime('d, M Y h:s A')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->label(__('filawidget::filawidget.Created at')),
            ])
            ->filters([
                SelectFilter::make('widget_area_id')
                    ->label(__('filawidget::filawidget.Widget Area'))
                    ->options(WidgetArea::pluck('name', 'id')->toArray()),
                SelectFilter::make('page')
                    ->label(__('filawidget::filawidget.Page'))
                    ->options([
                        'home' => 'Home',
                        'about' => 'About',
                        'contact' => 'Contact',
                        'services' => 'Services',
                        'service' => 'Service',
                        'faq' => 'FAQ',
                        'header_footer' => 'Header & Footer',
                    ]),
                Filter::make('created_at')
                    ->label(__('filawidget::filawidget.Created at'))
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListWidgets::route('/'),
            'create' => Pages\CreateWidget::route('/create'),
            'edit' => Pages\EditWidget::route('/{record}/edit'),
        ];
    }
}
