<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'content',
        'order',
        'visible'
    ];

    protected $casts = [
        'content' => 'array',
        'visible' => 'boolean',
        'order' => 'integer'
    ];

    // Boot method to automatically assign order
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order)) {
                $model->order = static::max('order') + 1;
            }
        });
    }

    // Scope for visible sections
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    // Scope for ordered sections
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Get sections for frontend display
    public static function getVisibleSections()
    {
        return static::visible()->ordered()->get();
    }

    // Reorder sections
    public function moveToPosition($newPosition)
    {
        $currentPosition = $this->order;
        
        if ($newPosition == $currentPosition) {
            return;
        }

        if ($newPosition < $currentPosition) {
            // Moving up - increment order of sections between new and current position
            static::whereBetween('order', [$newPosition, $currentPosition - 1])
                ->increment('order');
        } else {
            // Moving down - decrement order of sections between current and new position
            static::whereBetween('order', [$currentPosition + 1, $newPosition])
                ->decrement('order');
        }

        $this->update(['order' => $newPosition]);
    }

    // Get next available order number
    public static function getNextOrder()
    {
        return (static::max('order') ?? 0) + 1;
    }

    // Normalize orders (remove gaps)
    public static function normalizeOrders()
    {
        $sections = static::ordered()->get();
        
        $sections->each(function ($section, $index) {
            $section->update(['order' => $index + 1]);
        });
    }

    // Get section by name
    public static function getByName($sectionName)
    {
        return static::where('section_name', $sectionName)->first();
    }

    // Check if section has required content
    public function hasRequiredContent($requiredFields = [])
    {
        if (empty($requiredFields)) {
            return true;
        }

        foreach ($requiredFields as $field) {
            if (!isset($this->content[$field]) || empty($this->content[$field])) {
                return false;
            }
        }

        return true;
    }

    // Get content field with fallback
    public function getContent($field, $default = null)
    {
        return $this->content[$field] ?? $default;
    }

    // Set content field
    public function setContent($field, $value)
    {
        $content = $this->content ?? [];
        $content[$field] = $value;
        $this->update(['content' => $content]);
    }

    // Home page specific helper methods based on actual section content
    
    // Get hero section
    public static function getHeroSection()
    {
        return static::getByName('hero_section');
    }

    // Get trending services section
    public static function getTrendingServices()
    {
        return static::getByName('trending_services');
    }

    // Get text content sections
    public static function getTextContent1()
    {
        return static::getByName('text_content_1');
    }

    public static function getTextContent2()
    {
        return static::getByName('text_content_2');
    }

    public static function getTextContent3()
    {
        return static::getByName('text_content_3');
    }

    public static function getTextContent4()
    {
        return static::getByName('text_content_4');
    }

    // Get services section
    public static function getServicesSection()
    {
        return static::getByName('services_section');
    }

    // Get pricing section
    public static function getPricingSection()
    {
        return static::getByName('pricing_section');
    }

    // Get wide image section
    public static function getWideImageSection()
    {
        return static::getByName('wide_image_section');
    }

    // Get working hours section
    public static function getWorkingHoursSection()
    {
        return static::getByName('working_hours_section');
    }

    // Get contact section
    public static function getContactSection()
    {
        return static::getByName('contact_section');
    }

    // Section-specific content helpers
    
    // Hero section helpers
    public function getHeroSlides()
    {
        return $this->getContent('slides', []);
    }

    // Text content helpers
    public function getSmallTitle()
    {
        return $this->getContent('smallTitle', '');
    }

    public function getTitle()
    {
        return $this->getContent('title', '');
    }

    public function getDescription()
    {
        return $this->getContent('description', '');
    }

    public function getImage()
    {
        return $this->getContent('image', '');
    }

    // Color helpers for styling
    public function getSmallTitleColor()
    {
        return $this->getContent('smallTitleColor', '#af8855');
    }

    public function getTitleColor()
    {
        return $this->getContent('titleColor', '#363636');
    }

    public function getDescriptionColor()
    {
        return $this->getContent('descriptionColor', '#666');
    }

    public function getBackgroundColor()
    {
        return $this->getContent('backgroundColor', '#ffffff');
    }

    // Button helpers
    public function getButtonText()
    {
        return $this->getContent('buttonText', 'Learn More');
    }

    public function getButtonUrl()
    {
        return $this->getContent('buttonUrl', '#');
    }

    public function getButtonBgColor()
    {
        return $this->getContent('buttonBgColor', '#af8855');
    }

    public function getButtonTextColor()
    {
        return $this->getContent('buttonTextColor', '#ffffff');
    }

    // Services section helpers
    public function getServices()
    {
        return $this->getContent('services', []);
    }

    // Contact section helpers
    public function getContactData()
    {
        return [
            'backgroundColor' => $this->getContent('backgroundColor', '#f8f9fa'),
            'textColor' => $this->getContent('textColor', '#333'),
            'hoursTitle' => $this->getContent('hoursTitle', 'Working Hours'),
            'locationTitle' => $this->getContent('locationTitle', 'Our Location'),
            'locationAr' => $this->getContent('locationAr', ''),
            'locationEn' => $this->getContent('locationEn', ''),
            'phoneNo1' => $this->getContent('phoneNo1', ''),
            'phoneNo2' => $this->getContent('phoneNo2', ''),
            'mapSrc' => $this->getContent('mapSrc', ''),
        ];
    }

    // Working hours helpers
    public function getWorkingHoursData()
    {
        return [
            'smallTitle' => $this->getContent('smallTitle', 'Working Hours'),
            'title' => $this->getContent('title', 'Our Schedule'),
            'description' => $this->getContent('description', 'Contact us during our business hours'),
            'smallTitleColor' => $this->getContent('smallTitleColor', '#e74c3c'),
            'titleColor' => $this->getContent('titleColor', '#2c3e50'),
            'descriptionColor' => $this->getContent('descriptionColor', '#666'),
            'dayNameColor' => $this->getContent('dayNameColor', '#333'),
            'timeColor' => $this->getContent('timeColor', '#666'),
        ];
    }

    // Check if section should show booking button
    public function hasBookingButton()
    {
        return in_array($this->section_name, [
            'hero_section', 
            'services_section', 
            'trending_services',
            'contact_section',
            'text_content_3'
        ]);
    }

    // Get section background type (image, color, gradient)
    public function getBackgroundType()
    {
        return $this->getContent('background_type', 'color');
    }

    // Get section theme (light, dark)
    public function getTheme()
    {
        return $this->getContent('theme', 'light');
    }

    // Check if section should be full width
    public function isFullWidth()
    {
        return in_array($this->section_name, [
            'hero_section', 
            'wide_image_section', 
            'pricing_section',
            'contact_section'
        ]);
    }

    // Check if section has text content
    public function isTextContentSection()
    {
        return in_array($this->section_name, [
            'text_content_1',
            'text_content_2', 
            'text_content_3',
            'text_content_4'
        ]);
    }

    // Get section padding class
    public function getPaddingClass()
    {
        return $this->getContent('padding_class', 'pt-8');
    }

    // Text Content 2 specific helpers (3-image layout)
    public function getTextContent2Data()
    {
        return [
            'smallTitle' => $this->getContent('smallTitle', ''),
            'title' => $this->getContent('title', ''),
            'smallTitleColor' => $this->getContent('smallTitleColor', '#af8855'),
            'titleColor' => $this->getContent('titleColor', '#363636'),
            'image1' => $this->getContent('image1', ''),
            'image2' => $this->getContent('image2', ''),
            'image3' => $this->getContent('image3', ''),
            'cardTitle' => $this->getContent('cardTitle', ''),
            'cardDescription' => $this->getContent('cardDescription', ''),
            'cardBackgroundColor' => $this->getContent('cardBackgroundColor', '#ffffff'),
            'cardTitleColor' => $this->getContent('cardTitleColor', '#363636'),
            'cardDescriptionColor' => $this->getContent('cardDescriptionColor', '#666'),
        ];
    }

    // Text Content 4 specific helpers (side-by-side layout)
    public function getTextContent4Data()
    {
        return [
            'smallTitle' => $this->getContent('smallTitle', ''),
            'title' => $this->getContent('title', ''),
            'description1' => $this->getContent('description1', ''),
            'description2' => $this->getContent('description2', ''),
            'image' => $this->getContent('image', ''),
            'backgroundColor' => $this->getContent('backgroundColor', '#ffffff'),
            'smallTitleColor' => $this->getContent('smallTitleColor', '#af8855'),
            'titleColor' => $this->getContent('titleColor', '#363636'),
            'description1Color' => $this->getContent('description1Color', '#666'),
            'description2Color' => $this->getContent('description2Color', '#666'),
        ];
    }

    // Home page section name mapping for admin display
    public static function getSectionNameMapping()
    {
        return [
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
        ];
    }

    // Get required fields for each section type
    public static function getRequiredFieldsBySection($sectionName)
    {
        return match($sectionName) {
            'hero_section' => ['slides'],
            'text_content_1' => ['smallTitle', 'title', 'description'],
            'text_content_2' => ['smallTitle', 'title'],
            'text_content_3' => ['smallTitle', 'title', 'description'],
            'text_content_4' => ['smallTitle', 'title', 'description1'],
            'services_section' => ['services'],
            'wide_image_section' => ['image'],
            'contact_section' => ['locationAr', 'locationEn'],
            'working_hours_section' => ['smallTitle', 'title'],
            default => [],
        };
    }
}