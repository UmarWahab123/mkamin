<!-- Home Page Text Content 4 - DYNAMIC WIDGET
============================================= -->
{{-- @php
use App\Helpers\WidgetHelper;
dd(getImageUrl($section->content['image']));
dd($section->content);
@endphp --}}
<section class="pt-8 ct-05 content-section">
    <div class="container" style="background-color: {{ $section->content['backgroundColor'] ?? '#ffffff' }}">
        <div class="row d-flex align-items-center">
            <!-- TEXT BLOCK -->
            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-block left-column wow fadeInRight">
                    <!-- Section ID -->
                    <span class="section-id"
                        style="color: {{ $section->content['smallTitleColor'] ?? '#af8855' }}">{{ isset($section->content['smallTitle']) ? __($section->content['smallTitle']) : __('Premium Services') }}</span>
                    <!-- Title -->
                    <h2 class="h2-md" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                        {{ isset($section->content['title']) ? __($section->content['title']) : __('Professional Beauty Care') }}</h2>
                    <!-- Text -->
                    <p style="color: {{ $section->content['description1Color'] ?? '#666' }}">
                        {{ isset($section->content['description1']) ? __($section->content['description1']) : __('Experience the finest in beauty and wellness with our expert team of professionals.') }}</p>
                    <!-- Text -->
                    <p class="mb-0" style="color: {{ $section->content['description2Color'] ?? '#666' }}">
                        {{ isset($section->content['description2']) ? __($section->content['description2']) : __('We use only premium products and techniques to ensure exceptional results every time.') }}</p>
                </div>
            </div>
            <!-- END TEXT BLOCK -->

            <!-- IMAGE BLOCK -->
            <div class="col-lg-6 order-first order-lg-2 px-0">
                <div class="ct-05-img right-column wow fadeInLeft right-column-image d-flex justify-content-end">
                    <img class="img-fluid column-image"
                        src="{{ isset($section->content['image']) ? getImageUrl($section->content['image'], '/assets/images/mcs-7.jpeg') : '/assets/images/mcs-7.jpeg' }}"
                        alt="{{ __('content-image') }}">
                </div>
            </div>
        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 4 -->