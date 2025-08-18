<!-- PRICING SECTION
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<div class="{{ isset($paddingTop) ? $paddingTop : 'pt-8' }} pricing-5 pricing-section division">
    <div class="container">
        @if(isset($showSectionTitle) && $showSectionTitle)
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
        @endif

        <!-- SERVICE TYPE BUTTONS -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <div class="btn-group service-type-buttons" role="group">
                    <button type="button" class="btn btn-lg service-type-btn active" data-service-type="salon">{{ __('Salon Services Menu') }}</button>
                    <button type="button" class="btn btn-lg service-type-btn" data-service-type="home">{{ __('Home Services Menu') }}</button>
                </div>
            </div>
        </div>

        <!-- PRICING-5 WRAPPER -->
        <div class="pricing-5-wrapper">
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
                                        <div class="detail-price">
                                            <div class="price-name">
                                                <p>
                                                    {{ $service->name }}
                                                    <span class="salon-availability" style="display: inline;">
                                                        @if($service->is_available_at_salon)
                                                            <i class="fas fa-question-circle service-info-icon"
                                                                data-service-id="{{ $service->id }}"
                                                                data-service-name="{{ $service->name }}"
                                                                data-service-description="{{ $service->description }}"
                                                                data-service-image="{{ asset('storage/' . $service->image) }}"
                                                                data-service-price="{{ $service->price }}"
                                                                data-service-price-home="{{ $service->price_home }}"
                                                                data-service-duration="{{ $service->duration_minutes }}"
                                                                data-service-category="{{ $category->name }}"
                                                                data-service-location-type="salon"
                                                                title="{{ __('Click for details') }}"></i>
                                                        @else
                                                            <small class="coming-soon-text">{{ __('coming soon') }}</small>
                                                        @endif
                                                    </span>
                                                    <span class="home-availability" style="display: none;">
                                                        @if($service->is_available_at_home)
                                                            <i class="fas fa-question-circle service-info-icon"
                                                                data-service-id="{{ $service->id }}"
                                                                data-service-name="{{ $service->name }}"
                                                                data-service-description="{{ $service->description }}"
                                                                data-service-image="{{ asset('storage/' . $service->image) }}"
                                                                data-service-price="{{ $service->price }}"
                                                                data-service-price-home="{{ $service->price_home }}"
                                                                data-service-duration="{{ $service->duration_minutes }}"
                                                                data-service-category="{{ $category->name }}"
                                                                data-service-location-type="home"
                                                                title="{{ __('Click for details') }}"></i>
                                                        @else
                                                            <small class="coming-soon-text">{{ __('coming soon') }}</small>
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="price-dots"></div>
                                            <div class="price-number">
                                                <p class="salon-price"><span class="icon-saudi_riyal"></span> {{ $service->price }}</p>
                                                @if ($service->price_home)
                                                    <p class="home-price" style="display: none;"><span class="icon-saudi_riyal"></span> {{ $service->price_home }}</p>
                                                @else
                                                    <p class="home-price" style="display: none;"><span class="icon-saudi_riyal"></span> {{ __('Not Available') }}</p>
                                                @endif
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
                    <a href="{{ isset($buttonUrl) ? $buttonUrl : (isset($pricingContent) && isset($pricingContent['buttonUrl']) ? $pricingContent['buttonUrl'] : route('menu')) }}"
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
    @include('components.service-detail-modal')

<!-- JavaScript for service type toggling -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceButtons = document.querySelectorAll('.service-type-btn');

        serviceButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                serviceButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                const serviceType = this.getAttribute('data-service-type');

                // Show/hide prices based on service type
                if (serviceType === 'salon') {
                    document.querySelectorAll('.salon-price').forEach(price => price.style.display = 'block');
                    document.querySelectorAll('.home-price').forEach(price => price.style.display = 'none');
                    document.querySelectorAll('.salon-availability').forEach(item => item.style.display = 'inline');
                    document.querySelectorAll('.home-availability').forEach(item => item.style.display = 'none');
                } else {
                    document.querySelectorAll('.salon-price').forEach(price => price.style.display = 'none');
                    document.querySelectorAll('.home-price').forEach(price => price.style.display = 'block');
                    document.querySelectorAll('.salon-availability').forEach(item => item.style.display = 'none');
                    document.querySelectorAll('.home-availability').forEach(item => item.style.display = 'inline');
                }
            });
        });
    });
</script>

<style>
    .service-type-buttons {
        margin-bottom: 25px;
    }

    .service-type-btn {
        padding: 10px 20px;
        margin: 0 5px;
        background-color: #ffffff;
        border: 1px solid #333;
        transition: all 0.3s ease;
    }

    .service-type-btn:hover {
        background-color: #333;
        color: white;
        border-color: #333;
    }

    .service-type-btn.active {
        background-color: #333;
        color: white;
        border-color: #333;
    }

    .coming-soon-text {
        color: #888;
        font-style: italic;
        font-size: 0.85em;
        margin-left: 5px;
    }
</style>
