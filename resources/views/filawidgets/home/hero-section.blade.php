{{-- resources/views/filawidgets/home/hero-section.blade.php --}}
@php
use App\Helpers\WidgetHelper;
@endphp

<section id="hero-12" class="hero-section">
    <div class="slideshow">
        <div class="slideshow-inner">
            <!-- SLIDER -->
            <div class="slides">
                @foreach($section->content['slides'] ?? [] as $index => $slide)
                    <!-- SLIDE #{{ $index + 1 }} -->
                    <div class="slide {{ $index === 0 ? 'is-active' : '' }}">
                        <!-- Slide Content -->
                        <div class="slide-content">
                            <div class="container">
                                <div class="row justify-content-md-center">
                                    <div class="col col-lg-10">
                                        <div class="caption">
                                            <!-- Small Title with custom color -->
                                            @if(isset($slide['smallTitle']) && !empty($slide['smallTitle']))
                                                <span class="slide-small-title"
                                                    style="color: {{ $slide['smallTitleColor'] ?? '#af8855' }}">
                                                    {{ __($slide['smallTitle']) }}
                                                </span>
                                            @endif

                                            <!-- Title with custom color -->
                                            @if(isset($slide['title']) && !empty($slide['title']))
                                                <div class="title">
                                                    <h2 class="slide-title" style="color: {{ $slide['titleColor'] ?? '#ffffff' }}">
                                                        {{ __($slide['title']) }}
                                                    </h2>
                                                </div>
                                            @endif

                                            <!-- Button with custom colors -->
                                            @if(isset($slide['buttonText']) && !empty($slide['buttonText']) && isset($slide['buttonUrl']) && !empty($slide['buttonUrl']))
                                                <div class="text">
                                                    <a href="{{ $slide['buttonUrl'] }}" class="custom-btn"
                                                        style="background-color: {{ $slide['buttonBgColor'] ?? '#af8855' }}; color: {{ $slide['buttonTextColor'] ?? '#ffffff' }}; border-color: {{ $slide['buttonBgColor'] ?? '#af8855' }};">
                                                        {{ __($slide['buttonText']) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Slide Content -->

                        <!-- Slide Background Image -->
                        <div class="image-container">
                            @if (isset($slide['bgImage']) && !empty($slide['bgImage']))
                                <img class="image" src="{{ WidgetHelper::getImageUrl($slide['bgImage']) }}"
                                    alt="{{ __('slide-background') }}">
                            @else
                                <img class="image" src="/assets/images/slider/mcs-{{ $index + 1 }}.jpeg"
                                    alt="{{ __('slide-background') }}">
                            @endif
                        </div>
                    </div>
                    <!-- END SLIDE #{{ $index + 1 }} -->
                @endforeach

                {{-- Fallback slide if no slides exist --}}
                @if(!isset($section->content['slides']) || empty($section->content['slides']))
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
                @endif
            </div>
            <!-- END SLIDER -->
        </div>
        <!-- End Slideshow Inner -->
    </div>
    <!-- End Slideshow -->
</section>
<!-- END Home Page Hero Section -->