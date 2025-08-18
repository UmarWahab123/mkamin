@extends('layouts.app')

@section('title', __('mcs.sa - Home'))

@section('content')
    @php
    // Helper function to check if section is visible and has content
    function isSectionVisible($section, $requiredFields = []) {
        if (!$section) return false;
        
        // If no required fields specified, just check if section exists
        if (empty($requiredFields)) return true;
        
        // Check if all required fields exist and are not empty
        foreach ($requiredFields as $field) {
            if (!isset($section->content[$field]) || empty($section->content[$field])) {
                return false;
            }
        }
        
        return true;
    }

    // Helper function to get image URL
    function getImageUrl($imagePath, $default = null) {
        if (empty($imagePath)) {
            return $default;
        }
        
        if (is_array($imagePath)) {
            foreach ($imagePath as $path) {
                if (is_string($path) && str_starts_with($path, 'home/')) {
                    return asset('storage/' . $path);
                }
            }
            return $default;
        }
        
        if (is_string($imagePath)) {
            if (str_starts_with($imagePath, 'home/')) {
                return asset('storage/' . $imagePath);
            }
            if (str_starts_with($imagePath, 'http')) {
                return $imagePath;
            }
        }
        
        return $default;
    }
    @endphp
@foreach($sections as $section)
    <!-- Home Page Hero Section -->
    @if($section->section_name  === 'hero_section' && isSectionVisible($section))
    @include('filawidgets.home.hero-section')
    @endif
        <!-- Trending Services Section -->
    @if($section->section_name === 'trending_services' && isSectionVisible($section))

        @include('filawidgets.home.trending-service')
    @endif
    <!-- Home Page Text Content 1 -->
    @if($section->section_name === 'text_content_1' && isSectionVisible($section))
    @include('filawidgets.home.text-content-1')
    @endif

    <!-- Home Page Text Content 2 -->
    @if($section->section_name === 'text_content_2' && isSectionVisible($section))
    @include('filawidgets.home.text-content-2')
    @endif

    <!-- Home Page Services Section -->
    @if($section->section_name === 'services_section' && isSectionVisible($section))
    @include('filawidgets.home.services-section')
    @endif

    <!-- Home Page Text Content 3 -->
    @if($section->section_name === 'text_content_3' && isSectionVisible($section))
    @include('filawidgets.home.text-content-3')
    @endif


    <!-- Pricing Section -->
    @if($section->section_name === 'pricing_section' && isSectionVisible($section))
    @include('filawidgets.common.pricing-section',[
        'serviceCategories' => $serviceCategories,
        'buttonHoverClass' => 'gold',
        'showSectionTitle' => true,
        'paddingTop' => 'py-8',
    ])
    @endif

    <!-- Home Page Wide Image Section -->
    @if($section->section_name === 'wide_image_section' && isSectionVisible($section))
    @include('filawidgets.home.wide-image-section')
    @endif
    <!-- Home Page Text Content 4 -->
    @if($section->section_name === 'text_content_4' && isSectionVisible($section))
    @include('filawidgets.home.text-content-4')
    @endif

    <!-- Home Page Working Hours Section -->
    @if($section->section_name === 'working_hours_section' && isSectionVisible($section))
    @include('filawidgets.home.working-hours-section')  
    @endif

    <!-- Home Page Contact Section -->
    @if($section->section_name === 'contact_section' && isSectionVisible($section))
    @include('filawidgets.home.contact-section')
    @endif
@endforeach


    <!-- Common Styles -->
    @include('filawidgets.common.styles')

    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection
