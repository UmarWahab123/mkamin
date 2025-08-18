<!-- Home Page Text Content 3
============================================= -->
@php
    use App\Helpers\WidgetHelper;
@endphp
<section class="pt-8 ct-07 ws-wrapper content-section">
    <div class="container">
        <div class="ct-07-wrapper bg--black block-shadow color--white">
            <div class="row d-flex align-items-center">
                <!-- TEXT BLOCK -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-block left-column">
                        <!-- Section ID -->
                        <span class="section-id"
                            style="color: {{ $textContent3['smallTitleColor'] }}">{{ __($textContent3['smallTitle']) }}</span>
                        <!-- Title -->
                        <h2 class="h2-md" style="color: {{ $textContent3['titleColor'] }}">
                            {{ __($textContent3['title']) }}</h2>
                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $textContent3['descriptionColor'] }}">
                            {{ __($textContent3['description']) }}</p>
                        <!-- Button -->
                        <div class="txt-block-btn">
                            <a href="{{ $textContent3['buttonUrl'] }}" class="btn custom-btn"
                                style="background-color: {{ $textContent3['buttonBgColor'] }}; color: {{ $textContent3['buttonTextColor'] }}; border-color: {{ $textContent3['buttonBgColor'] }};">
                                {{ __($textContent3['buttonText']) }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END TEXT BLOCK -->

                <!-- IMAGE BLOCK -->
                <div class="col-lg-6 order-first order-lg-2">
                    <div class="img-block right-column d-flex justify-content-end">
                        <img class="img-fluid" src="/assets/images/mcs-2.jpeg" style="max-height: 80vh;"
                            alt="content-image">
                    </div>
                </div>
            </div>
            <!-- End row -->
        </div>
        <!-- End content wrapper -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 3 -->
