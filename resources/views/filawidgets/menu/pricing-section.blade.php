<!-- PRICING-5
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<div class="pt-8 pricing-5 pricing-section division">
    <div class="container">

        <!-- PRICING-5 WRAPPER -->
        <div class="pricing-5-wrapper">
            <div class="row">
                @foreach ($serviceCategories as $category)
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
                                                    <i class="fas fa-question-circle service-info-icon"
                                                        data-service-id="{{ $service->id }}"
                                                        data-service-name="{{ $service->name }}"
                                                        data-service-description="{{ $service->description }}"
                                                        data-service-image="{{ asset('storage/' . $service->image) }}"
                                                        data-service-price="{{ $service->price }}"
                                                        data-service-price-home="{{ $service->price_home }}"
                                                        data-service-duration="{{ $service->duration_minutes }}"
                                                        data-service-category="{{ $category->name }}"
                                                        title="{{ __('Click for details') }}"></i>
                                                </p>
                                            </div>
                                            <div class="price-dots"></div>
                                            <div class="price-number">
                                                @if ($service->price_home)
                                                    <p>${{ $service->price }} - ${{ $service->price_home }}</p>
                                                @else
                                                    <p>${{ $service->price }}</p>
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
        <!--END  PRICING-1 WRAPPER -->

        <!-- BUTTON -->
        <div class="row">
            <div class="col">
                <div class="more-btn mt-5">
                    <a href="{{ $pricingContent['buttonUrl'] }}" class="btn btn--tra-black hover--black">{{ __($pricingContent['buttonText']) }}</a>
                </div>
            </div>
        </div>
        <!-- END BUTTON -->

    </div>
    <!-- End container -->
</div>
<!-- PRICING-5  -->
