<!-- Single Service Details Modal -->
<div id="serviceModal" class="modal modal_barber fade" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <!-- CLOSE BUTTON -->
            <button type="button" class="modal-close color--black ico-20" data-bs-dismiss="modal" aria-label="Close">
                <span class="flaticon-246"></span>
            </button>

            <!-- MODAL CONTENT -->
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <!-- SERVICE IMAGE -->
                        <div class="col-md-6 service-image-container">
                            <div id="serviceImage" class="w-100 h-100"></div>
                        </div>

                        <!-- SERVICE DETAILS -->
                        <div class="col-md-6">
                            <div class="modal-body-content">
                                <!-- Title -->
                                <div class="service-title mb-3">
                                    <h3 class="h3-md" id="serviceModalLabel"></h3>
                                    <span class="category-badge" id="serviceCategory"></span>
                                </div>


                                <!-- Service Location Selector (if service can be done at home and locationType is not specified) -->
                                @if(!isset($locationType))
                                <div id="serviceLocationContainer" class="service-location-select mb-3" style="display: none;">
                                    <label for="modal-service-location" class="form-label">{{ __('Service Location') }}</label>
                                    <select id="modal-service-location" class="form-select service-location-selector">
                                        <option value=""> {{ __('Select Location') }} </option>
                                        <option value="salon">{{ __('Salon') }}</option>
                                        <option value="home">{{ __('Home') }}</option>
                                    </select>
                                </div>
                                @else
                                <div id="serviceLocationInfo" class="service-location-info mb-3">
                                    <p><strong>{{ __('Service Location') }}:</strong> {{ $locationType === 'salon' ? __('Salon') : __('Home') }}</p>
                                </div>
                                @endif

                                <!-- Pricing Details -->
                                <div class="service-pricing mb-4">
                                    <div class="price-info">
                                        @if(!isset($locationType) || $locationType === 'salon')
                                        <p class="mb-2"><strong>{{ __('Price at Salon') }}</strong> <span class="icon-saudi_riyal"></span>
                                             <span id="servicePrice"></span></p>
                                        @endif
                                        @if(!isset($locationType) || $locationType === 'home')
                                        <p id="servicePriceHome" class="mb-2"></p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="service-duration mb-4">
                                    <p id="serviceDuration"></p>
                                </div>

                                <!-- Book Button -->
                                <div class="service-actions mb-4">
                                    <button type="button" class="btn btn--black hover--black btn-add-to-cart w-100"
                                        id="modalAddToCart" data-service-id="" data-location-type="{{ $locationType ?? '' }}">
                                        {{ __('Add to Cart') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Description - Full Width Below -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="service-description">
                                <h4 class="h4-md mb-3">{{ __('Description') }}</h4>
                                <div id="serviceDescription" class="description-content"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal_barber .modal-content {
        border: none;
        border-radius: 0;
        background: #fff;
    }

    .service-image-container {
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .service-image-container img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .modal_barber .modal-body-content {
        padding: 1.5rem;
    }

    .category-badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.75em;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        background-color: var(--primary-color);
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        margin-left: 0.5rem;
    }

    .service-description {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .modal_barber .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1050;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
    }

    .service-info-icon {
        cursor: pointer;
        font-size: 0.9em;
        margin-left: 0.5rem;
        color: var(--primary-color);
    }
</style>

<!-- Include notification component -->
@include('components.notification')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the modal
        const serviceModalElement = document.getElementById('serviceModal');
        const serviceModal = new bootstrap.Modal(serviceModalElement);
        serviceModal.hide();

        // Service data for the currently displayed service
        let currentService = {
            id: null,
            name: '',
            price: 0,
            price_home: 0,
            image: '',
            description: '',
            duration: 0,
            category: '',
            can_be_done_at_home: false
        };

        // Check if locationType is predefined
        let predefinedLocationType = '{{ $locationType ?? "" }}';

        // Make sure the close button works properly
        document.querySelector('.modal-close').addEventListener('click', function() {
            serviceModal.hide();
        });

        // Also handle the modal hidden event
        serviceModalElement.addEventListener('hidden.bs.modal', function () {
            // Reset service data when modal is closed
            currentService = {
                id: null,
                name: '',
                price: 0,
                price_home: 0,
                image: '',
                description: '',
                duration: 0,
                category: '',
                can_be_done_at_home: false
            };
        });

        // Attach event listeners to service info icons
        document.querySelectorAll('.service-info-icon').forEach(function(icon) {
            icon.addEventListener('click', function() {

                // Get service data from data attributes
                currentService.id = this.dataset.serviceId;
                currentService.name = this.dataset.serviceName;
                currentService.description = this.dataset.serviceDescription;
                currentService.image = this.dataset.serviceImage;
                currentService.price = this.dataset.servicePrice;
                currentService.price_home = this.dataset.servicePriceHome;
                currentService.duration = this.dataset.serviceDuration;
                currentService.category = this.dataset.serviceCategory;
                currentService.location_type = this.dataset.serviceLocationType;
                currentService.can_be_done_at_home = this.dataset.servicePriceHome ? true : false;
                predefinedLocationType = currentService.location_type;
                document.getElementById('modalAddToCart').setAttribute('data-location-type', predefinedLocationType);

                // Set modal title and category
                document.getElementById('serviceModalLabel').textContent = currentService.name;
                document.getElementById('serviceCategory').textContent = currentService.category;

                // Set image if available
                const imageContainer = document.getElementById('serviceImage');
                if (currentService.image) {
                    imageContainer.innerHTML = `<img src="${currentService.image}" alt="${currentService.name}">`;
                } else {
                    imageContainer.innerHTML = '';
                }

                // Set description
                document.getElementById('serviceDescription').innerHTML = currentService.description;

                // Set price based on location type
                if (!predefinedLocationType || predefinedLocationType === 'salon') {
                    const priceElement = document.getElementById('servicePrice');
                    if (priceElement) {
                        priceElement.textContent = currentService.price;
                    }
                }

                // Set home price if available and if showing home prices
                if (!predefinedLocationType || predefinedLocationType === 'home') {
                    const priceHomeElement = document.getElementById('servicePriceHome');
                    if (priceHomeElement && currentService.price_home) {
                        priceHomeElement.innerHTML = `<strong>{{ __('Price at Home') }}</strong> <span class="icon-saudi_riyal"></span>
                        ${currentService.price_home}`;
                    } else if (priceHomeElement) {
                        priceHomeElement.innerHTML = '';
                    }
                }

                // Set duration if available
                const durationElement = document.getElementById('serviceDuration');
                if (currentService.duration) {
                    durationElement.innerHTML = `<strong><i class="fas fa-clock me-2"></i> {{ __('Duration') }}</strong> ${currentService.duration} {{ __('min') }}`;
                } else {
                    durationElement.innerHTML = '';
                }

                // Only handle location selector if no predefined location
                if (!predefinedLocationType) {
                    // Show/hide location selector based on whether service can be done at home
                    const locationContainer = document.getElementById('serviceLocationContainer');
                    if (locationContainer && currentService.can_be_done_at_home) {
                        locationContainer.style.display = 'block';
                        // Reset location selector
                        document.getElementById('modal-service-location').value = '';
                        // Set default location type to empty
                        document.getElementById('modalAddToCart').setAttribute('data-location-type', '');
                    } else if (locationContainer) {
                        locationContainer.style.display = 'none';
                        // Set default location type to salon
                        document.getElementById('modalAddToCart').setAttribute('data-location-type', 'salon');
                    }
                }

                // Set service ID on the Add to Cart button
                document.getElementById('modalAddToCart').setAttribute('data-service-id', currentService.id);

                // Show the modal
                serviceModal.show();
            });
        });

        // Handle service location selector change (only if not using predefined location)
        if (!predefinedLocationType) {
            const locationSelector = document.getElementById('modal-service-location');
            if (locationSelector) {
                locationSelector.addEventListener('change', function() {
                    const locationType = this.value;
                    const addToCartBtn = document.getElementById('modalAddToCart');

                    // Update the data-location-type attribute on the Add to Cart button
                    addToCartBtn.setAttribute('data-location-type', locationType);
                });
            }
        }

        // Add to Cart functionality using CartManager
        document.getElementById('modalAddToCart').addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const type = this.getAttribute('data-location-type');

            // Only check for location selection if not using predefined location
            if (!predefinedLocationType) {
                const locationSelector = document.getElementById('modal-service-location');
                // If location selector exists and no location is selected
                if (locationSelector && !type) {
                    // Focus on the location selector
                    locationSelector.focus();
                    // Show alert
                    window.displayNotification('error', '{{ __("Please select a service location before adding to cart") }}', 'topRight', 3);
                    return;
                }
            }

            // Use CartManager to add the service to cart
            CartManager.addToCart(serviceId, 1, type)
                .then(cart => {
                    // Close the modal after adding to cart
                    serviceModal.hide();
                })
                .catch(error => {
                    console.error('{{ __("Error adding service to cart:") }}', error);
                    window.displayNotification('error', '{{ __("Failed to add service to cart") }}', 'topRight', 3);
                });
        });
    });
</script>
