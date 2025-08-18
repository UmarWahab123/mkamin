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
                    <span class="section-id" style="color:{{ $section->content['smallTitleColor'] ?? '#af8855' }}">{{ isset($section->content['smallTitle']) ? __($section->content['smallTitle']) : __('Declare Your Style') }}</span>
                    <!-- Title -->
                    <h2 class="h2-md" style="color:{{ $section->content['titleColor'] ?? '#363636' }}">{{ isset($section->content['title']) ? __($section->content['title']) : __('Welcome to Alwan Maya Women Beauty Salon') }}</h2>
                    <!-- Text -->
                    <p class="mb-0" style="color:{{ $section->content['descriptionColor'] ?? '#363636' }}">{{ isset($section->content['description']) ? __($section->content['description']) : __('We are honored to be your first destination for taking care of your beauty. We believe that every person deserves moments of pampering and attention, and our team of experts is ready to provide services in the world of beauty, from hair and skin care to the latest makeup trends, in addition to nail care and other services.') }}</p>
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
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 1 -->
