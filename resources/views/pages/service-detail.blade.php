@extends('layouts.app')

@section('title', $service->name . ' - ' . __('mcs.sa Salon'))

@section('content')
    <!-- SERVICE DETAIL SECTION -->
    <div id="service-detail" class="pt-8 pb-7 division">
        <div class="container">
            <div class="row">
                <!-- Left Column - Service Details -->
                <div class="col-lg-8">
                    <!-- Service Main Content -->
                    <div class="service-detail-main my-5">
                        <div class="row">
                            <!-- Service Image -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="service-image-container">
                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                            class="img-fluid rounded">
                                    @else
                                        <img src="{{ asset('assets/images/service-placeholder.jpg') }}"
                                            alt="{{ $service->name }}" class="img-fluid rounded">
                                    @endif
                                </div>
                            </div>

                            <!-- Service Info -->
                            <div class="col-md-6">
                                <div class="service-info">
                                    <h3 class="h3-md mb-3">{{ $service->name }}</h3>

                                    <div class="service-category mb-3">
                                        <span class="category-badge">{{ $category->name }}</span>
                                    </div>
                                    @if ($service->can_be_done_at_home)
                                        <div class="service-location-select mb-3">
                                            <label for="service-location-{{ $service->id }}"
                                                class="form-label">{{ __('Service Location') }}</label>
                                            <select id="service-location-{{ $service->id }}"
                                                class="form-select service-detail-location-selector"
                                                data-service-id="{{ $service->id }}">
                                                <option value=""> {{ __('Select Location') }} </option>
                                                <option value="salon"
                                                    {{ ($locationType == 'salon') ? 'selected' : '' }}>
                                                    {{ __('Salon') }}</option>
                                                <option value="home"
                                                    {{ $service->can_be_done_at_home ? ( ($service->isAvailableForBooking('home') ? ($locationType == 'home' ? 'selected' : '') : 'disabled')) : 'disabled' }}>
                                                    {{ __('Home') }}</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="service-pricing mb-4">
                                        @if ($locationType == 'home')
                                            <div class="price-range">
                                                <h4 class="h4-md">{{ $service->sale_price_at_home }}
                                                    <span class="icon-saudi_riyal"></span>
                                                </h4>
                                                <p class="price-note mb-0">{{ __('Home service only') }}</p>
                                            </div>
                                        @else
                                            <div class="single-price">
                                                <h4 class="h4-md">{{ $service->sale_price_at_saloon }} <span class="icon-saudi_riyal"></span></h4>
                                                <p class="price-note mb-0">{{ __('Salon service only') }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($service->duration_minutes)
                                        <div class="service-duration mb-4">
                                            <p class="mb-0"><i class="fas fa-clock me-2"></i> {{ __('Duration') }} {{ $service->duration_minutes }} {{ __('min') }}</p>
                                        </div>
                                    @endif

                                    <div class="service-actions">
                                        <button type="button" class="btn btn--black hover--black w-100" id="addToCart"
                                            data-service-id="{{ $service->id }}"
                                            data-location-type="{{ $service->can_be_done_at_home ? $locationType : 'salon' }}">
                                            {{ __('Add to Cart') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Description -->
                        <div class="service-description mt-5">
                            <h4 class="h4-md mb-3">{{ __('Description') }}</h4>
                            <div class="description-content">
                                @if ($service->description)
                                    <p>{!! $service->description !!}</p>
                                @else
                                    <p>{{ __('No description available for this service.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Related Services (Same Category) -->
                    @if ($relatedServices->count() > 0)
                        <div class="related-services mt-5">
                            <h4 class="h4-md mb-4">{{ __('More') }} {{ $category->name }}</h4>

                            <div class="row">
                                @foreach ($relatedServices as $relatedService)
                                    <div class="col-md-6 col-lg-4 mb-4 service-item"
                                        data-service-id="{{ $relatedService->id }}"
                                        data-category-id="{{ $relatedService->category_id }}"
                                        data-can-home="{{ $relatedService->can_be_done_at_home ? 'true' : 'false' }}">
                                        <x-service-card :service="$relatedService" :type="$locationType" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Sidebar -->
                <div class="col-lg-4">
                    <!-- Categories Sidebar -->
                    <div class="service-sidebar mb-5">
                        <h4 class="h4-md mb-3">{{ __('Categories') }}</h4>
                        <div class="categories-list">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="{{ route($locationType.'-services') }}">
                                        {{ __('All Categories') }}
                                    </a>
                                </li>
                                @foreach ($categories as $cat)
                                    <li class="list-group-item {{ $cat->id == $category->id ? 'active' : '' }}">
                                        <a href="{{ route($locationType.'-services', ['category' => $cat->id]) }}">
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- You May Also Like -->
                    @if ($otherServices->count() > 0)
                        <div class="you-may-like">
                            <h4 class="h4-md mb-3">{{ __('You May Also Like') }}</h4>

                            @foreach ($otherServices->take(4) as $otherService)
                                <div class="recommended-service mb-3">
                                    <div class="d-flex">
                                        <div class="recommended-service-image me-3">
                                            @if ($otherService->image)
                                                <img src="{{ asset('storage/' . $otherService->image) }}"
                                                    alt="{{ $otherService->name }}" class="img-fluid rounded">
                                            @else
                                                <img src="{{ asset('assets/images/service-placeholder.jpg') }}"
                                                    alt="{{ $otherService->name }}" class="img-fluid rounded">
                                            @endif
                                        </div>
                                        <div class="recommended-service-info">
                                            <h6 class="mb-1">{{ $otherService->name }}</h6>
                                            <div class="recommended-service-price mb-1">
                                                @if ($locationType == 'home')
                                                    <span>{{ $otherService->sale_price_at_home }}
                                                        <span class="icon-saudi_riyal"></span></span>
                                                @else
                                                    <span>{{ $otherService->sale_price_at_saloon }} <span class="icon-saudi_riyal"></span></span>
                                                @endif
                                            </div>
                                            <a href="{{ route('services.detail', ['id' => $otherService->id, 'type' => $locationType]) }}"
                                                class="btn-link">{{ __('View Details') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!-- END SERVICE DETAIL SECTION -->

    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection

@section('styles')
    <style>
        /* Service Image */
        .service-image-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-image-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Service Info */
        .service-info {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .service-actions {
            margin-top: auto;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75em;
            font-weight: 600;
            line-height: 1;
            color: #fff;
            background-color: #212529;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .price-note {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Service Description */
        .service-description {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        /* Sidebar */
        .service-sidebar {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .list-group-item {
            border-radius: 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .list-group-item a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
        }

        .list-group-item:hover {
            background-color: #f0f0f0;
        }

        .list-group-item.active {
            background-color: #212529;
            border-color: #212529;
        }

        /* Recommended Services */
        .recommended-service-image {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
        }

        .recommended-service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recommended-service-info {
            flex: 1;
        }

        .btn-link {
            color: #000;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .service-image-container {
                margin-bottom: 20px;
            }

            .service-info {
                margin-bottom: 20px;
            }
        }
    </style>
@endsection

@section('scripts')
    <!-- Include notification component -->
    @include('components.notification')
    <script>
        $(document).ready(function() {
            // Handle service location selector change
            $('.service-detail-location-selector').on('change', function() {
                const serviceId = $(this).data('service-id');
                const locationType = $(this).val();
                const addToCartBtn = $('#addToCart');
                const servicePricing = $('.service-pricing');
                const price = {{ $service->sale_price_at_saloon }};
                const priceHome = {{ $service->sale_price_at_home ?? 0 }};
                const duration = {{ $service->duration_minutes ?? 0 }};
                const category = "{{ $category->name }}";

                // Update the data-location-type attribute on the Add to Cart button
                addToCartBtn.attr('data-location-type', locationType);

                // Update the price display based on the selected location
                if (locationType === 'salon') {
                    servicePricing.html(`
                        <div class="single-price">
                            <h4 class="h4-md">${price} <span class="icon-saudi_riyal"></span></h4>
                            <p class="price-note mb-0">{{ __('Salon service only') }}</p>
                        </div>
                    `);
                } else if (locationType === 'home') {
                    servicePricing.html(`
                        <div class="single-price">
                            <h4 class="h4-md">${priceHome} <span class="icon-saudi_riyal"></span></h4>
                            <p class="price-note mb-0">{{ __('Home service only') }}</p>
                        </div>
                    `);
                } else {
                    // Default view with price range
                    servicePricing.html(`
                        <div class="price-range">
                            <h4 class="h4-md">${price} - ${priceHome} <span class="icon-saudi_riyal"></span></h4>
                            <p class="price-note mb-0">{{ __('Salon price - Home service price') }}</p>
                        </div>
                    `);
                }
            });

            // Handle Add to Cart button click
            $('#addToCart').click(function(e) {
                e.preventDefault();
                const serviceId = $(this).data('service-id');
                const type = $(this).attr('data-location-type');
                const locationSelector = $('.service-detail-location-selector');

                // If location selector exists and no location is selected
                if (locationSelector.length && !type) {
                    // Focus on the location selector
                    locationSelector.focus();
                    // Show alert
                    window.displayNotification('error', '{{ __("Please select a service location before adding to cart") }}', 'topRight', 3);
                    return;
                }

                // Use CartManager to add the service to cart
                CartManager.addToCart(serviceId, 1, type)
                    .then(cart => {
                        console.log('{{ __("Service added to cart successfully") }}', cart);
                    })
                    .catch(error => {
                        console.error('{{ __("Error adding service to cart:") }}', error);
                        window.displayNotification('error', '{{ __("Failed to add service to cart") }}', 'topRight', 3);
                    });
            });
        });
    </script>
@endsection
