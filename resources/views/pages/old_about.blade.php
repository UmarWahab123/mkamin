@extends('layouts.app')

@section('title', __('About - mcs.sa Salon'))

@section('content')


    <!-- INNER PAGE HERO
                        ============================================= -->
    <section id="about-page" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ __('About mcs.sa') }}</h2>
                        <p>{{ __('Luxury salon where you will feel unique and special') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->




    <!-- TEXT CONTENT
                                    ============================================= -->
    <section class="pt-8 ct-03 content-section division">
        <div class="container">
            <div class="row">


                <!-- TEXT BLOCK -->
                <div class="col-lg-6">
                    <div class="txt-block left-column wow fadeInRight">


                        <!-- TEXT -->
                        <div class="ct-03-txt">

                            <!-- Section ID -->
                            <span class="section-id">{{ __('Mind, Body and Soul') }}</span>

                            <!-- Title -->
                            <h2 class="h2-md">{{ __('Luxury salon where you will feel unique') }}</h2>

                            <!-- Text -->
                            <p class="mb-5">{{ __('Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty. Experience tranquility and rejuvenation in our meticulously designed space created with your comfort in mind.') }}</p>

                        </div>


                        <!-- IMAGE -->
                        <div class="ct-03-img">
                            <img class="img-fluid" src="/assets/images/beauty_08.jpg" alt="content-image">
                        </div>


                    </div>
                </div>
                <!-- END TEXT BLOCK -->


                <!-- TEXT BLOCK -->
                <div class="col-lg-6">
                    <div class="txt-block right-column wow fadeInLeft">


                        <!-- IMAGE -->
                        <div class="ct-03-img mb-5">
                            <img class="img-fluid" src="/assets/images/salon_04.jpg" alt="content-image">
                        </div>


                        <!-- TEXT -->
                        <div class="ct-03-txt">

                            <!-- Text -->
                            <p class="mb-0">{{ __('At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect. Our talented team continuously trains in the latest trends and methods to ensure you receive the highest quality care with every visit.') }}</p>

                        </div>


                    </div>
                </div>
                <!-- END TEXT BLOCK -->


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END TEXT CONTENT -->




    <!-- ABOUT-1
                                    ============================================= -->
    <section class="pt-8 about-1 about-section">
        <div class="container">
            <div class="row justify-content-center">


                <!-- TEXT BLOCK -->
                <div class="col-lg-10 col-xl-9">
                    <div class="txt-block text-center">

                        <!-- Section ID -->
                        <span class="section-id">{{ __('Indulge Yourself') }}</span>

                        <!-- Title -->
                        <h2 class="h2-title">{{ __('Feel Yourself More Beautiful') }}</h2>

                        <!-- Text -->
                        <p class="mb-0">{{ __('Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.') }}</p>

                    </div>
                </div>
                <!-- END TEXT BLOCK -->


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END ABOUT-1 -->




    <!-- SERVICES-3
                                    ============================================= -->
    <div id="services-3" class="pt-8 services-section division">
        <div class="container">


            <!-- SERVICES-3 WRAPPER -->
            <div class="sbox-3-wrapper text-center">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6">


                    <!-- SERVICES BOX #1 -->
                    <div class="col">
                        <div class="sbox-3 sb-1 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-facial-treatment color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Facials') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #1 -->


                    <!-- SERVICES BOX #2 -->
                    <div class="col">
                        <div class="sbox-3 sb-2 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-eyelashes color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Eyelash') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #2 -->


                    <!-- SERVICES BOX #3 -->
                    <div class="col">
                        <div class="sbox-3 sb-3 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-eyebrow color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Eyebrow') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #3 -->


                    <!-- SERVICES BOX #4 -->
                    <div class="col">
                        <div class="sbox-3 sb-4 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-wax color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Waxing') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #4 -->


                    <!-- SERVICES BOX #5 -->
                    <div class="col">
                        <div class="sbox-3 sb-5 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-foundation color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Nails') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #5 -->


                    <!-- SERVICES BOX #6 -->
                    <div class="col">
                        <div class="sbox-3 sb-6 wow fadeInUp">

                            <!-- Icon -->
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-cosmetics color--black"></span>
                            </div>

                            <!-- Text -->
                            <div class="sbox-txt">
                                <p>{{ __('Make-Up') }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- END SERVICES BOX #6 -->


                </div>
                <!-- End row -->
            </div>
            <!-- END SERVICES-3 WRAPPER -->


            <!-- BUTTON -->
            <div class="row">
                <div class="col">
                    <div class="more-btn mt-5">
                        <a href="{{ route('menu') }}" class="btn btn--tra-black hover--black">{{ __('View Our Menu') }}</a>
                    </div>
                </div>
            </div>


        </div>
        <!-- End container -->
    </div>
    <!-- END SERVICES-3 -->




    <!-- TEXT CONTENT
                                    ============================================= -->
    <section class="pt-8 ct-05 content-section">
        <div class="container stone--shape">
            <div class="row d-flex align-items-center">


                <!-- TEXT BLOCK -->
                <div class="col-lg-6 order-last order-lg-2">
                    <div class="txt-block left-column wow fadeInRight">

                        <!-- Section ID -->
                        <span class="section-id">{{ __('You Are Beauty') }}</span>

                        <!-- Title -->
                        <h2 class="h2-md">{{ __('Give the pleasure of beautiful to yourself') }}</h2>

                        <!-- ACCORDION WRAPPER -->
                        <div class="accordion accordion-wrapper mt-5">
                            <ul class="accordion">


                                <!-- ACCORDION ITEM #1 -->
                                <li class="accordion-item is-active">

                                    <!-- Title -->
                                    <div class="accordion-thumb">
                                        <p>{{ __('Certified Stylists') }}</p>
                                    </div>

                                    <!-- Text -->
                                    <div class="accordion-panel">
                                        <p class="mb-0">{{ __('Our team consists of highly trained professionals with international certifications and years of experience in the beauty industry.') }}</p>
                                    </div>

                                </li>
                                <!-- END ACCORDION ITEM #1 -->


                                <!-- ACCORDION ITEM #2 -->
                                <li class="accordion-item">

                                    <!-- Title -->
                                    <div class="accordion-thumb">
                                        <p>{{ __('100% Organic Cosmetics') }}</p>
                                    </div>

                                    <!-- Text -->
                                    <div class="accordion-panel">
                                        <p class="mb-0">{{ __('We use only premium organic and cruelty-free products that nourish your skin and hair while being kind to the environment.') }}</p>
                                    </div>

                                </li>
                                <!-- END ACCORDION ITEM #2 -->


                                <!-- ACCORDION ITEM #3 -->
                                <li class="accordion-item">

                                    <!-- Title -->
                                    <div class="accordion-thumb">
                                        <p>{{ __('Easy Online Booking') }}</p>
                                    </div>

                                    <!-- Text -->
                                    <div class="accordion-panel">
                                        <p class="mb-0">{{ __('Book your appointments with ease through our convenient online system, available 24/7 with instant confirmation and reminders.') }}</p>
                                    </div>

                                </li>
                                <!-- END ACCORDION ITEM #3 -->


                            </ul>
                        </div>
                        <!-- END ACCORDION WRAPPER -->

                    </div>
                </div>
                <!-- END TEXT BLOCK -->


                <!-- IMAGE BLOCK -->
                <div class="col-lg-6 order-first order-lg-2">
                    <div class="ct-05-img right-column wow fadeInLeft">
                        <img class="img-fluid" src="/assets/images/woman_02.jpg" alt="content-image">
                    </div>
                </div>


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END TEXT CONTENT -->




    <!-- WORKING HOURS
                                    ============================================= -->
    <!-- Home Page Working Hours Section -->
    @include('filawidgets.common.working-hours-section', ['workingHours' => $workingHours, 'workingHoursContent' => $workingHoursContent, 'qrCode' => $qrCode])




    <!-- WIDE IMAGE
                                    ============================================= -->
    <div class="bg--01 bg--scroll ct-12 content-section"></div>




    <!-- ABOUT-5
                                    ============================================= -->
    <section class="pt-8 about-5 about-section">
        <div class="container-fluid">
            <div class="row">


                <!-- IMAGE BLOCK -->
                <div class="col">
                    <div id="ab-5-1" class="about-5-img">
                        <img class="img-fluid" src="/assets/images/beauty_02.jpg" alt="about-image">
                    </div>
                </div>


                <!-- TEXT BLOCK -->
                <div class="col-md-8 col-lg-7 order-first order-md-1">
                    <div class="txt-block">

                        <!-- Section ID -->
                        <span class="section-id">{{ __('Be Irresistible') }}</span>

                        <!-- Title -->
                        <h2 class="h2-title">{{ __('The Ultimate Relaxation for Your Mind and Body') }}</h2>

                        <!-- Image -->
                        <div id="ab-5-2" class="about-5-img">
                            <img class="img-fluid" src="/assets/images/beauty_03.jpg" alt="about-image">
                        </div>

                    </div>
                </div>
                <!-- END TEXT BLOCK -->


                <!-- IMAGE BLOCK -->
                <div class="col order-last order-md-2">
                    <div id="ab-5-3" class="about-5-img">
                        <img class="img-fluid" src="/assets/images/beauty_04.jpg" alt="about-image">
                    </div>
                </div>


            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END ABOUT-5 -->







    <!-- BANNER-1
                                    ============================================= -->
    <section class="pt-8 banner-1 banner-section">
        <div class="container">
            <div class="banner-1-wrapper bg--fixed">
                <div class="row">
                    <div class="col">
                        <div class="banner-1-txt text-center color--white">

                            <!-- Section ID -->
                            <span class="section-id">{{ __('This Week Only') }}</span>

                            <!-- Title -->
                            <h2>{{ __('Get 30% OFF') }}</h2>
                            <h3>{{ __('Manicure + Gel Polish') }}</h3>

                            <!-- Button -->
                            <a href="{{ route('salon-services') }}" class="btn btn--tra-white hover--white">{{ __('Book an Appointment') }}</a>

                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- End banner wrapper -->
        </div>
        <!-- End container -->
    </section>
    <!-- END BANNER-1 -->



    <!-- Cart Float Component -->
    @include('components.cart-float')



@endsection
