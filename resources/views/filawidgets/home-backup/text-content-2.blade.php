<!-- Home Page Text Content 2
============================================= -->
@php
    use App\Helpers\WidgetHelper;
@endphp
<section class="pt-8 about-6 about-section">
    <div class="container">
        <!-- SECTION TITLE -->
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="section-title text-center mb-6">
                    <!-- Section ID -->
                    <span class="section-id"
                        style="color: {{ $textContent2['smallTitleColor'] }}">{{ __($textContent2['smallTitle']) }}</span>
                    <!-- Title -->
                    <h2 class="h2-title" style="color: {{ $textContent2['titleColor'] }}">
                        {{ __($textContent2['title']) }}</h2>
                </div>
            </div>
        </div>

        <!-- ABOUT-6 CONTENT -->
        <div class="row">
            <!-- ABOUT-6 IMAGE -->
            <div class="col-md-6 col-lg-4">
                <div id="a6-img-1" class="about-6-img">
                    <img class="img-fluid"
                        src="{{ WidgetHelper::getImageUrl($textContent2['image1'], '/assets/images/mcs-4.jpeg') }}"
                        alt="about-image">
                </div>
            </div>

            <!-- ABOUT-6 TEXT -->
            <div class="col-lg-4 order-first order-lg-1">
                <div class="about-6-txt">
                    <!-- TEXT -->
                    <div class="a6-txt" style="background-color: {{ $textContent2['cardBackgroundColor'] }}">
                        <!-- Title -->
                        <h4 class="h4-md" style="color: {{ $textContent2['cardTitleColor'] }}">
                            {{ __($textContent2['cardTitle']) }}</h4>
                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $textContent2['cardDescriptionColor'] }}">
                            {{ __($textContent2['cardDescription']) }}
                        </p>
                    </div>
                    <!-- IMAGE -->
                    <div class="a6-img">
                        <img class="img-fluid"
                            src="{{ WidgetHelper::getImageUrl($textContent2['image3'], '/assets/images/mcs-6.jpeg') }}"
                            alt="about-image">
                    </div>
                </div>
            </div>

            <!-- ABOUT-6 IMAGE -->
            <div class="col-md-6 col-lg-4 order-last order-lg-2">
                <div id="a6-img-2" class="about-6-img">
                    <img class="img-fluid"
                        src="{{ WidgetHelper::getImageUrl($textContent2['image2'], '/assets/images/mcs-5.jpeg') }}"
                        alt="about-image">
                </div>
            </div>
        </div>
        <!-- END ABOUT-6 CONTENT -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 2 -->
