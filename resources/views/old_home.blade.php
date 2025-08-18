@extends('layouts.app')

@section('title', __('mcs.sa - Home'))

@section('content')


    <!-- Home Page Hero Section -->
    @include('filawidgets.home.hero-section', ['heroWidgets' => $heroWidgets])


    <div class="team-members-category container mt-6">
        <!-- CATEGORY TITLE -->
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center text-center mb-6">
                    <!-- Section ID -->
                    <span class="section-id text-center" style="color: #af8855">{{__('Best Selling Services')}}</span>
                    <!-- Title -->
                    <h2 class="h2-title" style="color: #363636">{{__('Trending Services')}}</h2>
                </div>
            </div>
        </div>
        <!-- TEAM MEMBERS WRAPPER -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
            @foreach ($services as $service)
                <!-- TEAM MEMBER #1 -->
                <div class="col">
                    <div class="team-member wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                        <!-- Team Member Photo -->
                        <div class="team-member-photo">
                            <div class="hover-overlay" style="height: 300px; overflow: hidden;">
                                <a href="{{ route('services.detail', $service->id) }}">
                                    <img class="img-fluid rounded w-100 h-100" src="{{ asset('storage/' . $service->image) }}" alt="team-member-foto" style="object-fit: cover; object-position: center;">
                                    <div class="item-overlay"></div>
                                </a>
                            </div>
                        </div>
                        <!-- Team Member Data -->
                        <div class="team-member-data">
                            <!-- Title -->
                            <span class="section-id">{{ $service->name }}</span>
                            {{-- <h5 class="h5-lg">{{ $service->name }}</h5> --}}
                            <!-- Link -->
                            <p class="tra-link"><a href="{{ route('salon-services', ['category' => $service->category_id]) }}">{{__('View More')}}</a></p>
                        </div>
                    </div>
                </div>
                <!-- END TEAM MEMBER #1 -->
            @endforeach
        </div>
        <!-- END TEAM MEMBERS WRAPPER -->



    </div>



    <!-- Home Page Text Content 1 -->
    @include('filawidgets.home.text-content-1', ['textContent' => $textContent])

    <!-- Home Page Text Content 2 -->
    @include('filawidgets.home.text-content-2', ['textContent2' => $textContent2])

    <!-- Home Page Services Section -->
    @include('filawidgets.home.services-section', ['servicesContent' => $servicesContent])

    <!-- Home Page Text Content 3 -->
    @include('filawidgets.home.text-content-3', ['textContent3' => $textContent3])

    <!-- Pricing Section -->
    @include('filawidgets.common.pricing-section', [
        'serviceCategories' => $serviceCategories,
        'buttonText' => 'View All Prices',
        'buttonUrl' => route('menu'),
        'buttonHoverClass' => 'gold',
        'showSectionTitle' => true,
        'paddingTop' => 'py-8',
    ])

    <!-- Home Page Wide Image Section -->
    @include('filawidgets.home.wide-image-section', ['wideImage' => $wideImage])

    <!-- Home Page Text Content 4 -->
    @include('filawidgets.home.text-content-4', ['textContent4' => $textContent4])

    <!-- Home Page Working Hours Section -->
    @include('filawidgets.common.working-hours-section', [
        'workingHours' => $workingHours,
        'workingHoursContent' => $workingHoursContent,
        'qrCode' => $qrCode,
    ])

    <!-- Home Page Contact Section -->
    @include('filawidgets.home.contact-section', [
        'contactSectionContent' => $contactSectionContent,
        'workingHours' => $workingHours,
    ])

    <!-- Common Styles -->
    @include('filawidgets.common.styles')

    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection