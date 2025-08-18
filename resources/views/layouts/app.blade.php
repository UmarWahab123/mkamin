@php
    use App\Models\HeaderSettings;
    $header = HeaderSettings::first();
@endphp

<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>

    <!-- Inject dynamic color -->
    <style>
        :root {
            --header-bg-color: {{ $header->header_color ?? '#212223' }};
            --header-text-color: {{ $header->header_text_color ?? '#fff' }};
            --header-text-hover-color: {{ $header->header_text_hover_color ?? '#000000' }};
            --header-text-dropdown-color: {{ $header->header_text_dropdown_color ?? '#fff' }};
            --header-text-dropdown-hover-color: {{ $header->header_text_dropdown_hover_color ?? '#000000' }};
        }
    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DSAThemes">
    <meta name="description" content="mcs.sa - Beauty Salon">
    <meta name="keywords"
        content="DSAThemes, Beauty, Salon, Beauty Parlour, Health Care, Makeup, Nail Salon, Therapy, Treatment">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SITE TITLE -->
    <title>@yield('title', 'mcs.sa - Beauty Salon')</title>

    <!-- FAVICON AND TOUCH ICONS -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/images/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/images/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" href="/assets/images/apple-touch-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/riyal_currancy.css">


    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP CSS -->
    @if (app()->getLocale() == 'ar')
        <link href="/assets/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- FONT ICONS -->
    <link href="/assets/css/flaticon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- PLUGINS STYLESHEET -->
    <link href="/assets/css/menu.css" rel="stylesheet">
    <link id="effect" href="/assets/css/dropdown-effects/fade-down.css" media="all" rel="stylesheet">
    <link href="/assets/css/magnific-popup.css" rel="stylesheet">
    <link href="/assets/css/owl.carousel.min.css" rel="stylesheet">
    <link href="/assets/css/owl.theme.default.min.css" rel="stylesheet">
    <link href="/assets/css/datetimepicker.min.css" rel="stylesheet">
    <link href="/assets/css/lunar.css" rel="stylesheet">

    <!-- ON SCROLL ANIMATION -->
    <link href="/assets/css/animate.css" rel="stylesheet">

    <!-- TEMPLATE CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- RESPONSIVE CSS -->
    <link href="/assets/css/responsive.css" rel="stylesheet">

    <!-- CSS FILES -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <link rel="stylesheet" href="/assets/css/menu.css">
    <link rel="stylesheet" href="/assets/css/dropdown-effects/fade-down.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    {{-- <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="stylesheet" href="/assets/css/rtl.css"> --}}

    <!-- Font Awesome CSS -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}

    <!-- Leaflet CSS (for maps) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    @yield('styles')
    @stack('styles')

    <!-- OPTIONAL HEAD SCRIPTS -->
    @yield('head')
</head>

<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<!-- PRELOADER SPINNER
    ============================================= -->
<div id="loading" class="loading-black">
    <div id="loading-center"><span class="loader"></span></div>
</div>

<!-- STYLE SWITCHER
    ============================================= -->
{{-- <div id="stlChanger">
        <div class="blockChanger bgChanger">
            <a href="#" class="chBut ico-35">
                <p class="switch">
                    <span class="drk-mode flaticon-moon"></span>
                    <span class="lgt-mode flaticon-sum"></span>
                </p>
            </a>
        </div>
    </div> --}}
<!-- END SWITCHER -->

<!-- PAGE CONTENT
    ============================================= -->
<div id="page" class="page">

    <!-- HEADER
        ============================================= -->
    @include('partials.header')
    <!-- END HEADER -->

    <!-- CONTENT
        ============================================= -->
    @yield('content')
    <!-- END CONTENT -->

    <!-- FOOTER
        ============================================= -->
    @include('partials.footer')
    <!-- END FOOTER -->


</div>
<!-- END PAGE CONTENT -->

<!-- EXTERNAL SCRIPTS
    ============================================= -->
<script src="/assets/js/jquery-3.7.0.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/modernizr.custom.js"></script>
<script src="/assets/js/jquery.easing.js"></script>
<script src="/assets/js/menu.js"></script>
<script src="/assets/js/materialize.js"></script>
<script src="/assets/js/tweenmax.min.js"></script>
<script src="/assets/js/slideshow.js"></script>
<script src="/assets/js/datetimepicker.js"></script>
<script src="/assets/js/owl.carousel.min.js"></script>
<script src="/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/assets/js/request-form.js"></script>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="/assets/js/jquery.ajaxchimp.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/lunar.js"></script>
<script src="/assets/js/wow.js"></script>

<!-- Leaflet JS (for maps) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // Set global AJAX defaults
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-LOCALE': '{{ app()->getLocale() }}'
        }
    });
</script>
<!-- Custom Script -->
<script src="/assets/js/custom.js"></script>

{{-- <script>
        $(function() {
            $(".switch").click(function() {
                $("body").toggleClass("theme--dark");
            });
        });
    </script> --}}

<!-- Cart Manager Component -->
@include('components.cart-manager')

@yield('scripts')
@stack('scripts')
</body>

</html>
