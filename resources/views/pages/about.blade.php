@extends('layouts.app')

@section('title', __('About - mcs.sa Salon'))

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

// Fixed helper function for live server image display
function getImageUrl($imagePath, $default = null) {
    if (empty($imagePath)) {
        return $default ? asset($default) : asset('/assets/images/placeholder.jpg');
    }

    // Handle arrays of paths
    if (is_array($imagePath)) {
        foreach ($imagePath as $path) {
            if (is_string($path) && !empty(trim($path))) {
                $imagePath = trim($path);
                break;
            }
        }
        
        // If no valid path found in array
        if (is_array($imagePath)) {
            return $default ? asset($default) : asset('/assets/images/placeholder.jpg');
        }
    }

    // Clean the path
    $imagePath = trim($imagePath, '/ ');
    
    // If it's already a full URL, return as is
    if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
        return $imagePath;
    }
    
    // Remove 'storage/' prefix if present to avoid double prefix
    if (str_starts_with($imagePath, 'storage/')) {
        $imagePath = substr($imagePath, 8);
    }
    
    // Add 'about/' prefix if not present
    if (!str_starts_with($imagePath, 'about/')) {
        $imagePath = 'about/' . $imagePath;
    }
    
    // Return the asset URL
    return asset('storage/' . $imagePath);
}
@endphp
<!-- DYNAMIC SECTIONS RENDERING BASED ON DATABASE ORDER -->
@foreach($sections as $section)
    @if($section->section_name === 'hero' && isSectionVisible($section))
        <!-- INNER PAGE HERO -->
        <section id="about-page" class="inner-page-hero division"
            @if(isset($section->content['background_image']))
                style="background-image: url('{{ getImageUrl($section->content['background_image']) }}');"
            @endif
        >
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page-hero-txt color--white">
                            <h2>{{ isset($section->content['title']) ? __($section->content['title']) : __('About mcs.sa') }}</h2>
                            <p>{{ isset($section->content['description']) ? __($section->content['description']) : __('Luxury salon where you will feel unique and special') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END INNER PAGE HERO -->

    @elseif($section->section_name === 'about_content' && isSectionVisible($section))
        <!-- TEXT CONTENT -->
        <section class="pt-8 ct-03 content-section division">
            <div class="container">
                <div class="row">
                    <!-- TEXT BLOCK -->
                    <div class="col-lg-6">
                        <div class="txt-block left-column wow fadeInRight">
                            <!-- TEXT -->
                            <div class="ct-03-txt">
                                <!-- Section ID -->
                                <span class="section-id">{{ isset($section->content['left_section_id']) ? __($section->content['left_section_id']) : __('Mind, Body and Soul') }}</span>
                                <!-- Title -->
                                <h2 class="h2-md">{{ isset($section->content['left_title']) ? __($section->content['left_title']) : __('Luxury salon where you will feel unique') }}</h2>
                                <!-- Text -->
                                <p class="mb-5">{{ isset($section->content['left_body']) ? __($section->content['left_body']) : __('Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty. Experience tranquility and rejuvenation in our meticulously designed space created with your comfort in mind.') }}</p>
                            </div>
                            <!-- IMAGE -->
                            <div class="ct-03-img">
                                <img class="img-fluid" src="{{ isset($section->content['left_image']) ? getImageUrl($section->content['left_image'], '/assets/images/beauty_08.jpg') : '/assets/images/beauty_08.jpg' }}" alt="content-image">
                            </div>
                        </div>
                    </div>
                    <!-- END TEXT BLOCK -->

                    <!-- TEXT BLOCK -->
                    <div class="col-lg-6">
                        <div class="txt-block right-column wow fadeInLeft">
                            <!-- IMAGE -->
                            <div class="ct-03-img mb-5">
                                <img class="img-fluid" src="{{ isset($section->content['right_image']) ? getImageUrl($section->content['right_image'], '/assets/images/salon_04.jpg') : '/assets/images/salon_04.jpg' }}" alt="content-image">
                            </div>
                            <!-- TEXT -->
                            <div class="ct-03-txt">
                                <!-- Text -->
                                <p class="mb-0">{{ isset($section->content['right_body']) ? __($section->content['right_body']) : __('At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect. Our talented team continuously trains in the latest trends and methods to ensure you receive the highest quality care with every visit.') }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- END TEXT BLOCK -->
                </div>
            </div>
        </section>
        <!-- END TEXT CONTENT -->

    @elseif($section->section_name === 'center_text' && isSectionVisible($section))
        <!-- ABOUT-1 -->
        <section class="pt-8 about-1 about-section">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- TEXT BLOCK -->
                    <div class="col-lg-10 col-xl-9">
                        <div class="txt-block text-center">
                            <!-- Section ID -->
                            <span class="section-id">{{ isset($section->content['section_id']) ? __($section->content['section_id']) : __('Indulge Yourself') }}</span>
                            <!-- Title -->
                            <h2 class="h2-title">{{ isset($section->content['title_center']) ? __($section->content['title_center']) : __('Feel Yourself More Beautiful') }}</h2>
                            <!-- Text -->
                            <p class="mb-0">{{ isset($section->content['body_center']) ? __($section->content['body_center']) : __('Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END ABOUT-1 -->

    @elseif($section->section_name === 'services_preview' && isSectionVisible($section))
        <!-- SERVICES-3 -->
        <div id="services-3" class="pt-8 services-section division">
            <div class="container">
                <div class="sbox-3-wrapper text-center">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6">
                        @if(isset($section->content['services']) && is_array($section->content['services']))
                            @foreach($section->content['services'] as $index => $service)
                                <div class="col">
                                    <div class="sbox-3 sb-{{ $index + 1 }} wow fadeInUp">
                                        <!-- Icon -->
                                        <div class="sbox-ico ico-65">
                                            <span class="{{ $service['icon_class'] ?? 'flaticon-facial-treatment' }} color--black"></span>
                                        </div>
                                        <!-- Text -->
                                        <div class="sbox-txt">
                                            <p>{{ __($service['name'] ?? 'Service') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- DEFAULT SERVICES IF NO DATABASE DATA -->
                            <div class="col">
                                <div class="sbox-3 sb-1 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-facial-treatment color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Facials') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="sbox-3 sb-2 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-eyelashes color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Eyelash') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="sbox-3 sb-3 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-eyebrow color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Eyebrow') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="sbox-3 sb-4 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-wax color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Waxing') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="sbox-3 sb-5 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-foundation color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Nails') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="sbox-3 sb-6 wow fadeInUp">
                                    <div class="sbox-ico ico-65">
                                        <span class="flaticon-cosmetics color--black"></span>
                                    </div>
                                    <div class="sbox-txt">
                                        <p>{{ __('Make-Up') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="row">
                    <div class="col">
                        <div class="more-btn mt-5">
                            <a href="{{ route('menu') }}" class="btn btn--tra-black hover--black">{{ __('View Our Menu') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SERVICES-3 -->

    @elseif($section->section_name === 'features_accordion' && isSectionVisible($section))
        <!-- TEXT CONTENT (Features) -->
        <section class="pt-8 ct-05 content-section">
            <div class="container stone--shape">
                <div class="row d-flex align-items-center">
                    <!-- TEXT BLOCK -->
                    <div class="col-lg-6 order-last order-lg-2">
                        <div class="txt-block left-column wow fadeInRight">
                            <!-- Section ID -->
                            <span class="section-id">{{ isset($section->content['features_section_id']) ? __($section->content['features_section_id']) : __('You Are Beauty') }}</span>
                            <!-- Title -->
                            <h2 class="h2-md">{{ isset($section->content['features_title']) ? __($section->content['features_title']) : __('Give the pleasure of beautiful to yourself') }}</h2>
                            
                            <!-- ACCORDION WRAPPER -->
                            <div class="accordion accordion-wrapper mt-5">
                                <ul class="accordion">
                                    @if(isset($section->content['features_accordion']) && is_array($section->content['features_accordion']))
                                        @foreach($section->content['features_accordion'] as $index => $feature)
                                            <li class="accordion-item {{ $index === 0 ? 'is-active' : '' }}">
                                                <div class="accordion-thumb">
                                                    <p>{{ __($feature['title'] ?? 'Feature Title') }}</p>
                                                </div>
                                                <div class="accordion-panel">
                                                    <p class="mb-0">{{ __($feature['content'] ?? 'Feature description') }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <!-- DEFAULT ACCORDION ITEMS -->
                                        <li class="accordion-item is-active">
                                            <div class="accordion-thumb">
                                                <p>{{ __('Certified Stylists') }}</p>
                                            </div>
                                            <div class="accordion-panel">
                                                <p class="mb-0">{{ __('Our team consists of highly trained professionals with international certifications and years of experience in the beauty industry.') }}</p>
                                            </div>
                                        </li>
                                        <li class="accordion-item">
                                            <div class="accordion-thumb">
                                                <p>{{ __('100% Organic Cosmetics') }}</p>
                                            </div>
                                            <div class="accordion-panel">
                                                <p class="mb-0">{{ __('We use only premium organic and cruelty-free products that nourish your skin and hair while being kind to the environment.') }}</p>
                                            </div>
                                        </li>
                                        <li class="accordion-item">
                                            <div class="accordion-thumb">
                                                <p>{{ __('Easy Online Booking') }}</p>
                                            </div>
                                            <div class="accordion-panel">
                                                <p class="mb-0">{{ __('Book your appointments with ease through our convenient online system, available 24/7 with instant confirmation and reminders.') }}</p>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- IMAGE BLOCK -->
                    <div class="col-lg-6 order-first order-lg-2">
                        <div class="ct-05-img right-column wow fadeInLeft">
                            <img class="img-fluid" src="{{ isset($section->content['features_image']) ? getImageUrl($section->content['features_image'], '/assets/images/woman_02.jpg') : '/assets/images/woman_02.jpg' }}" alt="content-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END TEXT CONTENT -->

    @elseif($section->section_name === 'working_hours' && isSectionVisible($section, ['working_hours']))
        <!-- WORKING HOURS -->
        <section class="py-8 ct-table content-section division">
            <div class="container">
                <div class="row d-flex align-items-center">
                    <!-- TABLE -->
                    <div class="col-lg-6 order-last order-lg-2">
                        <div class="txt-table left-column wow fadeInRight">
                            <table class="table">
                                <tbody>
                                    @foreach($section->content['working_hours'] as $index => $hours)
                                        <tr @if($index == count($section->content['working_hours']) - 1) class="last-tr" @endif>
                                            <td style="color: {{ $section->content['dayNameColor'] ?? '#333' }}">
                                                {{ __($hours['day'] ?? 'Day') }}
                                            </td>
                                            <td> - </td>
                                            <td class="text-end" style="color: {{ $section->content['timeColor'] ?? '#666' }}">
                                                {{ __($hours['time'] ?? 'Time') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TEXT -->
                    <div class="col-lg-6 order-first order-lg-2">
                        <div class="right-column wow fadeInLeft">
                            <span class="section-id" style="color: {{ $section->content['smallTitleColor'] ?? '#e74c3c' }}">
                                {{ __($section->content['smallTitle'] ?? 'Working Hours') }}
                            </span>
                            <h2 class="h2-md" style="color: {{ $section->content['titleColor'] ?? '#2c3e50' }}">
                                {{ __($section->content['title'] ?? 'Our Schedule') }}
                            </h2>
                            <p class="mb-0" style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                {{ __($section->content['description'] ?? 'Contact us during our business hours for appointments and inquiries.') }}
                            </p>

                            @if(isset($qrCode))
                                <div class="row justify-content-center mt-2">
                                    <div class="col-auto">
                                        <div class="qr-code-container text-center">
                                            <div class="card p-3 shadow-sm">
                                                {!! $qrCode !!}
                                                <h6 class="mt-2">{{ __('Book Now') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END WORKING HOURS -->

    @elseif($section->section_name === 'wide_image' && isSectionVisible($section, ['image']))
        <!-- WIDE IMAGE -->
        <div class="bg--01 bg--scroll ct-12 content-section"
            style="background-image: url('{{ getImageUrl($section->content['image']) }}');"
        ></div>
        <!-- END WIDE IMAGE -->

    @elseif($section->section_name === 'about5' && isSectionVisible($section))
        <!-- ABOUT-5 -->
        <section class="pt-8 about-5 about-section">
            <div class="container-fluid">
                <div class="row">
                    <!-- IMAGE BLOCK -->
                    <div class="col">
                        <div id="ab-5-1" class="about-5-img">
                            <img class="img-fluid" src="{{ isset($section->content['image_1']) ? getImageUrl($section->content['image_1'], '/assets/images/beauty_02.jpg') : '/assets/images/beauty_02.jpg' }}" alt="about-image">
                        </div>
                    </div>
                    <!-- TEXT BLOCK -->
                    <div class="col-md-8 col-lg-7 order-first order-md-1">
                        <div class="txt-block">
                            <span class="section-id">{{ isset($section->content['small_title']) ? __($section->content['small_title']) : __('Be Irresistible') }}</span>
                            <h2 class="h2-title">{{ isset($section->content['title']) ? __($section->content['title']) : __('The Ultimate Relaxation for Your Mind and Body') }}</h2>
                            <div id="ab-5-2" class="about-5-img">
                                <img class="img-fluid" src="{{ isset($section->content['image_2']) ? getImageUrl($section->content['image_2'], '/assets/images/beauty_03.jpg') : '/assets/images/beauty_03.jpg' }}" alt="about-image">
                            </div>
                        </div>
                    </div>
                    <!-- IMAGE BLOCK -->
                    <div class="col order-last order-md-2">
                        <div id="ab-5-3" class="about-5-img">
                            <img class="img-fluid" src="{{ isset($section->content['image_3']) ? getImageUrl($section->content['image_3'], '/assets/images/beauty_04.jpg') : '/assets/images/beauty_04.jpg' }}" alt="about-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END ABOUT-5 -->

    @elseif($section->section_name === 'banner_promo' && isSectionVisible($section))
        <!-- BANNER-1 -->
        <section class="pt-8 banner-1 banner-section">
            <div class="container">
                <div class="banner-1-wrapper bg--fixed"
                    @if(isset($section->content['background']))
                        style="background-image: url('{{ getImageUrl($section->content['background']) }}');"
                    @endif
                >
                    <div class="row">
                        <div class="col">
                            <div class="banner-1-txt text-center color--white">
                                <span class="section-id">{{ isset($section->content['small_title']) ? __($section->content['small_title']) : __('This Week Only') }}</span>
                                <h2>{{ isset($section->content['title']) ? __($section->content['title']) : __('Get 30% OFF') }}</h2>
                                <h3>{{ isset($section->content['subtitle']) ? __($section->content['subtitle']) : __('Manicure + Gel Polish') }}</h3>
                                <a href="{{ isset($section->content['button_link']) ? $section->content['button_link'] : route('salon-services') }}" class="btn btn--tra-white hover--white">
                                    {{ isset($section->content['button_text']) ? __($section->content['button_text']) : __('Book an Appointment') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END BANNER-1 -->
    @endif
@endforeach

<!-- FALLBACK WORKING HOURS (if no database working hours section) -->
@if(!$sections->contains('section_name', 'working_hours') && isset($workingHours))
    @include('filawidgets.common.working-hours-section', ['workingHours' => $workingHours, 'workingHoursContent' => $workingHoursContent, 'qrCode' => $qrCode])
@endif

<!-- Cart Float Component -->
@include('components.cart-float')

@endsection