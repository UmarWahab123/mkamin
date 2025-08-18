@extends('layouts.app')

@section('title', __('Menu - mcs.sa Salon'))

@section('content')

    <!-- INNER PAGE HERO
        ============================================= -->
    <section id="pricing-page-1" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ __('Our Services') }}</h2>
                        <p>{{ __('It\'s time to give your hair some love') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->



    <!-- PRICING SECTION
    ============================================= -->

    <div class="pt-3 pricing-5 pricing-section division">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="Location type selection">
                    <a href="{{ route('menu', ['type' => 'salon']) }}"
                        class="btn {{ $locationType == 'salon' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Salon Services
                    </a>
                    <a href="{{ route('menu', ['type' => 'home']) }}"
                        class="btn {{ $locationType == 'home' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Home Services
                    </a>
                </div>
            </div>
            <!-- PRICING-5 WRAPPER -->
            <div class="pricing-5-wrapper mt-3">
                <div class="row">
                    @foreach ($serviceCategories as $index => $category)
                        <div class="col-lg-6">
                            <div class="pricing-5-table {{ $loop->even ? 'right-column' : 'left-column' }} wow fadeInUp">
                                <!-- PRICING LIST CATEGORY -->
                                <div class="pricing-5-category {{ $loop->first ? '' : 'mt-4' }} mb-4">
                                    <h3>{{ $category->name }}</h3>
                                </div>

                                <!-- SERVICES LIST -->
                                <ul class="pricing-list">
                                    @foreach ($category->productAndServices as $service)
                                        <li class="pricing-5-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="price-name">
                                                    <p>
                                                        {{ $service->name }}
                                                        @if ($service->isAvailableForBooking)
                                                            <i class="fas fa-question-circle service-info-icon"
                                                                data-service-id="{{ $service->id }}"
                                                                data-service-name="{{ $service->name }}"
                                                                data-service-description="{{ $service->description }}"
                                                                data-service-image="{{ asset('storage/' . $service->image) }}"
                                                                data-service-price="{{ $service->sale_price_at_saloon }}"
                                                                data-service-price-home="{{ $service->sale_price_at_home }}"
                                                                data-service-duration="{{ $service->duration_minutes }}"
                                                                data-service-category="{{ $category->name }}"
                                                                data-service-location-type="{{ $locationType }}"
                                                                title="{{ __('Click for details') }}"></i>
                                                        @else
                                                            <small class="text-muted fs-6">({{ __('Coming Soon') }})</small>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="price-dots"></div>
                                                <div class="price-number">
                                                    <p class="text-end"><span class="icon-saudi_riyal"></span> {{ $locationType == 'home' ? $service->sale_price_at_home : $service->sale_price_at_saloon }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--END PRICING WRAPPER -->

            <!-- BUTTON -->
            <div class="row">
                <div class="col">
                    <div class="more-btn mt-5">
                        <a href="{{ $locationType == 'home' ? route('home-services') : route('salon-services') }}"
                            class="btn btn--tra-black hover--{{ isset($buttonHoverClass) ? $buttonHoverClass : 'black' }}">
                            {{ __(isset($buttonText) ? $buttonText : (isset($pricingContent) && isset($pricingContent['buttonText']) ? $pricingContent['buttonText'] : 'View All Prices')) }}
                        </a>
                    </div>
                </div>
            </div>
            <!-- END BUTTON -->

        </div>
        <!-- End container -->
    </div>
    <!-- END PRICING SECTION -->
    <!-- Include Service Detail Modal Component -->
    @include('components.service-detail-modal', [
        'locationType' => $locationType,
    ])

    <!-- Working Hours Section -->
    @include('filawidgets.common.working-hours-section', [
        'workingHours' => $workingHours,
        'workingHoursContent' => $workingHoursContent,
        'qrCode' => $qrCode,
    ])

    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection

@section('styles')
    <!-- No additional styles needed as they're now in the component -->
@endsection

@section('scripts')
    <!-- No additional scripts needed as they're now in the component -->
@endsection
