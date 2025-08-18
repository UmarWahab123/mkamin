@props(['service', 'type' => 'salon'])

<div class="service-card h-100" data-price="{{ $service->sale_price_at_saloon }}" data-price-home="{{ $service->sale_price_at_home }}"
    data-duration="{{ $service->duration_minutes }}"
    data-category="{{ isset($service->category) ? $service->category->name : (isset($categories) && $service->category_id ? $categories->where('id', $service->category_id)->first()->name : '') }}">
    <a href="{{ route('services.detail', ['id' => $service->id, 'type' => $type]) }}"
        class="service-image d-block">
        @if ($service->image)
            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-fluid">
        @else
            <img src="{{ asset('assets/images/service-placeholder.jpg') }}" alt="{{ $service->name }}"
                class="img-fluid">
        @endif
    </a>
    <div class="service-details p-3">
        <a href="{{ route('services.detail', ['id' => $service->id, 'type' => $type]) }}">
            <h5 class="service-name">{{ $service->name }}</h5>
        </a>

        @if ($type === null && $service->can_be_done_at_home)
            <div class="service-location-select mb-3">
                <label for="service-location-{{ $service->id }}" class="form-label">{{ __('Service Location') }}</label>
                <select id="service-location-{{ $service->id }}" class="form-select service-location-selector"
                    data-service-id="{{ $service->id }}">
                    <option value=""> {{ __('Select Location') }} </option>
                    <option value="salon" {{ !$service->can_be_done_at_home ? 'selected' : '' }}>{{ __('Salon') }}</option>
                    <option value="home" {{ $service->can_be_done_at_home ? '' : 'disabled' }}>{{ __('Home') }}</option>
                </select>
            </div>
        @endif

        <div class="service-info mb-3">
            @if ($service->can_be_done_at_home)
                @if ($type === 'home')
                    <div class="service-price">
                        <span>{{ $service->sale_price_at_home }} <span class="icon-saudi_riyal"></span></span>
                        <small class="text-muted d-block">{{ __('Home service') }}</small>
                    </div>
                @elseif ($type === 'salon')
                    <div class="service-price">
                        <span>{{ $service->sale_price_at_saloon }} <span class="icon-saudi_riyal"></span></span>
                        <small class="text-muted d-block">{{ __('Salon service') }}</small>
                    </div>
                @else
                    <div class="service-price-range">
                        <span class="price-range">{{ $service->sale_price_at_saloon }} -
                            {{ $service->sale_price_at_home }} <span class="icon-saudi_riyal"></span></span>
                        <small class="text-muted d-block">{{ __('Salon - Home') }}</small>
                    </div>
                @endif
            @else
                <div class="service-price">
                    <span>{{ $service->sale_price_at_saloon }} <span class="icon-saudi_riyal"></span></span>
                    <small class="text-muted d-block">{{ __('Salon only') }}</small>
                </div>
            @endif

            @if (isset($service->duration_minutes) && $service->duration_minutes)
                <div class="service-duration">
                    <i class="fas fa-clock"></i> {{ $service->duration_minutes }} {{ __('min') }}
                </div>
            @endif

            @if (isset($service->category) && $service->category)
                <span class="category-badge">{{ $service->category->name }}</span>
            @elseif (isset($categories) && $service->category_id)
                <span
                    class="category-badge">{{ $categories->where('id', $service->category_id)->first()->name }}</span>
            @endif
        </div>
        <div class="d-flex justify-content-center align-items-stretch">
            {{-- <a href="/services/{{ $service->id }}"
                class="btn btn-sm btn--tra-black hover--black p-2 py-3 rounded col-5 d-flex justify-content-center align-items-center">
                {{ __('Details') }}
            </a> --}}
            <button type="button"
                class="btn btn-sm btn--black hover--black btn-add-to-cart p-2 py-3 rounded d-flex justify-content-center align-items-center"
                data-service-id="{{ $service->id }}"
                data-location-type="{{ $service->can_be_done_at_home ? $type : 'salon' }}">{{ __('Add to Cart') }}</button>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .service-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .service-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .service-image {
            height: 200px;
            overflow: hidden;
        }

        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.05);
        }

        .service-details {
            background-color: #fff;
        }

        .service-name {
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.3;
            height: 2.6em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .service-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .service-price-range,
        .service-price {
            font-weight: 600;
        }

        .service-duration {
            color: #6c757d;
            font-size: 0.9rem;
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
            align-self: flex-start;
        }

        .service-location-select {
            margin-bottom: 10px;
        }
    </style>
@endpush

@once
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Handle "Add to Cart" button click - use event delegation to ensure it works with dynamically updated attributes
                $(document).on('click', '.btn-add-to-cart', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get the current values at the time of click
                    const serviceId = $(this).data('service-id');
                    const type = $(this).attr('data-location-type');
                    const serviceCard = $(this).closest('.service-card');
                    const locationSelector = serviceCard.find('.service-location-selector');

                    // If location selector exists and no location is selected
                    if (locationSelector.length && !type) {
                        // Focus on the location selector
                        locationSelector.focus();
                        // Show alert only once
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

                // Handle service location selector change
                $(document).on('change', '.service-location-selector', function() {
                    const serviceId = $(this).data('service-id');
                    const locationType = $(this).val();
                    const serviceCard = $(this).closest('.service-card');
                    const addToCartBtn = serviceCard.find('.btn-add-to-cart');
                    const serviceInfo = serviceCard.find('.service-info');

                    // Update the data-location-type attribute on the Add to Cart button
                    addToCartBtn.attr('data-location-type', locationType);

                    // Get the price data from the service card
                    const price = serviceCard.data('price');
                    const priceHome = serviceCard.data('price-home');
                    const duration = serviceCard.data('duration');
                    const category = serviceCard.data('category');

                    // Update the price display based on the selected location
                    if (locationType === 'salon') {
                        serviceInfo.html(`
                        <div class="service-price">
                            <span>${price} <span class="icon-saudi_riyal"></span></span>
                            <small class="text-muted d-block">{{ __('Salon service') }}</small>
                        </div>
                        ${duration ? `
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> ${duration} {{ __('min') }}
                            </div>
                            ` : ''}
                        ${category ? `
                            <span class="category-badge">${category}</span>
                            ` : ''}
                    `);
                    } else if (locationType === 'home') {
                        serviceInfo.html(`
                        <div class="service-price">
                            <span>${priceHome} <span class="icon-saudi_riyal"></span></span>
                            <small class="text-muted d-block">{{ __('Home service') }}</small>
                        </div>
                        ${duration ? `
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> ${duration} {{ __('min') }}
                            </div>
                            ` : ''}
                        ${category ? `
                            <span class="category-badge">${category}</span>
                            ` : ''}
                    `);
                    } else {
                        // Default view with price range
                        serviceInfo.html(`
                        <div class="service-price-range">
                            <span class="price-range">${price} -
                                ${priceHome} <span class="icon-saudi_riyal"></span></span>
                            <small class="text-muted d-block">{{ __('Salon - Home') }}</small>
                        </div>
                        ${duration ? `
                            <div class="service-duration">
                                <i class="fas fa-clock"></i> ${duration} {{ __('min') }}
                            </div>
                            ` : ''}
                        ${category ? `
                            <span class="category-badge">${category}</span>
                            ` : ''}
                    `);
                    }
                });
            });
        </script>
    @endpush
@endonce
