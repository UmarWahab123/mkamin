<!-- Home Page Text Content 4
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<section class="pt-8 ct-05 content-section">
    <div class="container" style="background-color: {{ $textContent4['backgroundColor'] }}">
        <div class="row d-flex align-items-center">
            <!-- TEXT BLOCK -->
            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-block left-column wow fadeInRight">
                    <!-- Section ID -->
                    <span class="section-id"
                        style="color: {{ $textContent4['smallTitleColor'] }}">{{ __($textContent4['smallTitle']) }}</span>
                    <!-- Title -->
                    <h2 class="h2-md" style="color: {{ $textContent4['titleColor'] }}">
                        {{ __($textContent4['title']) }}</h2>
                    <!-- Text -->
                    <p style="color: {{ $textContent4['description1Color'] }}">
                        {{ __($textContent4['description1']) }}</p>
                    <!-- Text -->
                    <p class="mb-0" style="color: {{ $textContent4['description2Color'] }}">
                        {{ __($textContent4['description2']) }}</p>
                </div>
            </div>
            <!-- END TEXT BLOCK -->

            <!-- IMAGE BLOCK -->
            <div class="col-lg-6 order-first order-lg-2 px-0">
                <div class="ct-05-img right-column wow fadeInLeft right-column-image d-flex justify-content-end">
                    <img class="img-fluid column-image"
                        src="{{ WidgetHelper::getImageUrl($textContent4['image'], '/assets/images/mcs-7.jpeg') }}"
                        alt="content-image">
                </div>
            </div>
        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 4 -->
