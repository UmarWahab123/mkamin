@extends('layouts.app')

@section('title', __('Book Now - mcs.sa Salon'))

@section('content')


    <!-- INNER PAGE HERO
                    ============================================= -->
    <section id="booking-page" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ __('Book an Appointment') }}</h2>
                        <p>{{ __('Select services and products for your appointment') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->




    <!-- BOOKING SERVICES SECTION -->
    <div id="booking-services" class="pt-8 pb-7 booking-section division">
        <div class="container">
            <!-- Service Type Selection -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h3 class="h3-md mb-4">{{ __('Select Service Type') }}</h3>
                    <div class="service-type-buttons">
                        <button id="salonServiceBtn" class="btn btn--black hover--black service-type-btn me-2">{{ __('Salon Services') }}</button>
                        <button id="homeServiceBtn" class="btn btn--tra-black hover--black service-type-btn">{{ __('Home Services') }}</button>
                    </div>
                    <p class="mt-3 service-type-info">
                        <span id="salonInfo" class="service-info">{{ __('Select services to be performed at our salon') }}</span>
                        <span id="homeInfo" class="service-info d-none">{{ __('Select services to be performed at your home') }}</span>
                    </p>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Categories and Services -->
                <div class="col-lg-7">
                    <div class="mb-4">
                        <h3 class="h3-md">{{ __('Select Services & Products') }}</h3>
                    </div>

                    <!-- Search Bar -->
                    <div class="service-search-container mb-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="serviceSearch" class="form-control" placeholder="{{ __('Search services...') }}">
                            <button type="button" id="clearSearch" class="btn btn-outline-secondary d-none">×</button>
                        </div>
                    </div>

                    <!-- Categories Tabs -->
                    <ul class="nav nav-tabs category-tabs mb-4" id="categoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active"
                                    id="category-tab-all"
                                    data-bs-toggle="tab"
                                    data-bs-target="#category-content-all"
                                    type="button"
                                    role="tab"
                                    aria-controls="category-content-all"
                                    aria-selected="true">
                                {{ __('All') }}
                            </button>
                        </li>
                        @foreach($categories as $index => $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? '' : '' }}"
                                        id="category-tab-{{ $category->id }}"
                                        data-bs-toggle="tab"
                                        data-bs-target="#category-content-{{ $category->id }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="category-content-{{ $category->id }}"
                                        aria-selected="{{ $index === 0 ? 'false' : 'false' }}">
                                    {{ $category->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="categoryTabsContent">
                        <!-- All Services Tab -->
                        <div class="tab-pane fade show active"
                             id="category-content-all"
                             role="tabpanel"
                             aria-labelledby="category-tab-all">
                            <div class="service-list">
                                @foreach($categories as $category)
                                    @foreach($category->productAndServices as $service)
                                        <div class="service-list-item {{ $service->can_be_done_at_home ? 'home-available' : 'salon-only' }}"
                                             data-service-id="{{ $service->id }}"
                                             data-can-home="{{ $service->can_be_done_at_home ? 'true' : 'false' }}"
                                             data-price="{{ $service->price }}"
                                             data-price-home="{{ $service->price_home }}">

                                            <div class="service-list-content">
                                                <div class="service-image">
                                                    @if($service->image)
                                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                                                    @else
                                                        <img src="{{ asset('assets/images/service-placeholder.jpg') }}" alt="{{ $service->name }}">
                                                    @endif
                                                </div>
                                                <div class="service-info">
                                                    <h5 class="service-name">
                                                        {{ $service->name }}
                                                        <i class="fas fa-question-circle service-info-icon"
                                                           data-service-id="{{ $service->id }}"
                                                           data-service-name="{{ $service->name }}"
                                                           data-service-description="{{ $service->description }}"
                                                           data-service-image="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/images/service-placeholder.jpg') }}"
                                                           data-service-price="{{ $service->price }}"
                                                           data-service-price-home="{{ $service->price_home }}"
                                                           data-service-duration="{{ $service->duration_minutes }}"
                                                           data-service-category="{{ $category->name }}"
                                                           title="Click for details"></i>
                                                    </h5>
                                                    <div class="service-details">
                                                        <span class="service-price">{{ $service->price }} SAR</span>
                                                        @if($service->can_be_done_at_home)
                                                            <span class="home-service-price d-none">{{ $service->price_home }} SAR</span>
                                                        @endif
                                                        @if($service->duration_minutes)
                                                            <span class="duration"><i class="fas fa-clock"></i> {{ $service->duration_minutes }} min</span>
                                                        @endif
                                                        <span class="category-tag">{{ $category->name }}</span>
                                                    </div>
                                                </div>

                                                <div class="service-actions">
                                                    <div class="quantity-controls d-none">
                                                        <button type="button" class="btn-decrease">-</button>
                                                        <input type="number" class="quantity-input" value="0" min="0" max="10">
                                                        <button type="button" class="btn-increase">+</button>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-add-service">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        @foreach($categories as $index => $category)
                            <div class="tab-pane fade {{ $index === 0 ? '' : '' }}"
                                 id="category-content-{{ $category->id }}"
                                 role="tabpanel"
                                 aria-labelledby="category-tab-{{ $category->id }}">

                                @if(count($category->productAndServices) > 0)
                                    <div class="service-list">
                                        @foreach($category->productAndServices as $service)
                                            <div class="service-list-item {{ $service->can_be_done_at_home ? 'home-available' : 'salon-only' }}"
                                                 data-service-id="{{ $service->id }}"
                                                 data-can-home="{{ $service->can_be_done_at_home ? 'true' : 'false' }}"
                                                 data-price="{{ $service->price }}"
                                                 data-price-home="{{ $service->price_home }}">

                                                <div class="service-list-content">
                                                    <div class="service-image">
                                                        @if($service->image)
                                                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                                                        @else
                                                            <img src="{{ asset('assets/images/service-placeholder.jpg') }}" alt="{{ $service->name }}">
                                                        @endif
                                                    </div>
                                                    <div class="service-info">
                                                        <h5 class="service-name">
                                                            {{ $service->name }}
                                                            <i class="fas fa-question-circle service-info-icon"
                                                               data-service-id="{{ $service->id }}"
                                                               data-service-name="{{ $service->name }}"
                                                               data-service-description="{{ $service->description }}"
                                                               data-service-image="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/images/service-placeholder.jpg') }}"
                                                               data-service-price="{{ $service->price }}"
                                                               data-service-price-home="{{ $service->price_home }}"
                                                               data-service-duration="{{ $service->duration_minutes }}"
                                                               data-service-category="{{ $category->name }}"
                                                               title="Click for details"></i>
                                                        </h5>
                                                        <div class="service-details">
                                                            <span class="service-price">{{ $service->price }} SAR</span>
                                                            @if($service->can_be_done_at_home)
                                                                <span class="home-service-price d-none">{{ $service->price_home }} SAR</span>
                                                            @endif
                                                            @if($service->duration_minutes)
                                                                <span class="duration"><i class="fas fa-clock"></i> {{ $service->duration_minutes }} min</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="service-actions">
                                                        <div class="quantity-controls d-none">
                                                            <button type="button" class="btn-decrease">-</button>
                                                            <input type="number" class="quantity-input" value="0" min="0" max="10">
                                                            <button type="button" class="btn-increase">+</button>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-add-service">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>No services available in this category</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column - Invoice -->
                <div class="col-lg-5">
                    <div class="invoice-panel">
                        <div class="mb-4">
                            <h3 class="h3-md">{{ __('Your Booking') }}</h3>
                            <div class="service-type-badge mt-2 mb-3">
                                <span id="currentServiceType" class="badge bg-primary">{{ __('Salon Service') }}</span>
                            </div>
                        </div>

                        <!-- Selected Services -->
                        <div id="selectedServices" class="mb-4">
                            <h4 class="h4-xs mb-3">{{ __('Selected Services') }}</h4>
                            <div class="selected-services-list">
                                <!-- Selected services will be displayed here via jQuery -->
                                <div class="empty-cart-message">{{ __('No services selected yet') }}</div>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="invoice-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Subtotal:') }}</span>
                                <span id="subtotal">0.00 SAR</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('VAT (15%):') }}</span>
                                <span id="vat">0.00 SAR</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>{{ __('Total:') }}</span>
                                <span id="total">0.00 SAR</span>
                            </div>
                        </div>

                        <!-- Next Button -->
                        <div class="mt-4 text-center">
                            <button id="proceedToCheckout" class="btn btn--tra-black hover--black w-100">{{ __('Proceed to Checkout') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END BOOKING SERVICES SECTION -->




    <!-- GALLERY-3
                        ============================================= -->
    <section id="gallery-3" class="bg--stone py-8 gallery-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-title">
                        <h3 class="h3-lg color--black">{{ __('Follow:') }} <a href="#">@mcs.sa</a></h3>
                    </div>
                </div>
            </div>


            <!-- IMAGES WRAPPER -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6">


                <!-- IMAGE #1 -->
                <div class="col">
                    <div id="img-3-1" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/woman_08.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="image-link" href="/assets/images/gallery/woman_08.jpg">
                                        <span class="flaticon-visibility"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- IMAGE #2 -->
                <div class="col">
                    <div id="img-3-2" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/beauty_01.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data gallery-video">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="video-popup1" href="https://www.youtube.com/embed/SZEflIVnhH8">
                                        <span class="flaticon-play"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- IMAGE #3 -->
                <div class="col">
                    <div id="img-3-3" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/woman_07.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="image-link" href="/assets/images/gallery/woman_07.jpg">
                                        <span class="flaticon-visibility"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- IMAGE #4 -->
                <div class="col">
                    <div id="img-3-4" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/beauty_02.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="image-link" href="/assets/images/gallery/beauty_01.jpg">
                                        <span class="flaticon-visibility"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- IMAGE #5 -->
                <div class="col">
                    <div id="img-3-5" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/beauty_03.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data gallery-video">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="video-popup2" href="https://www.youtube.com/watch?v=7e90gBu4pas">
                                        <span class="flaticon-play"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- IMAGE #6 -->
                <div class="col">
                    <div id="img-3-6" class="gallery-image">
                        <div class="hover-overlay">

                            <!-- Image -->
                            <img class="img-fluid" src="/assets/images/gallery/woman_09.jpg" alt="gallery-image">
                            <div class="item-overlay"></div>

                            <!-- Image Zoom -->
                            <div class="image-data">
                                <div class="gallery-link ico-40 color--white">
                                    <a class="image-link" href="/assets/images/gallery/woman_09.jpg">
                                        <span class="flaticon-visibility"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <!-- END IMAGES WRAPPER -->


        </div>
        <!-- End container -->
    </section>
    <!-- END GALLERY-3 -->

    <!-- Single Service Details Modal -->
    <div id="serviceModal" class="modal modal_barber fade" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- CLOSE BUTTON -->
                <button type="button" class="modal-close color--black ico-20" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">
                    <span class="flaticon-246"></span>
                </button>

                <!-- MODAL CONTENT -->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <!-- SERVICE IMAGE -->
                            <div class="col-md-6 bg-img d-none d-sm-flex align-items-center justify-content-center">
                                <div id="serviceImage" class="w-100 h-100"></div>
                            </div>

                            <!-- SERVICE DETAILS -->
                            <div class="col-md-6">
                                <div class="modal-body-content">
                                    <!-- Title -->
                                    <div class="request-form-title">
                                        <h3 class="h3-md" id="serviceModalLabel"></h3>
                                        <h4 class="h4-md" id="serviceCategory"></h4>
                                    </div>

                                    <!-- Description -->
                                    <p id="serviceDescription" class="mb-4"></p>

                                    <!-- Pricing Details -->
                                    <div class="service-pricing shadow p-2 rounded mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-2"><strong>{{ __('Price at Salon:') }}</strong> <span id="servicePrice"></span> {{ __('SAR') }}</p>
                                                <p id="servicePriceHome" class="mb-2"></p>
                                                <p id="serviceDuration"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <div class="form-btn">
                                        <button type="button" id="modalAddToCart" class="btn btn--black hover--tra-black">{{ __('Add to Cart') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
<style>
    .service-list-item {
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 0;
        transition: all 0.3s ease;
    }

    .service-list-item:last-child {
        border-bottom: none;
    }

    .service-list-item:hover {
        background-color: #f9f9f9;
    }

    .service-list-item.search-hidden {
        display: none;
    }

    .service-search-container {
        position: relative;
    }

    #serviceSearch {
        padding-right: 35px;
    }

    #clearSearch {
        position: absolute;
        right: 0;
        z-index: 3;
        border: none;
        background: transparent;
        font-size: 1.2rem;
        line-height: 1;
        padding: 8px 12px;
    }

    .no-results-message {
        color: #6c757d;
        font-style: italic;
    }

    .service-list-content {
        display: flex;
        align-items: center;
    }

    .service-image {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        margin-right: 15px;
        border-radius: 6px;
        overflow: hidden;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-info {
        flex: 1;
    }

    .service-name {
        margin-bottom: 5px;
    }

    .modal_barber .modal-content {
            border: none;
            border-radius: 0;
            background: #fff;
        }

        .modal_barber .bg-img {
            background-size: cover;
            background-position: center;
            min-height: 500px;
            position: relative;
        }

        .modal_barber .bg-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }

        .modal_barber .bg-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal_barber .modal-body-content {
            padding: 3rem;
        }

        .modal_barber .request-form-title {
            margin-bottom: 2rem;
        }

        .modal_barber .request-form-title h3 {
            margin-bottom: 0.5rem;
        }

        .modal_barber .request-form-title h4 {
            color: var(--primary-color);
        }


        .modal_barber .form-btn {
            margin-top: 2rem;
        }

        .modal_barber .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1;
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
        }

    .service-info-icon {
        margin-left: 5px;
        color: #3a3a3a;
        font-size: 0.8rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .service-info-icon:hover {
        color: #000;
    }


    .service-details {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .service-price {
        font-weight: bold;
        color: #363636;
    }

    .home-service-price {
        color: #6f6f6f;
        font-size: 0.9rem;
    }

    .duration {
        color: #6f6f6f;
        font-size: 0.9rem;
    }

    .duration i {
        margin-right: 3px;
    }

    .service-actions {
        display: flex;
        align-items: center;
        min-width: 100px;
        justify-content: flex-end;
        margin-left: 10px;
    }

    #serviceImage {
        background-size: cover;
        background-position: center;
        min-height: 300px;
    }

    .service-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .service-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .quantity-controls button {
        width: 30px;
        height: 30px;
        background: #f0f0f0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .quantity-input {
        width: 40px;
        height: 30px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .invoice-panel {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        position: sticky;
        top: 20px;
    }

    .selected-services-list {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .selected-service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .empty-cart-message {
        color: #6f6f6f;
        text-align: center;
        padding: 20px;
    }

    .invoice-totals {
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 0;
        margin-bottom: 20px;
    }

    .remove-service {
        color: #cc0000;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 1.2rem;
    }

    .service-type-buttons {
        display: flex;
        justify-content: center;
    }

    .btn--black.active-btn {
        font-weight: bold;
        background-color: #000;
        color: #fff;
    }

    .btn--tra-black.active-btn {
        font-weight: bold;
        background-color: #000;
        color: #fff;
    }

    .service-type-badge {
        text-align: center;
    }

    .service-item.hidden {
        display: none;
    }

    /* Category Tabs Styling */
    .category-tabs {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 20px;
        padding-bottom: 5px;
    }

    .category-tabs .nav-item {
        margin-right: 5px;
    }

    .category-tabs .nav-link {
        padding: 8px 15px;
        border-radius: 20px;
        color: #6f6f6f;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .category-tabs .nav-link:hover {
        color: #363636;
        background-color: #f8f9fa;
    }

    .category-tabs .nav-link.active {
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    /* For mobile scrolling */
    @media (max-width: 768px) {
        .category-tabs {
            padding-bottom: 10px;
        }

        .category-tabs .nav-link {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
    }

    .category-tag {
        font-size: 0.8rem;
        background-color: #f0f0f0;
        color: #555;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize variables
        let selectedServices = [];
        let isHomeService = false;
        let currentServiceId = null;

        // Load saved data from localStorage if available
        function loadSavedData() {
            const savedData = localStorage.getItem('bookingData');
            if (savedData) {
                const data = JSON.parse(savedData);
                selectedServices = data.services || [];

                // If there are no services in the cart, default to salon service
                if (selectedServices.length === 0) {
                    isHomeService = false;
                } else {
                    isHomeService = data.isHomeService || false;
                }

                // Set the UI according to the saved data
                updateServiceTypeUI(isHomeService);
                restoreServiceStates();
                renderSelectedServices();
            } else {
                // Default to salon service UI
                isHomeService = false;
                updateServiceTypeUI(false);
            }
        }

        // Handle service info icon click
        $(document).on('click', '.service-info-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Get service info from data attributes
            const serviceId = $(this).data('service-id');
            const serviceName = $(this).data('service-name');
            const serviceDescription = $(this).data('service-description');
            const serviceImage = $(this).data('service-image');
            const servicePrice = $(this).data('service-price');
            const servicePriceHome = $(this).data('service-price-home');
            const serviceDuration = $(this).data('service-duration');
            const serviceCategory = $(this).data('service-category');

            // Set current service ID for the "Add to Cart" button
            currentServiceId = serviceId;

            // Populate modal with service data
            $('#serviceModalLabel').text(serviceName);
            $('#serviceCategory').text(serviceCategory);
            $('#serviceDescription').text(serviceDescription);
            $('#servicePrice').text(servicePrice);

            // Set home price if available
            if (servicePriceHome) {
                $('#servicePriceHome').html('<strong>{{ __('Price at Home:') }}</strong> ' + servicePriceHome + ' {{ __('SAR') }}');
            } else {
                $('#servicePriceHome').html('<em>{{ __('Not available for home service') }}</em>');
            }

            // Set duration if available
            if (serviceDuration) {
                $('#serviceDuration').html('<strong>{{ __('Duration:') }}</strong> ' + serviceDuration + ' {{ __('min') }}');
            } else {
                $('#serviceDuration').html('');
            }

            // Set image
            $('#serviceImage').css('background-image', 'url(' + serviceImage + ')');

            // Show modal
            $('#serviceModal').modal('show');
        });

        // Handle "Add to Cart" button in modal
        $('#modalAddToCart').click(function() {
            if (currentServiceId) {
                // Find the service item in the DOM
                const $serviceItem = $(`.service-list-item[data-service-id="${currentServiceId}"]`);

                // Check if the service can be added based on current service type
                const canBeHomeService = $serviceItem.data('can-home') === 'true';
                if (isHomeService && !canBeHomeService) {
                    alert('{{ __('This service is not available for home service.') }}');
                    return;
                }

                // Show quantity controls
                $serviceItem.find('.quantity-controls').removeClass('d-none');
                $serviceItem.find('.quantity-input').val(1);
                $serviceItem.find('.btn-add-service').addClass('d-none');

                // Add service with quantity 1
                addService(currentServiceId, 1);

                // Close modal
                $('#serviceModal').modal('hide');
            }
        });

        // Save current booking data to localStorage
        function saveToLocalStorage() {
            const bookingData = {
                services: selectedServices,
                isHomeService: isHomeService
            };
            localStorage.setItem('bookingData', JSON.stringify(bookingData));
        }

        // Update UI for the selected service type
        function updateServiceTypeUI(isHome) {
            if (isHome) {
                // Update UI for home service
                $('#homeServiceBtn').removeClass('btn--tra-black').addClass('btn--black active-btn');
                $('#salonServiceBtn').removeClass('btn--black active-btn').addClass('btn--tra-black');
                $('#homeInfo').removeClass('d-none');
                $('#salonInfo').addClass('d-none');
                $('#currentServiceType').text('{{ __('Home Service') }}');

                // Show only home-available services
                $('.service-list-item.salon-only').addClass('hidden');
                $('.service-list-item.home-available').removeClass('hidden');
                $('.service-price').addClass('d-none');
                $('.home-service-price').removeClass('d-none');
            } else {
                // Update UI for salon service
                $('#salonServiceBtn').removeClass('btn--tra-black').addClass('btn--black active-btn');
                $('#homeServiceBtn').removeClass('btn--black active-btn').addClass('btn--tra-black');
                $('#salonInfo').removeClass('d-none');
                $('#homeInfo').addClass('d-none');
                $('#currentServiceType').text('{{ __('Salon Service') }}');

                // Show all services
                $('.service-list-item').removeClass('hidden');
                $('.service-price').removeClass('d-none');
                $('.home-service-price').addClass('d-none');
            }
        }

        // Restore service states based on selected services
        function restoreServiceStates() {
            // First reset all services
            resetAllServices();

            // Then update services for selected services
            selectedServices.forEach(service => {
                const $serviceItem = $(`.service-list-item[data-service-id="${service.id}"]`);
                if ($serviceItem.length > 0) {
                    $serviceItem.find('.quantity-controls').removeClass('d-none');
                    $serviceItem.find('.quantity-input').val(service.quantity);
                    $serviceItem.find('.btn-add-service').addClass('d-none');
                }
            });
        }

        // Service type toggle - Salon
        $('#salonServiceBtn').click(function() {
            if (isHomeService) {
                if (selectedServices.length > 0) {
                    if (!confirm('{{ __('Changing service type will clear your current selections. Continue?') }}')) {
                        return;
                    }
                    // Clear selections
                    selectedServices = [];
                    renderSelectedServices();
                    resetAllServices();
                    saveToLocalStorage();
                }

                // Update to salon service UI
                isHomeService = false;
                updateServiceTypeUI(isHomeService);
                saveToLocalStorage();
            }
        });

        // Service type toggle - Home
        $('#homeServiceBtn').click(function() {
            if (!isHomeService) {
                if (selectedServices.length > 0) {
                    if (!confirm('{{ __('Changing service type will clear your current selections. Continue?') }}')) {
                        return;
                    }
                    // Clear selections
                    selectedServices = [];
                    renderSelectedServices();
                    resetAllServices();
                    saveToLocalStorage();
                }

                // Update to home service UI
                isHomeService = true;
                updateServiceTypeUI(isHomeService);
                saveToLocalStorage();
            }
        });

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;

            // Sum all selected services
            selectedServices.forEach(service => {
                const price = isHomeService ? service.price_home : service.price;
                subtotal += price * service.quantity;
            });

            const vat = subtotal * 0.15;
            const total = subtotal + vat;

            // Update the display
            $('#subtotal').text(subtotal.toFixed(2) + ' SAR');
            $('#vat').text(vat.toFixed(2) + ' SAR');
            $('#total').text(total.toFixed(2) + ' SAR');

            // Show/hide empty cart message
            if (selectedServices.length > 0) {
                $('.empty-cart-message').hide();
                $('#proceedToCheckout').prop('disabled', false);
            } else {
                $('.empty-cart-message').show();
                $('#proceedToCheckout').prop('disabled', true);
            }
        }

        // Render the selected services list
        function renderSelectedServices() {
            const $list = $('.selected-services-list');

            // Clear the current list (except the empty message)
            $list.find('.selected-service-item').remove();

            // Add each selected service
            selectedServices.forEach((service, index) => {
                const price = isHomeService ? service.price_home : service.price;
                const total = price * service.quantity;

                const serviceHtml = `
                    <div class="selected-service-item" data-index="${index}">
                        <div>
                            <div class="fw-bold">${service.name}</div>
                            <div class="small">${price.toFixed(2)} SAR x ${service.quantity}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="me-3">${total.toFixed(2)} SAR</span>
                            <button type="button" class="remove-service" data-index="${index}">&times;</button>
                        </div>
                    </div>
                `;

                $list.append(serviceHtml);
            });

            calculateTotals();
            saveToLocalStorage();
        }

        // Add a service to the invoice
        function addService(serviceId, quantity) {
            // Find the service in the DOM to get its data
            const $serviceItem = $(`.service-list-item[data-service-id="${serviceId}"]`);

            if ($serviceItem.length === 0) return;

            const serviceName = $serviceItem.find('.service-name').text();
            const servicePrice = parseFloat($serviceItem.data('price'));
            const servicePriceHome = parseFloat($serviceItem.data('price-home') || 0);
            const canBeHomeService = $serviceItem.data('can-home') === 'true';

            // Check if the service is already in the selected list
            const existingIndex = selectedServices.findIndex(s => s.id === serviceId);

            if (existingIndex >= 0) {
                // Update quantity
                selectedServices[existingIndex].quantity += quantity;
            } else {
                // Add new service
                selectedServices.push({
                    id: serviceId,
                    name: serviceName,
                    price: servicePrice,
                    price_home: servicePriceHome,
                    can_be_done_at_home: canBeHomeService,
                    quantity: quantity
                });
            }

            renderSelectedServices();
        }

        // Remove a service from the invoice
        function removeService(index) {
            selectedServices.splice(index, 1);
            renderSelectedServices();
        }

        // Reset all services
        function resetAllServices() {
            $('.service-list-item').each(function() {
                $(this).find('.quantity-controls').addClass('d-none');
                $(this).find('.quantity-input').val(0);
                $(this).find('.btn-add-service').removeClass('d-none');
            });
        }

        // Event: Add service button click
        $(document).on('click', '.btn-add-service', function() {
            const $serviceItem = $(this).closest('.service-list-item');
            const serviceId = $serviceItem.data('service-id');
            const canBeHomeService = $serviceItem.data('can-home') === 'true';

            // Skip the check for home service since we're already filtering which services are shown
            // Only visible services should be clickable

            // Show quantity controls
            $serviceItem.find('.quantity-controls').removeClass('d-none');
            $serviceItem.find('.quantity-input').val(1);
            $serviceItem.find('.btn-add-service').addClass('d-none');

            // Add service with quantity 1
            addService(serviceId, 1);
        });

        // Event: Quantity increase button
        $(document).on('click', '.btn-increase', function() {
            const $serviceItem = $(this).closest('.service-list-item');
            const serviceId = $serviceItem.data('service-id');
            const $input = $serviceItem.find('.quantity-input');
            const currentValue = parseInt($input.val());

            $input.val(currentValue + 1);
            addService(serviceId, 1);
        });

        // Event: Quantity decrease button
        $(document).on('click', '.btn-decrease', function() {
            const $serviceItem = $(this).closest('.service-list-item');
            const serviceId = $serviceItem.data('service-id');
            const $input = $serviceItem.find('.quantity-input');
            const currentValue = parseInt($input.val());

            if (currentValue > 0) {
                $input.val(currentValue - 1);

                // Find the service in the selected list
                const existingIndex = selectedServices.findIndex(s => s.id === serviceId);

                if (existingIndex >= 0) {
                    // Update quantity
                    selectedServices[existingIndex].quantity -= 1;

                    // If quantity is now 0, remove the service
                    if (selectedServices[existingIndex].quantity <= 0) {
                        selectedServices.splice(existingIndex, 1);

                        // Reset the UI
                        $serviceItem.find('.quantity-controls').addClass('d-none');
                        $serviceItem.find('.btn-add-service').removeClass('d-none');
                    }

                    renderSelectedServices();
                }
            }
        });

        // Event: Remove service button click
        $(document).on('click', '.remove-service', function() {
            const index = $(this).data('index');
            const serviceId = selectedServices[index].id;

            // Reset the UI for this service item
            const $serviceItem = $(`.service-list-item[data-service-id="${serviceId}"]`);
            if ($serviceItem.length > 0) {
                $serviceItem.find('.quantity-controls').addClass('d-none');
                $serviceItem.find('.quantity-input').val(0);
                $serviceItem.find('.btn-add-service').removeClass('d-none');
            }

            removeService(index);
        });

        // Event: Proceed to checkout
        $('#proceedToCheckout').click(function() {
            if (selectedServices.length === 0) {
                alert('{{ __('Please select at least one service before proceeding to checkout.') }}');
                return;
            }

            // Store booking data in session storage for checkout page
            const bookingData = {
                services: selectedServices,
                isHomeService: isHomeService,
                subtotal: parseFloat($('#subtotal').text()),
                vat: parseFloat($('#vat').text()),
                total: parseFloat($('#total').text())
            };

            sessionStorage.setItem('bookingData', JSON.stringify(bookingData));

            // Redirect to checkout page
            window.location.href = '/booking/checkout';
        });

        // Initialize
        $('#proceedToCheckout').prop('disabled', true);
        loadSavedData();

        // Service search functionality
        $('#serviceSearch').on('input', function() {
            const searchQuery = $(this).val().toLowerCase().trim();

            if (searchQuery.length > 0) {
                $('#clearSearch').removeClass('d-none');

                // Get the currently active tab
                const activeTabId = $('.tab-pane.active').attr('id');

                // If we're in the "All" tab, search across all services
                if (activeTabId === 'category-content-all') {
                    $('.service-list-item').each(function() {
                        const serviceName = $(this).find('.service-name').text().toLowerCase();
                        const categoryName = $(this).find('.category-tag').text().toLowerCase();

                        if (serviceName.includes(searchQuery) || categoryName.includes(searchQuery)) {
                            $(this).removeClass('search-hidden');
                        } else {
                            $(this).addClass('search-hidden');
                        }
                    });
                } else {
                    // For specific category tabs
                    $(`#${activeTabId} .service-list-item`).each(function() {
                        const serviceName = $(this).find('.service-name').text().toLowerCase();

                        if (serviceName.includes(searchQuery)) {
                            $(this).removeClass('search-hidden');
                        } else {
                            $(this).addClass('search-hidden');
                        }
                    });
                }

                // Show "no results" message if all items in the current tab are hidden
                const allHidden = $(`#${activeTabId} .service-list-item`).not('.search-hidden').length === 0;

                if (allHidden) {
                    if ($(`#${activeTabId} .no-results-message`).length === 0) {
                        $(`#${activeTabId} .service-list`).append('<p class="no-results-message text-center py-3">{{ __('No services found matching your search criteria.') }}</p>');
                    }
                } else {
                    $(`#${activeTabId} .no-results-message`).remove();
                }
            } else {
                // If search is empty, show all items and hide the clear button
                $('.service-list-item').removeClass('search-hidden');
                $('.no-results-message').remove();
                $('#clearSearch').addClass('d-none');
            }
        });

        // Clear search button
        $('#clearSearch').click(function() {
            $('#serviceSearch').val('').trigger('input');
        });

        // Handle tab change - clear search when changing tabs
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            $('#serviceSearch').val('').trigger('input');
        });
    });
</script>
@endsection
