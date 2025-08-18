<!-- PRICING-5
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
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
