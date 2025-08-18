@extends('layouts.app')

@section('title', __('mcs.sa - Home'))

@section('content')
    @php
        use App\Helpers\WidgetHelper;
    @endphp

    <!-- Home Page Hero Section - DYNAMIC WIDGETS
                                    ============================================= -->
    <section id="hero-12" class="hero-section">
        <div class="slideshow">
            <div class="slideshow-inner">
                <!-- SLIDER -->
                <div class="slides">
                    @forelse($heroWidgets as $slide)
                        <!-- SLIDE #{{ $slide['key'] + 1 }} -->
                        <div class="slide {{ $slide['key'] === 0 ? 'is-active' : '' }}">
                            <!-- Slide Content -->
                            <div class="slide-content">
                                <div class="container">
                                    <div class="row justify-content-md-center">
                                        <div class="col col-lg-10">
                                            <div class="caption">
                                                <!-- Small Title with custom color -->
                                                <span class="slide-small-title"
                                                    style="color: {{ $slide['smallTitleColor'] }}">{{ __($slide['smallTitle']) }}</span>

                                                <!-- Title with custom color -->
                                                <div class="title">
                                                    <h2 class="slide-title" style="color: {{ $slide['titleColor'] }}">
                                                        {{ __($slide['title']) }}</h2>
                                                </div>

                                                <!-- Button with custom colors -->
                                                <div class="text">
                                                    <a href="{{ $slide['buttonUrl'] }}" class="custom-btn"
                                                        style="background-color: {{ $slide['buttonBgColor'] }}; color: {{ $slide['buttonTextColor'] }}; border-color: {{ $slide['buttonBgColor'] }};">
                                                        {{ __($slide['buttonText']) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Slide Content -->

                            <!-- Slide Background Image -->
                            <div class="image-container">
                                @if ($slide['bgImage'])
                                    <img class="image" src="{{ WidgetHelper::getImageUrl($slide['bgImage']) }}"
                                        alt="{{ __('slide-background') }}">
                                @else
                                    <img class="image" src="/assets/images/slider/mcs-{{ $slide['key'] + 1 }}.jpeg"
                                        alt="{{ __('slide-background') }}">
                                @endif
                            </div>
                        </div>
                        <!-- END SLIDE #{{ $slide['key'] + 1 }} -->
                    @empty
                        <!-- Fallback slide if no widgets -->
                        <div class="slide is-active">
                            <div class="slide-content">
                                <div class="container">
                                    <div class="row justify-content-md-center">
                                        <div class="col col-lg-10">
                                            <div class="caption color--white">
                                                <span>{{ __('Welcome') }}</span>
                                                <div class="title">
                                                    <h2>{{ __('No Slider Content Found') }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="image-container">
                                <img class="image" src="/assets/images/slider/mcs-1.jpeg"
                                    alt="{{ __('slide-background') }}">
                            </div>
                        </div>
                    @endforelse
                </div>
                <!-- END SLIDER -->
            </div>
            <!-- End Slideshow Inner -->
        </div>
        <!-- End Slideshow -->
    </section>
    <!-- END Home Page Hero Section -->


    <!-- Home Page Text Content 1 - DYNAMIC WIDGET
                                    ============================================= -->
    <section class="pt-8 ct-01 content-section division">
        <div class="container">
            <div class="row d-flex align-items-center">
                <!-- TEXT BLOCK -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-block left-column wow fadeInRight">
                        <!-- Section ID -->
                        <span class="section-id color--gold">{{ __($textContent['smallTitle']) }}</span>
                        <!-- Title -->
                        <h2 class="h2-md">{{ __($textContent['title']) }}</h2>
                        <!-- Text -->
                        <p class="mb-0">{{ __($textContent['description']) }}</p>
                    </div>
                </div>
                <!-- END TEXT BLOCK -->

                <!-- IMAGE BLOCK -->
                <div class="col-lg-6 order-first order-lg-2">
                    <div class="d-flex justify-content-center">
                        <img class="img-fluid rounded w-75 mx-auto"
                            src="{{ WidgetHelper::getImageUrl($textContent['image'], '/assets/images/mcs-3.jpeg') }}"
                            alt="{{ __('content-image') }}">
                    </div>
                </div>
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Text Content 1 -->

    <!-- Home Page Text Content 2
                                ============================================= -->
    <section class="pt-8 about-6 about-section">
        <div class="container">
            <!-- SECTION TITLE -->
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="section-title text-center mb-6">
                        <!-- Section ID -->
                        <span class="section-id"
                            style="color: {{ $textContent2['smallTitleColor'] }}">{{ __($textContent2['smallTitle']) }}</span>
                        <!-- Title -->
                        <h2 class="h2-title" style="color: {{ $textContent2['titleColor'] }}">
                            {{ __($textContent2['title']) }}</h2>
                    </div>
                </div>
            </div>

            <!-- ABOUT-6 CONTENT -->
            <div class="row">
                <!-- ABOUT-6 IMAGE -->
                <div class="col-md-6 col-lg-4">
                    <div id="a6-img-1" class="about-6-img">
                        <img class="img-fluid"
                            src="{{ WidgetHelper::getImageUrl($textContent2['image1'], '/assets/images/mcs-4.jpeg') }}"
                            alt="about-image">
                    </div>
                </div>

                <!-- ABOUT-6 TEXT -->
                <div class="col-lg-4 order-first order-lg-1">
                    <div class="about-6-txt">
                        <!-- TEXT -->
                        <div class="a6-txt" style="background-color: {{ $textContent2['cardBackgroundColor'] }}">
                            <!-- Title -->
                            <h4 class="h4-md" style="color: {{ $textContent2['cardTitleColor'] }}">
                                {{ __($textContent2['cardTitle']) }}</h4>
                            <!-- Text -->
                            <p class="mb-0" style="color: {{ $textContent2['cardDescriptionColor'] }}">
                                {{ __($textContent2['cardDescription']) }}
                            </p>
                        </div>
                        <!-- IMAGE -->
                        <div class="a6-img">
                            <img class="img-fluid"
                                src="{{ WidgetHelper::getImageUrl($textContent2['image3'], '/assets/images/mcs-6.jpeg') }}"
                                alt="about-image">
                        </div>
                    </div>
                </div>

                <!-- ABOUT-6 IMAGE -->
                <div class="col-md-6 col-lg-4 order-last order-lg-2">
                    <div id="a6-img-2" class="about-6-img">
                        <img class="img-fluid"
                            src="{{ WidgetHelper::getImageUrl($textContent2['image2'], '/assets/images/mcs-5.jpeg') }}"
                            alt="about-image">
                    </div>
                </div>
            </div>
            <!-- END ABOUT-6 CONTENT -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Text Content 2 -->

    <!-- Home Page Services Section
                            ============================================= -->
    <section id="services-2" class="pt-8 services-section division">
        <div class="container">
            <!-- Home Page Services Section WRAPPER -->
            <div class="sbox-2-wrapper text-center">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">

                    @foreach ($servicesContent['services'] as $index => $service)
                        <!-- SERVICES BOX #{{ $index + 1 }} -->
                        <div class="col">
                            <div class="sbox-2 sb-{{ $index + 1 }} wow fadeInUp">
                                <!-- Icon or Image -->
                                <div class="sbox-ico ico-65">
                                    @if ($service['image'])
                                        <img src="{{ WidgetHelper::getImageUrl($service['image']) }}"
                                            alt="{{ $service['title'] }}" class="service-img">
                                    @else
                                        <span class="{{ $service['icon'] }} color--gold"></span>
                                    @endif
                                </div>
                                <!-- Text -->
                                <div class="sbox-txt">
                                    <h5 class="h5-lg" style="color: {{ $servicesContent['titleColor'] }}">
                                        {{ __($service['title']) }}</h5>
                                    <p style="color: {{ $servicesContent['descriptionColor'] }}">
                                        {{ __($service['description']) }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- END SERVICES BOX #{{ $index + 1 }} -->
                    @endforeach

                </div>
                <!-- End row -->
            </div>
            <!-- END Home Page Services Section WRAPPER -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Services Section -->

    <!-- Home Page Text Content 3
                        ============================================= -->
    <section class="pt-8 ct-07 ws-wrapper content-section">
        <div class="container">
            <div class="ct-07-wrapper bg--black block-shadow color--white">
                <div class="row d-flex align-items-center">
                    <!-- TEXT BLOCK -->
                    <div class="col-lg-6 order-last order-lg-2">
                        <div class="txt-block left-column">
                            <!-- Section ID -->
                            <span class="section-id"
                                style="color: {{ $textContent3['smallTitleColor'] }}">{{ __($textContent3['smallTitle']) }}</span>
                            <!-- Title -->
                            <h2 class="h2-md" style="color: {{ $textContent3['titleColor'] }}">
                                {{ __($textContent3['title']) }}</h2>
                            <!-- Text -->
                            <p class="mb-0" style="color: {{ $textContent3['descriptionColor'] }}">
                                {{ __($textContent3['description']) }}</p>
                            <!-- Button -->
                            <div class="txt-block-btn">
                                <a href="{{ $textContent3['buttonUrl'] }}" class="btn custom-btn"
                                    style="background-color: {{ $textContent3['buttonBgColor'] }}; color: {{ $textContent3['buttonTextColor'] }}; border-color: {{ $textContent3['buttonBgColor'] }};">
                                    {{ __($textContent3['buttonText']) }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END TEXT BLOCK -->

                    <!-- IMAGE BLOCK -->
                    <div class="col-lg-6 order-first order-lg-2">
                        <div class="img-block right-column d-flex justify-content-end">
                            <img class="img-fluid" src="/assets/images/mcs-2.jpeg" style="max-height: 80vh;"
                                alt="content-image">
                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- End content wrapper -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Text Content 3 -->

    <!-- PRICING-5
                           ============================================= -->
    <div class="py-8 pricing-5 pricing-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-title text-center mb-6">

                        <!-- Section ID -->
                        <span class="section-id">{{ __('You\'ll Like It Here!') }}</span>

                        <!-- Title -->
                        <h2 class="h2-title">{{ __('Our Services & Prices') }}</h2>

                    </div>
                </div>
            </div>


            <!-- PRICING-5 WRAPPER -->
            <div class="pricing-5-wrapper">
                <div class="row">

                    @foreach ($serviceCategories as $index => $category)
                        <!-- PRICING-{{ $index + 1 }} TABLE -->
                        <div class="col-lg-6">
                            <div class="pricing-5-table {{ $index === 0 ? 'left-column' : 'right-column' }} wow fadeInUp">
                                <!-- CATEGORY TITLE -->
                                <h4 class="h4-md mb-4 text-center">{{ $category->name }}</h4>

                                <ul class="pricing-list">
                                    @forelse($category->productAndServices as $service)
                                        <!-- PRICING ITEM -->
                                        <li class="pricing-5-item">
                                            <div class="detail-price">
                                                <div class="price-name">
                                                    <p>{{ $service->name }}</p>
                                                </div>
                                                <div class="price-dots"></div>
                                                <div class="price-number">
                                                    @if ($service->sale_price_at_saloon && $service->sale_price_at_home)
                                                        <p>{{ __('SAR') }} {{ $service->sale_price_at_saloon }} -
                                                            {{ $service->sale_price_at_home }}</p>
                                                    @elseif($service->sale_price_at_saloon)
                                                        <p>{{ __('SAR') }} {{ $service->sale_price_at_saloon }}</p>
                                                    @elseif($service->sale_price_at_home)
                                                        <p>{{ __('SAR') }} {{ $service->sale_price_at_home }}</p>
                                                    @else
                                                        <p>{{ __('Price upon request') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <!-- No services found -->
                                        <li class="pricing-5-item">
                                            <div class="detail-price">
                                                <div class="price-name">
                                                    <p>{{ __('No services available') }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>
                                <!-- END PRICING LIST -->
                            </div>
                        </div>
                        <!-- END PRICING-{{ $index + 1 }} TABLE -->
                    @endforeach

                </div>
            </div>
            <!-- END PRICING-5 WRAPPER -->


            <!-- BUTTON -->
            <div class="row">
                <div class="col">
                    <div class="more-btn mt-5">
                        <a href="{{ route('menu') }}"
                            class="btn btn--tra-black hover--gold">{{ __('View All Prices') }}</a>
                    </div>
                </div>
            </div>


        </div>
        <!-- End container -->
    </div>
    <!-- END PRICING-5 -->

    <!-- Home Page Wide Image Section
                                    ============================================= -->
    <div class="bg--scroll ct-12 content-section"
        style="background-image: url({{ $wideImage ? WidgetHelper::getImageUrl($wideImage) : asset('assets/images/mcs-8.jpeg') }});">
    </div>
    <!-- END Home Page Wide Image Section -->



    <!-- Home Page Text Content 4
                                ============================================= -->
    <section class="pt-8 ct-05 content-section">
        <div class="container" style="background-color: {{ $textContent4['backgroundColor'] }}">
            <div class="row d-flex align-items-center">


                <!-- TEXT BLOCK -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-block left-column wow fadeInRight">

                        <!-- Section ID -->
                        <span class="section-id"
                            style="color: {{ $textContent4['smallTitleColor'] }}">{{ __($textContent4['smallTitle']) }}</span>

                        <!-- Title -->
                        <h2 class="h2-md" style="color: {{ $textContent4['titleColor'] }}">
                            {{ __($textContent4['title']) }}</h2>

                        <!-- Text -->
                        <p style="color: {{ $textContent4['description1Color'] }}">
                            {{ __($textContent4['description1']) }}</p>

                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $textContent4['description2Color'] }}">
                            {{ __($textContent4['description2']) }}</p>

                    </div>
                </div>
                <!-- END TEXT BLOCK -->


                <!-- IMAGE BLOCK -->
                <div class="col-lg-6 order-first order-lg-2 px-0">
                    <div class="ct-05-img right-column wow fadeInLeft right-column-image d-flex justify-content-end">
                        <img class="img-fluid column-image"
                            src="{{ WidgetHelper::getImageUrl($textContent4['image'], '/assets/images/mcs-7.jpeg') }}"
                            alt="content-image">
                    </div>
                </div>


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Text Content 4 -->


    <!-- Home Page Working Hours Section
                            ============================================= -->
    <section class="py-8 ct-table content-section division">
        <div class="container">
            <div class="row d-flex align-items-center">


                <!-- TABLE -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-table left-column wow fadeInRight">
                        <table class="table">
                            <tbody>
                                @foreach ($workingHours as $index => $hours)
                                    <tr @if ($index == 6) class="last-tr" @endif>
                                        <td style="color: {{ $workingHoursContent['dayNameColor'] }}">
                                            {{ __($hours['day']) }}</td>
                                        <td> - </td>
                                        <td class="text-end" style="color: {{ $workingHoursContent['timeColor'] }}">
                                            {{ __($hours['time']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END TABLE -->


                <!-- TEXT -->
                <div class="col-lg-6 order-first order-lg-2">
                    <div class="right-column wow fadeInLeft">

                        <!-- Section ID -->
                        <span class="section-id"
                            style="color: {{ $workingHoursContent['smallTitleColor'] }}">{{ __($workingHoursContent['smallTitle']) }}</span>

                        <!-- Title -->
                        <h2 class="h2-md" style="color: {{ $workingHoursContent['titleColor'] }}">
                            {{ __($workingHoursContent['title']) }}</h2>

                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $workingHoursContent['descriptionColor'] }}">
                            {{ __($workingHoursContent['description']) }}
                        </p>

                    </div>
                </div>


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Page Working Hours Section -->


    <!-- Home Pag Contact Section
                        ============================================= -->
    <section id="contacts-2" class="py-8 contacts-section division" style="background-color: {{ $contactSectionContent['backgroundColor'] }}">
        <div class="container">
            <div class="row d-flex align-items-center" style="color: {{ $contactSectionContent['textColor'] }}">


                <!-- HOURS & LOCATION -->
                <div class="col-lg-4">

                    <!-- WORKING HOURS -->
                    <div class="cbox-2 cb-1 mb-5">

                        <!-- Title -->
                        <h4 style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['hoursTitle']) }}</h4>

                        <!-- Text -->
                        @foreach($workingHours as $hours)
                            <p>
                                <span style="display: inline-block; width: 100px; color: {{ $contactSectionContent['textColor'] }}">{{ __($hours['day']) }} </span>
                                <span style="color: {{ $contactSectionContent['textColor'] }}">{{ __($hours['time']) }}</span>
                            </p>
                        @endforeach

                    </div>

                    <!-- LOCATION -->
                    <div class="cbox-2 cb-2">

                        <!-- Title -->
                        <h4 style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationTitle']) }}</h4>

                        <!-- Address -->
                        <p style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationAr']) }}</p>
                        <p style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationEn']) }}</p>

                        <!-- Contacts -->
                        <div class="cbox-2-contacts">
                            <p><a href="tel:{{ $contactSectionContent['phoneNo1'] }}" style="color: {{ $contactSectionContent['textColor'] }}">{{ $contactSectionContent['phoneNo1'] }}</a></p>
                            <p><a href="tel:{{ $contactSectionContent['phoneNo2'] }}" style="color: {{ $contactSectionContent['textColor'] }}">{{ $contactSectionContent['phoneNo2'] }}</a></p>
                        </div>

                    </div>

                </div>
                <!-- END HOURS & LOCATION -->


                <!-- GOOGLE MAP -->
                <div class="col-lg-8">
                    <div class="google-map">
                        <iframe
                            src="{{ $contactSectionContent['mapSrc'] }}"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <!-- END GOOGLE MAP -->


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END Home Pag Contact Section -->


    <style>
        .custom-btn {
            display: inline-block;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 2px solid transparent;
        }

        .custom-btn:hover {
            background-color: transparent !important;
            border-color: inherit;
            color: {{ $buttonTextColor }} !important;
            text-decoration: none;
        }

        .slide-title {
            margin-bottom: 20px;
            font-weight: 700;
        }

        .slide-small-title {
            font-size: 1.2rem;
            display: block;
            margin-bottom: 10px;
        }

        .service-img {
            max-width: 65px;
            max-height: 65px;
            object-fit: cover;
            border-radius: 50%;
        }

        .column-image {
            max-height: 80vh;
        }
    </style>
@endsection
