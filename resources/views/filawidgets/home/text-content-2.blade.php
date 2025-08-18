<!-- Home Page Text Content 2 - DYNAMIC WIDGET
============================================= -->
{{-- @php
    use App\Helpers\WidgetHelper;
    dd(getImageUrl($section->content['image1']));
    dd($section->content);
@endphp --}}
<section class="pt-8 about-6 about-section">
    <div class="container">
        <!-- SECTION TITLE -->
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="section-title text-center mb-6">
                    <!-- Section ID -->
                    <span class="section-id"
                        style="color: {{ $section->content['smallTitleColor'] ?? '#af8855' }}">{{ isset($section->content['smallTitle']) ? __($section->content['smallTitle']) : __('About mcs.sa') }}</span>
                    <!-- Title -->
                    <h2 class="h2-title" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                        {{ isset($section->content['title']) ? __($section->content['title']) : __('Our Story of Excellence') }}</h2>
                </div>
            </div>
        </div>

        <!-- ABOUT-6 CONTENT -->
        <div class="row">
            <!-- ABOUT-6 IMAGE -->
            <div class="col-md-6 col-lg-4">
                <div id="a6-img-1" class="about-6-img">
                    <img class="img-fluid"
                        src="{{ isset($section->content['image1']) ? getImageUrl($section->content['image1'], '/assets/images/mcs-4.jpeg') : '/assets/images/mcs-4.jpeg' }}"
                        alt="{{ __('about-image') }}">
                </div>
            </div>

            <!-- ABOUT-6 TEXT -->
            <div class="col-lg-4 order-first order-lg-1">
                <div class="about-6-txt">
                    <!-- TEXT -->
                    <div class="a6-txt" style="background-color: {{ $section->content['cardBackgroundColor'] ?? '#ffffff' }}">
                        <!-- Title -->
                        <h4 class="h4-md" style="color: {{ $section->content['cardTitleColor'] ?? '#363636' }}">
                            {{ isset($section->content['cardTitle']) ? __($section->content['cardTitle']) : __('Professional Care') }}</h4>
                        <!-- Text -->
                        <p class="mb-0" style="color: {{ $section->content['cardDescriptionColor'] ?? '#666' }}">
                            {{ isset($section->content['cardDescription']) ? __($section->content['cardDescription']) : __('Expert stylists with years of experience in beauty and wellness.') }}
                        </p>
                    </div>
                    <!-- IMAGE -->
                    <div class="a6-img">
                        <img class="img-fluid"
                            src="{{ isset($section->content['image3']) ? getImageUrl($section->content['image3'], '/assets/images/mcs-6.jpeg') : '/assets/images/mcs-6.jpeg' }}"
                            alt="{{ __('about-image') }}">
                    </div>
                </div>
            </div>

            <!-- ABOUT-6 IMAGE -->
            <div class="col-md-6 col-lg-4 order-last order-lg-2">
                <div id="a6-img-2" class="about-6-img">
                    <img class="img-fluid"
                        src="{{ isset($section->content['image2']) ? getImageUrl($section->content['image2'], '/assets/images/mcs-5.jpeg') : '/assets/images/mcs-5.jpeg' }}"
                        alt="{{ __('about-image') }}">
                </div>
            </div>
        </div>
        <!-- END ABOUT-6 CONTENT -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Text Content 2 -->