<!-- Home Page Text Content 1 - DYNAMIC WIDGET
============================================= -->
@php
    use App\Helpers\WidgetHelper;
@endphp
<section class="pt-8 ct-01 content-section division">
    <div class="container">
        <div class="row d-flex align-items-center">
            <!-- TEXT BLOCK -->
            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-block left-column wow fadeInRight">
                    <!-- Section ID -->
                    <span class="section-id color--gold">{{ __($textContent['smallTitle']) }}</span>
                    <!-- Title -->
                    <h2 class="h2-md">{{ __($textContent['title']) }}</h2>
                    <!-- Text -->
                    <p class="mb-0">{{ __($textContent['description']) }}</p>
                </div>
            </div>
            <!-- END TEXT BLOCK -->

            <!-- IMAGE BLOCK -->
            <div class="col-lg-6 order-first order-lg-2">
                <div class="d-flex justify-content-center">
                    <img class="img-fluid rounded w-75 mx-auto"
                        src="{{ WidgetHelper::getImageUrl($textContent['image'], '/assets/images/mcs-3.jpeg') }}"
                        alt="{{ __('content-image') }}">
                </div>
            </div>
        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 1 -->
