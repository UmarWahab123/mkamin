<!-- Home Page Hero Section - DYNAMIC WIDGETS
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
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
                                            {{-- <div class="text">
                                                <a href="{{ $slide['buttonUrl'] }}" class="custom-btn"
                                                    style="background-color: {{ $slide['buttonBgColor'] }}; color: {{ $slide['buttonTextColor'] }}; border-color: {{ $slide['buttonBgColor'] }};">
                                                    {{ __($slide['buttonText']) }}
                                                </a>
                                            </div> --}}
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
