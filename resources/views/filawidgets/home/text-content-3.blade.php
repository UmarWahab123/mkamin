<!-- Home Page Text Content 3 - DYNAMIC WIDGET
============================================= -->
{{-- @php
    // use App\Helpers\WidgetHelper;
    // dd(getImageUrl($section->content['image']));
    // dd($section->content);
@endphp --}}
<section class="pt-8 ct-07 ws-wrapper content-section">
    <div class="container">
        <div class="ct-07-wrapper bg--black block-shadow color--white">
            <div class="row d-flex align-items-center">
                <!-- TEXT BLOCK -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-block left-column">
                        <!-- Section ID -->
                        <span class="section-id"
                            style="color: {{ $section->content['smallTitleColor'] ?? '#af8855' }}">{{ isset($section->content['smallTitle']) ? __($section->content['smallTitle']) : __('Special Offer') }}</span>
                        <!-- Title -->
                        <h2 class="h2-md" style="color: {{ $section->content['titleColor'] ?? '#ffffff' }}">
                            {{ isset($section->content['title']) ? __($section->content['title']) : __('Exclusive Beauty Package') }}</h2>
                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $section->content['descriptionColor'] ?? '#ffffff' }}">
                            {{ isset($section->content['description']) ? __($section->content['description']) : __('Transform yourself with our comprehensive beauty treatments designed for your complete wellness and relaxation.') }}</p>
                        
                        <!-- Button -->
                        @if(isset($section->content['buttonText']) && $section->content['buttonText'])
                            <div class="txt-block-btn">
                                <a href="{{ $section->content['buttonUrl'] ?? '#' }}" class="btn custom-btn"
                                    style="background-color: {{ $section->content['buttonBgColor'] ?? '#af8855' }}; color: {{ $section->content['buttonTextColor'] ?? '#ffffff' }}; border-color: {{ $section->content['buttonBgColor'] ?? '#af8855' }};">
                                    {{ __($section->content['buttonText']) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END TEXT BLOCK -->

                <!-- IMAGE BLOCK -->
                <div class="col-lg-6 order-first order-lg-2">
                    <div class="img-block right-column d-flex justify-content-end">
                      <img class="img-fluid"
                        src="{{ isset($section->content['image']) 
                                    ? getImageUrl($section->content['image'], asset('assets/images/elementor-placeholder-image.png')) 
                                    : asset('assets/images/elementor-placeholder-image.png') }}"
                        style="max-height: 80vh;"
                        alt="{{ __('content-image') }}">

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