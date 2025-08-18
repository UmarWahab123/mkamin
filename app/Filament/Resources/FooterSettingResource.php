<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterSettingResource\Pages;
use App\Filament\Resources\FooterSettingResource\RelationManagers;
use App\Models\FooterSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FooterSettingResource extends Resource
{
    protected static ?string $model = FooterSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $navigationGroup = 'Page Settings';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Footer Configuration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Social Links')
                            ->schema([
                                Forms\Components\Repeater::make('social_links')
                                    ->label('')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->options(collect(FooterSetting::socialPlatforms())->mapWithKeys(
                                                fn($item) => [$item['name'] => $item['name']]
                                            ))
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('url')
                                            ->url()
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\Toggle::make('active')
                                            ->default(true)
                                            ->live(debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4)
                                    ->itemLabel(
                                        fn(array $state): ?string =>
                                        $state['platform'] ?? null
                                    )
                                    ->collapsible()
                                    ->orderable()
                                    ->defaultItems(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $component) {
                                        static::dispatchPreviewUpdate($component);
                                    })
                                    ->addActionLabel('Add Social Link')
                                    ->deleteAction(
                                        fn($action) => $action->after(function ($component) {
                                            static::dispatchPreviewUpdate($component);
                                        })
                                    ),
                            ]),
                        Forms\Components\Tabs\Tab::make('Navigation Links')
                            ->schema([
                                Forms\Components\Repeater::make('navigation_links')
                                    ->label('')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('url')
                                            ->required()
                                            ->live(onBlur: true, debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\Toggle::make('active')
                                            ->default(true)
                                            ->live(debounce: 300)
                                            ->afterStateUpdated(function ($state, $component) {
                                                static::dispatchPreviewUpdate($component);
                                            })
                                            ->columnSpan(1),
                                    ])
                                    ->columns(5)
                                    ->itemLabel(
                                        fn(array $state): ?string =>
                                        $state['label'] ?? null
                                    )
                                    ->collapsible()
                                    ->orderable()
                                    ->defaultItems(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $component) {
                                        static::dispatchPreviewUpdate($component);
                                    })
                                    ->addActionLabel('Add Navigation Link')
                                    ->deleteAction(
                                        fn($action) => $action->after(function ($component) {
                                            static::dispatchPreviewUpdate($component);
                                        })
                                    ),
                            ]),

                        Forms\Components\Tabs\Tab::make('Copyright')
                            ->schema([
                                Forms\Components\TextInput::make('copyright_text')
                                    ->required()
                                    ->live(onBlur: true, debounce: 300)
                                    ->afterStateUpdated(function ($state, $component) {
                                        static::dispatchPreviewUpdate($component);
                                    }),
                                Forms\Components\TextInput::make('designer_text')
                                    ->required()
                                    ->live(onBlur: true, debounce: 300)
                                    ->afterStateUpdated(function ($state, $component) {
                                        static::dispatchPreviewUpdate($component);
                                    }),
                                Forms\Components\TextInput::make('designer_url')
                                    ->url()
                                    ->required()
                                    ->live(onBlur: true, debounce: 300)
                                    ->afterStateUpdated(function ($state, $component) {
                                        static::dispatchPreviewUpdate($component);
                                    }),
                            ]),

                    ])
                    ->columnSpan(2)
                    ->extraAttributes(['x-data' => '{}'])
                    ->extraAttributes([
                        'x-init' => 'setTimeout(() => { 
                            $dispatch("formDataChanged", {
                                social_links: $wire.data.social_links || [],
                                navigation_links: $wire.data.navigation_links || [],
                                copyright_text: $wire.data.copyright_text || "",
                                designer_text: $wire.data.designer_text || "",
                                designer_url: $wire.data.designer_url || ""
                            })
                        }, 500)'
                    ]),

                Forms\Components\Section::make('Footer Preview')
                    ->schema([
                        Forms\Components\View::make('partials.footer-preview-livewire')
                            ->viewData(fn($record) => ['record' => $record])
                    ])->columnSpan(1),
            ])->columns(3);
    }

    protected static function dispatchPreviewUpdate($component)
    {
        $livewire = $component->getLivewire();
        $formData = $livewire->data;

        $livewire->dispatch('updatePreview', [
            'social_links' => $formData['social_links'] ?? [],
            'navigation_links' => $formData['navigation_links'] ?? [],
            'copyright_text' => $formData['copyright_text'] ?? '',
            'designer_text' => $formData['designer_text'] ?? '',
            'designer_url' => $formData['designer_url'] ?? '',
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
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFooterSettings::route('/'),
            'create' => Pages\CreateFooterSetting::route('/create'),
            'view' => Pages\ViewFooterSetting::route('/{record}'),
            'edit' => Pages\EditFooterSetting::route('/{record}/edit'),
        ];
    }
}
