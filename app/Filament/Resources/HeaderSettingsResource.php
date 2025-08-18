<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeaderSettingsResource\Pages;
use App\Filament\Resources\HeaderSettingsResource\RelationManagers;
use App\Models\HeaderSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HeaderSettingsResource extends Resource
{
    protected static ?string $model = HeaderSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $navigationGroup = 'Page Settings';
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Header Preview')
                    ->schema([
                        Forms\Components\View::make('partials.header-preview-livewire')
                            ->viewData(fn($record) => ['record' => $record])
                    ])->columnSpan(2),
                Forms\Components\Tabs::make('Header Configuration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Header Logo')
                            ->schema([
                                Forms\Components\FileUpload::make('mobile_logo')
                                    ->image()
                                    ->imageEditor(),
                                Forms\Components\FileUpload::make('desktop_logo')
                                    ->directory('uploads')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                            ]),
                        Forms\Components\Tabs\Tab::make('Color and Language Switcher')
                            ->schema([
                                Forms\Components\ColorPicker::make('header_color')
                                    ->default('#000000')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\ColorPicker::make('header_text_color')
                                    ->default('#ffffff')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\ColorPicker::make('header_text_hover_color')
                                    ->default('#ffffff')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\ColorPicker::make('header_text_dropdown_color')
                                    ->default('#ffffff')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\ColorPicker::make('header_text_dropdown_hover_color')
                                    ->default('#ffffff')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('is_show_language_switcher')
                                    ->label('Show Language Switcher')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Navigation Links')
                            ->schema([
                                Forms\Components\Repeater::make('navigation_links')
                                    ->label('')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(fn($state, $component) => static::dispatchPreviewUpdate($component))
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('url')
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(fn($state, $component) => static::dispatchPreviewUpdate($component))
                                            ->columnSpan(2),

                                        Forms\Components\Toggle::make('active')
                                            ->default(true)
                                            ->live(debounce: 300)
                                            ->afterStateUpdated(fn($state, $component) => static::dispatchPreviewUpdate($component))
                                            ->columnSpan(1),

                                        Forms\Components\Toggle::make('dropdown')
                                            ->label('Has Dropdown')
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(fn($state, $component) => static::dispatchPreviewUpdate($component))
                                            ->columnSpan(1),

                                        // Nested repeater for dropdown items
                                        Forms\Components\Repeater::make('dropdown_links')
                                            ->label('Dropdown Links')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->required()
                                                    ->columnSpan(1),

                                                Forms\Components\TextInput::make('url')
                                                    ->required()
                                                    ->columnSpan(1),

                                                Forms\Components\Toggle::make('active')
                                                    ->default(true)
                                                    ->columnSpan(1),
                                            ])
                                            ->columnSpanFull()
                                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                                            ->collapsible()
                                            ->orderable()
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Dropdown Link')
                                            ->visible(fn(callable $get) => $get('dropdown') === true),
                                    ])
                                    ->columns(6) // Adjusted columns to fit the extra toggle
                                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                                    ->collapsible()
                                    ->orderable()
                                    ->defaultItems(0)
                                    ->live()
                                    ->afterStateUpdated(fn($state, $component) => static::dispatchPreviewUpdate($component))
                                    ->addActionLabel('Add Navigation Link')
                                    ->deleteAction(
                                        fn($action) => $action->after(fn($component) => static::dispatchPreviewUpdate($component))
                                    )

                            ]),


                    ])
                    ->columnSpan(2)
                    ->extraAttributes(['x-data' => '{}'])
                    ->extraAttributes([
                        'x-init' => 'setTimeout(() => { 
                            $dispatch("formDataChanged", {
                                mobile_logo: $wire.data.mobile_logo || "",
                                desktop_logo: $wire.data.desktop_logo || "",
                                header_color: $wire.data.header_color || "",
                                is_show_language_switcher: $wire.data.is_show_language_switcher || true,
                                navigation_links: $wire.data.navigation_links || [],
                            })
                        }, 500)'
                    ]),
            ])->columns(1);
    }

    protected static function dispatchPreviewUpdate($component)
    {
        $livewire = $component->getLivewire();
        $formData = $livewire->data;

        $livewire->dispatch('updatePreview', [
            'mobile_logo' => $formData['mobile_logo'] ?? '',
            'desktop_logo' => $formData['desktop_logo'] ?? '',
            'header_color' => $formData['header_color'] ?? '',
            'is_show_language_switcher' => $formData['is_show_language_switcher'] ?? true,
            'navigation_links' => $formData['navigation_links'] ?? [],
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListHeaderSettings::route('/'),
            'create' => Pages\CreateHeaderSettings::route('/create'),
            'edit' => Pages\EditHeaderSettings::route('/{record}/edit'),
        ];
    }
}
