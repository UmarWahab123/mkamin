<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeSectionResource\Pages;
use App\Models\HomeSection;
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
use Filament\Forms\Components\ColorPicker;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class HomeSectionResource extends Resource
{
    protected static ?string $model = HomeSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Page Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->live() // Essential: Make entire form reactive
            ->schema([
                Grid::make()->columns(3)->schema([

                    // Left config card
                    Card::make()->schema([
                        Select::make('section_name')
                            ->label('Section Type')
                            ->options([
                                'hero_section' => 'Hero Section (Slider)',
                                'trending_services' => 'Trending Services',
                                'text_content_1' => 'Text Content 1 (Image + Text)',
                                'text_content_2' => 'Text Content 2 (3 Images + Card)',
                                'services_section' => 'Services Section (4 Column)',
                                'text_content_3' => 'Text Content 3 (Dark Theme + Button)',
                                'pricing_section' => 'Pricing Section',
                                'wide_image_section' => 'Wide Image Section',
                                'text_content_4' => 'Text Content 4 (Side by Side)',
                                'working_hours_section' => 'Working Hours Section',
                                'contact_section' => 'Contact Section (Map + Info)',
                            ])
                            ->required()
                            ->disabled(fn ($record) => (bool) $record)
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('preview_trigger', microtime(true));
                            }),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(function () {
                                $maxOrder = HomeSection::max('order') ?? 0;
                                return $maxOrder + 1;
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('preview_trigger', microtime(true));
                            }),
                        Toggle::make('visible')
                            ->label('Show on website')
                            ->default(true)
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('preview_trigger', microtime(true));
                            }),
                    ])->columnSpan(1),

                    // Middle content card with dynamic fields
                    Card::make()->schema(self::getContentFields())
                        ->columnSpan(1),

                    // Right preview card - Enhanced with complete live preview
                    Card::make()->schema([
                        View::make('filament.home-sections.preview')
                            ->viewData(function ($record, $get, $set, $livewire) {
                                $content = $get('content') ?? [];
                                $sectionName = $get('section_name');
                                $visible = $get('visible') ?? true;
                                $previewTrigger = $get('preview_trigger');
                                
                                // Enhanced data preparation
                                $processedContent = $content;
                                
                                // Special handling for hero section slides
                                if ($sectionName === 'hero_section' && isset($content['slides'])) {
                                    $slides = [];
                                    foreach ($content['slides'] as $key => $slide) {
                                        if (is_array($slide)) {
                                            $slides[] = $slide;
                                        }
                                    }
                                    $processedContent['slides'] = $slides;
                                }
                                                        
                                return [
                                    'section_name' => $sectionName,
                                    'content' => $processedContent,
                                    'order' => $get('order'),
                                    'visible' => $visible,
                                    'preview_trigger' => $previewTrigger,
                                    'livewire' => $livewire,
                                    'getBestImageUrl' => function($key) use ($processedContent) {
                                        if (empty($processedContent[$key])) {
                                            return null;
                                        }
                                        
                                        $imagePath = $processedContent[$key];
                                        
                                        if (is_array($imagePath)) {
                                            foreach ($imagePath as $uuid => $savedPath) {
                                                if (is_string($savedPath) && str_starts_with($savedPath, 'home/')) {
                                                    return asset('storage/' . $savedPath);
                                                }
                                                if (empty($savedPath) && preg_match('/^[a-f0-9-]{36}$/', $uuid)) {
                                                    return url('/livewire/preview-file/' . $uuid);
                                                }
                                            }
                                        }
                                        
                                        if (is_string($imagePath)) {
                                            if (str_starts_with($imagePath, 'home/')) {
                                                return asset('storage/' . $imagePath);
                                            }
                                            if (preg_match('/^[a-f0-9-]{36}$/', $imagePath)) {
                                                return url('/livewire/preview-file/' . $imagePath);
                                            }
                                        }
                                        
                                        return null;
                                    },
                                    'getNestedImageUrl' => function($keyPath) use ($processedContent) {
                                        $keys = explode('.', $keyPath);
                                        $value = $processedContent;
                                        
                                        foreach ($keys as $key) {
                                            if (!isset($value[$key])) {
                                                return null;
                                            }
                                            $value = $value[$key];
                                        }
                                        
                                        if (is_array($value)) {
                                            foreach ($value as $uuid => $savedPath) {
                                                if (is_string($savedPath) && str_starts_with($savedPath, 'home/')) {
                                                    return asset('storage/' . $savedPath);
                                                }
                                                if (empty($savedPath) && preg_match('/^[a-f0-9-]{36}$/', $uuid)) {
                                                    return url('/livewire/preview-file/' . $uuid);
                                                }
                                            }
                                        }
                                        
                                        return null;
                                    }
                                ];
                            })
                            ->key(function ($get) {
                                $content = $get('content') ?? [];
                                $sectionName = $get('section_name');
                                $trigger = $get('preview_trigger') ?? time();
                                
                                // Create a more specific key for hero sections
                                if ($sectionName === 'hero_section' && isset($content['slides'])) {
                                    $slidesHash = md5(serialize($content['slides']));
                                    return 'preview-hero-' . $slidesHash . '-' . $trigger;
                                }
                                
                                return 'preview-' . $sectionName . '-' . md5(serialize($content)) . '-' . $trigger;
                            })
                    ])->columnSpan(1),
                ]),
                
                // Hidden trigger field for preview updates
                Forms\Components\Hidden::make('preview_trigger')
                    ->default(microtime(true))
                    ->live(),
            ]);
    }

    protected static function getContentFields(): array
    {
        return [
            // Hero Section (Slider) - COMPLETELY FIXED FOR LIVE PREVIEW
            Repeater::make('content.slides')->label('Hero Slides')
                ->live()
                ->afterStateUpdated(function ($state, $set, $get) {
                    // Force immediate preview refresh
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'hero_section')
                ->schema([
                    TextInput::make('smallTitle')->label('Small Title')
                        ->live(debounce: 300) // Reduced debounce for faster updates
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    TextInput::make('title')->label('Main Title')->required()
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    ColorPicker::make('smallTitleColor')->label('Small Title Color')->default('#af8855')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    ColorPicker::make('titleColor')->label('Title Color')->default('#ffffff')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    FileUpload::make('bgImage')->label('Background Image')
                        ->image()->disk('public')->directory('home')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    TextInput::make('buttonText')->label('Button Text')
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    TextInput::make('buttonUrl')->label('Button URL')
                        ->live(debounce: 300)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    ColorPicker::make('buttonBgColor')->label('Button Background')->default('#af8855')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                    ColorPicker::make('buttonTextColor')->label('Button Text Color')->default('#ffffff')
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $set('preview_trigger', microtime(true));
                        }),
                ])
                ->defaultItems(1) // Always start with one slide
                ->addActionLabel('Add Slide')
                ->reorderable(true)
                ->collapsible()
                ->collapsed(false) // Keep expanded by default
                ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['smallTitle'] ?? 'New Slide')
                ->deleteAction(
                    fn (Forms\Components\Actions\Action $action) => $action
                        ->after(function ($set) {
                            $set('preview_trigger', microtime(true));
                        })
                )
                ->addAction(
                    fn (Forms\Components\Actions\Action $action) => $action
                        ->after(function ($set) {
                            $set('preview_trigger', microtime(true));
                        })
                ),

            // Trending Services Section
            TextInput::make('content.section_id')->label('Small Title')
                ->live()->default('Best Selling Services')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'trending_services'),
            TextInput::make('content.title')->label('Main Title')
                ->live()->default('Trending Services')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'trending_services'),
            ColorPicker::make('content.small_title_color')->label('Small Title Color')
                ->live()->default('#af8855')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'trending_services'),
            ColorPicker::make('content.title_color')->label('Title Color')
                ->live()->default('#363636')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'trending_services'),

            // Text Content 1 (Simple Image + Text)
            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            Textarea::make('content.description')->label('Description')->rows(4)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            FileUpload::make('content.image')->label('Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            ColorPicker::make('content.smallTitleColor')->label('Small Title Color')->default('#af8855')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            ColorPicker::make('content.titleColor')->label('Title Color')->default('#363636')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),
            ColorPicker::make('content.descriptionColor')->label('Description Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_1'),

            // Text Content 2 (3 Images + Card)
            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            FileUpload::make('content.image1')->label('Left Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            FileUpload::make('content.image2')->label('Right Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            FileUpload::make('content.image3')->label('Bottom Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            TextInput::make('content.cardTitle')->label('Card Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            Textarea::make('content.cardDescription')->label('Card Description')->rows(3)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            ColorPicker::make('content.smallTitleColor')->label('Small Title Color')->default('#af8855')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            ColorPicker::make('content.titleColor')->label('Title Color')->default('#363636')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            ColorPicker::make('content.cardBackgroundColor')->label('Card Background')->default('#ffffff')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            ColorPicker::make('content.cardTitleColor')->label('Card Title Color')->default('#363636')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),
            ColorPicker::make('content.cardDescriptionColor')->label('Card Description Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_2'),

            // Services Section
            Repeater::make('content.services')->label('Services')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'services_section')
                ->schema([
                    TextInput::make('title')->label('Service Title')->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        }),
                    Textarea::make('description')->label('Description')->rows(2)
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        }),
                    TextInput::make('icon')->label('Icon Class')
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        }),
                    FileUpload::make('image')->label('Service Image')
                        ->image()->disk('public')->directory('home')
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        }),
                ]),
            ColorPicker::make('content.titleColor')->label('Service Title Color')->default('#363636')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'services_section'),
            ColorPicker::make('content.descriptionColor')->label('Description Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'services_section'),

            // Text Content 3 (Dark Theme + Button)
            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            Textarea::make('content.description')->label('Description')->rows(4)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            TextInput::make('content.buttonText')->label('Button Text')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            TextInput::make('content.buttonUrl')->label('Button URL')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            ColorPicker::make('content.smallTitleColor')->label('Small Title Color')->default('#af8855')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            ColorPicker::make('content.titleColor')->label('Title Color')->default('#ffffff')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            ColorPicker::make('content.descriptionColor')->label('Description Color')->default('#ffffff')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            ColorPicker::make('content.buttonBgColor')->label('Button Background')->default('#af8855')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),
            ColorPicker::make('content.buttonTextColor')->label('Button Text Color')->default('#ffffff')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_3'),

            // Wide Image Section
            FileUpload::make('content.image')->label('Wide Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'wide_image_section'),

            // Text Content 4 (Side by Side)
            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            TextInput::make('content.title')->label('Title')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            Textarea::make('content.description1')->label('First Paragraph')->rows(3)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            Textarea::make('content.description2')->label('Second Paragraph')->rows(3)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            FileUpload::make('content.image')->label('Side Image')
                ->image()->disk('public')->directory('home')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            ColorPicker::make('content.backgroundColor')->label('Background Color')->default('#ffffff')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            ColorPicker::make('content.smallTitleColor')->label('Small Title Color')->default('#af8855')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            ColorPicker::make('content.titleColor')->label('Title Color')->default('#363636')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            ColorPicker::make('content.description1Color')->label('First Paragraph Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),
            ColorPicker::make('content.description2Color')->label('Second Paragraph Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'text_content_4'),

            // Working Hours Section
            Repeater::make('content.workingHours')->label('Working Hours Schedule')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section')
                ->schema([
                    TextInput::make('day')->label('Day')->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        })
                        ->placeholder('e.g., Sunday, Monday'),
                    TextInput::make('time')->label('Time')->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $set('preview_trigger', microtime(true));
                        })
                        ->placeholder('e.g., 9:00 AM - 9:00 PM'),
                ])
                ->defaultItems(7)
                ->addActionLabel('Add Day')
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['day'] ?? 'Day'),

            TextInput::make('content.smallTitle')->label('Small Title')
                ->live()->default('Working Hours')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            TextInput::make('content.title')->label('Title')
                ->live()->default('Visit Us Today')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            Textarea::make('content.description')->label('Description')->rows(3)
                ->live()->default('We\'re ready to help you look and feel your best. Contact us during our business hours.')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            ColorPicker::make('content.smallTitleColor')->label('Small Title Color')->default('#e74c3c')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            ColorPicker::make('content.titleColor')->label('Title Color')->default('#2c3e50')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            ColorPicker::make('content.descriptionColor')->label('Description Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            ColorPicker::make('content.dayNameColor')->label('Day Name Color')->default('#333')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),
            ColorPicker::make('content.timeColor')->label('Time Color')->default('#666')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'working_hours_section'),

            // Contact Section
            TextInput::make('content.hoursTitle')->label('Hours Section Title')
                ->live()->default('Working Hours')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            TextInput::make('content.locationTitle')->label('Location Section Title')
                ->live()->default('Our Location')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            TextInput::make('content.locationAr')->label('Arabic Address')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            TextInput::make('content.locationEn')->label('English Address')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            TextInput::make('content.phoneNo1')->label('Phone Number 1')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            TextInput::make('content.phoneNo2')->label('Phone Number 2')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            Textarea::make('content.mapSrc')->label('Google Map Embed URL')->rows(3)
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            ColorPicker::make('content.backgroundColor')->label('Background Color')->default('#f8f9fa')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),
            ColorPicker::make('content.textColor')->label('Text Color')->default('#333')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'contact_section'),

            // Pricing Section
            TextInput::make('content.sectionTitle')->label('Section Title')
                ->live()->default('Our Services & Prices')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'pricing_section'),
            TextInput::make('content.buttonText')->label('Button Text')
                ->live()->default('View All Prices')
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'pricing_section'),
            TextInput::make('content.buttonUrl')->label('Button URL')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'pricing_section'),
            Toggle::make('content.showSectionTitle')->label('Show Section Title')
                ->live()->default(true)
                ->afterStateUpdated(function ($state, $set) {
                    $set('preview_trigger', microtime(true));
                })
                ->visible(fn ($get) => $get('section_name') === 'pricing_section'),
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
                        'hero_section' => 'danger',
                        'trending_services' => 'warning',
                        'text_content_1' => 'info',
                        'text_content_2' => 'success',
                        'services_section' => 'primary',
                        'text_content_3' => 'secondary',
                        'pricing_section' => 'gray',
                        'wide_image_section' => 'info',
                        'text_content_4' => 'warning',
                        'working_hours_section' => 'success',
                        'contact_section' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => HomeSection::getSectionNameMapping()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('content->title')
                    ->label('Title')
                    ->limit(50)
                    ->placeholder('No title set')
                    ->searchable()
                    ->tooltip(fn ($record) => $record->content['title'] ?? $record->content['smallTitle'] ?? 'No title set'),
                
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
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add New Section')
                    ->color('warning')
                    ->icon('heroicon-o-plus'),
            ])
            ->emptyStateHeading('No sections created yet')
            ->emptyStateDescription('Create your first home page section to get started.')
            ->emptyStateIcon('heroicon-o-home')
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
            'index' => Pages\ListHomeSections::route('/'),
            'create' => Pages\CreateHomeSection::route('/create'),
            'edit' => Pages\EditHomeSection::route('/{record}/edit'),
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