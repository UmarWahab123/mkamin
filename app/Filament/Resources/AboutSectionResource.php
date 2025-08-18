<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutSectionResource\Pages;
use App\Models\AboutSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class AboutSectionResource extends Resource
{
    protected static ?string $model = AboutSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Page Settings';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()->columns(3)->schema([

                // Left config card
              Card::make()->schema([
                Select::make('section_name')
                        ->label('Section key')
                        ->options([
                            'hero' => 'Hero Section',
                            'about_content' => 'About Content (Two Column)',
                            'center_text' => 'Center Text Section',
                            'services_preview' => 'Services Preview',
                            'features_accordion' => 'Features with Accordion',
                            'working_hours' => 'Working Hours',
                            'wide_image' => 'Gallery Section',
                            'about5' => 'About5',
                            'banner_promo' => 'Banner Promotion',
                        ])
                        ->required()
                        ->disabled(fn ($record) => (bool) $record)
                        ->searchable()
                        ->live(),
                    TextInput::make('order')
                        ->label('Display Order')
                        ->numeric()
                        ->default(function () {
                            // Auto-assign next order number
                            $maxOrder = AboutSection::max('order') ?? 0;
                            return $maxOrder + 1;
                        })
                        ->live(),
                    Toggle::make('visible')
                        ->label('Show on website')
                        ->default(true)
                        ->live(),
                ])->columnSpan(1),

                // Middle content card with dynamic fields
                Card::make()->schema(self::getContentFields())
                    ->columnSpan(1),

                // Right preview card (simplified approach)
                Card::make()->schema([
                    View::make('filament.about-sections.preview')
                        ->viewData(function ($record, $get, $set, $livewire) {
                            // Simple approach - just pass everything we can get
                            return [
                                'section_name' => $get('section_name'),
                                'content' => $get('content') ?? [],
                                'order' => $get('order'),
                                'visible' => $get('visible'),
                                'livewire' => $livewire, // Pass the whole component
                            ];
                        }),
                ])->columnSpan(1),
            ]),
        ]);
    }

    protected static function getContentFields(): array
    {
        return [
            // Hero Section
            TextInput::make('content.title')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'hero'),
            Textarea::make('content.description')->label('Description')->rows(4)
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'hero'),
            FileUpload::make('content.background_image')->label('Background Image')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'hero'),

            // About Content (two columns)
            TextInput::make('content.left_section_id')->label('Left small title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),
            TextInput::make('content.left_title')->label('Left title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),
            Textarea::make('content.left_body')->label('Left body')->rows(4)
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),
            FileUpload::make('content.left_image')->label('Left image')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),

            Textarea::make('content.right_body')->label('Right body')->rows(4)
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),
            FileUpload::make('content.right_image')->label('Right image')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about_content'),

            // Center text section
            TextInput::make('content.section_id')->label('Small title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'center_text'),
            TextInput::make('content.title_center')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'center_text'),
            Textarea::make('content.body_center')->label('Body')->rows(4)
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'center_text'),

            // Services preview
            Repeater::make('content.services')->label('Services items')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'services_preview')
                ->schema([
                    TextInput::make('name')->label('Service name')->required()->live(),
                    TextInput::make('icon_class')->label('Icon class')->required()->live(),
                ]),

            // Features accordion
            TextInput::make('content.features_section_id')->label('Small title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'features_accordion'),
            TextInput::make('content.features_title')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'features_accordion'),
            FileUpload::make('content.features_image')->label('Right image')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'features_accordion'),
            Repeater::make('content.features_accordion')->label('Accordion')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'features_accordion')
                ->schema([
                    TextInput::make('title')->required()->live(),
                    Textarea::make('content')->rows(3)->required()->live(),
                ]),

            // Working hours
            Repeater::make('content.working_hours')->label('Working hours')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours')
                ->schema([
                    TextInput::make('day')->required()->live(),
                    TextInput::make('time')->required()->live(),
                ]),
            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            Textarea::make('content.description')->label('Description')->rows(3)
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.dayNameColor')->label('Day name color')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.timeColor')->label('Time color')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.smallTitleColor')->label('Small title color')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.titleColor')->label('Title color')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),
            TextInput::make('content.descriptionColor')->label('Description color')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'working_hours'),

            // Wide image
            FileUpload::make('content.image')->label('Wide image')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'wide_image'),

            // About5 images
            FileUpload::make('content.image_1')->label('Image 1')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about5'),
            FileUpload::make('content.image_2')->label('Image 2')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about5'),
            FileUpload::make('content.image_3')->label('Image 3')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about5'),
            TextInput::make('content.small_title')->label('Small title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about5'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'about5'),

            // Banner promo
            TextInput::make('content.small_title')->label('Small title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
            TextInput::make('content.subtitle')->label('Subtitle')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
            FileUpload::make('content.background')->label('Background')
                ->image()
                ->disk('public')
                ->directory('about')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
            TextInput::make('content.button_text')->label('Button text')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
            TextInput::make('content.button_link')->label('Button link')
                ->live()
                ->visible(fn ($get) => $get('section_name') === 'banner_promo'),
        ];
    }

   public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Add drag handle column
                TextColumn::make('drag_handle')
                    ->label('')
                    ->state(fn () => '⋮⋮')
                    ->extraAttributes(['class' => 'cursor-move'])
                    ->sortable(false)
                    ->searchable(false)
                    ->width('30px'),

                TextColumn::make('section_name')
                    ->label('Section')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'warning',
                        'about_content' => 'info',
                        'center_text' => 'success',
                        'services_preview' => 'primary',
                        'features_accordion' => 'secondary',
                        'working_hours' => 'warning',
                        'wide_image' => 'gray',
                        'about5' => 'info',
                        'banner_promo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hero' => 'Hero Section',
                        'about_content' => 'About Content (Two Column)',
                        'center_text' => 'Center Text Section',
                        'services_preview' => 'Services Preview',
                        'features_accordion' => 'Features with Accordion',
                        'working_hours' => 'Working Hours',
                        'wide_image' => 'Gallery Section',
                        'about5' => 'About5',
                        'banner_promo' => 'Banner Promotion',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->searchable()
                    ->sortable(),
                
                // TextColumn::make('content->title')
                //     ->label('Title')
                //     ->limit(50)
                //     ->placeholder('No title set')
                //     ->searchable()
                //     ->tooltip(fn ($record) => $record->content['title'] ?? 'No title set'),
                
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\ToggleColumn::make('visible')
                    ->label('Visible')
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter(),
                
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order') // This enables drag & drop reordering
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Section')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Add bulk reorder action
                    Tables\Actions\BulkAction::make('reorder')
                        ->label('Reorder Selected')
                        ->icon('heroicon-m-arrows-up-down')
                        ->color('info')
                        ->action(function ($records) {
                            // Reset order starting from 1
                            $records->each(function ($record, $index) {
                                $record->update(['order' => $index + 1]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Reorder Sections')
                        ->modalDescription('This will reorder the selected sections starting from order 1.')
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add New Section')
                    ->color('warning')
                    ->icon('heroicon-o-plus'),
                
                // COMMENTED OUT: Reset All Orders button
                // Tables\Actions\Action::make('reset_order')
                //     ->label('Reset All Orders')
                //     ->icon('heroicon-m-arrow-path')
                //     ->color('gray')
                //     ->action(function () {
                //         $sections = AboutSection::orderBy('order')->get();
                //         $sections->each(function ($section, $index) {
                //             $section->update(['order' => $index + 1]);
                //         });
                //     })
                //     ->requiresConfirmation()
                //     ->modalHeading('Reset Section Orders')
                //     ->modalDescription('This will reset all section orders starting from 1 based on current order.')
            ])
            ->emptyStateHeading('No sections created yet')
            ->emptyStateDescription('Create your first about page section to get started.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add New Section')
                    ->color('warning')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutSections::route('/'),
            'create' => Pages\CreateAboutSection::route('/create'),
            'edit' => Pages\EditAboutSection::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}