@extends('layouts.app')

@section('title', __('Testimonials - mcs.sa Salon'))

@section('content')

    <!-- INNER PAGE HERO
                        ============================================= -->
    <section id="reviews-page" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ __('Comments & Reviews') }}</h2>
                        <p>{{ __('Here is what our amazing clients are saying about us') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->




    <!-- TESTIMONIALS-3
                                    ============================================= -->
    <div id="reviews-3" class="pt-8 reviews-section">
        <div class="container">


            <!-- TESTIMONIALS-3 WRAPPER -->
            <div class="reviews-3-wrapper rel shape--02 rice--shape">
                <div class="row align-items-center row-cols-1 row-cols-md-2">


                    <!-- TESTIMONIAL #1 -->
                    <div class="col">
                        <div id="rw-3-1" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('The highlight service I received was absolutely phenomenal! My stylist used premium products that left my hair silky smooth and vibrant. The atmosphere was relaxing, and the attention to detail made all the difference.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-1.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/google.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Laura Merino') }}</p>
                                        <span>{{ __('2 days ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #1 -->


                    <!-- TESTIMONIAL #2 -->
                    <div class="col">
                        <div id="rw-3-2" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-half-empty"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('The nail technicians here are true artists! My gel manicure lasted three weeks without chipping. The salon is immaculate and maintains the highest hygiene standards. I\'ve found my permanent nail salon and wouldn\'t dream of going anywhere else.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-2.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/crowd.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Carmen M. Garcia') }}</p>
                                        <span>{{ __('9 days ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #2 -->


                    <!-- TESTIMONIAL #3 -->
                    <div class="col">
                        <div id="rw-3-3" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('I\'ve had eyebrow microblading done at several places, but mcs.sa Salon is exceptional. The technician took time to understand exactly what I wanted and designed a perfect shape for my face. The results look completely natural and have saved me so much time in my morning routine.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-3.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/google.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Michelle Boxer') }}</p>
                                        <span>{{ __('25 days ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #3 -->


                    <!-- TESTIMONIAL #4 -->
                    <div class="col">
                        <div id="rw-3-4" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('The facial treatment at mcs.sa was transformative! The esthetician analyzed my skin concerns and customized everything. I left with glowing skin and expert advice for my home care routine. Worth every penny for the luxury experience.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-4.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/yelp.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Nicole Byer') }}</p>
                                        <span>{{ __('1 month ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #4 -->


                    <!-- TESTIMONIAL #5 -->
                    <div class="col">
                        <div id="rw-3-5" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('I\'m extremely impressed with the level of professionalism at mcs.sa Salon. Their waxing services are quick, precise, and as painless as possible. The therapist made me feel comfortable throughout the entire session, and the results are flawless.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-8.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/yelp.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Rachel A.') }}</p>
                                        <span>{{ __('1 month ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #5 -->


                    <!-- TESTIMONIAL #6 -->
                    <div class="col">
                        <div id="rw-3-6" class="review-3 bg--stone">

                            <!-- Quote Icon -->
                            <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>

                            <!-- Text -->
                            <div class="review-txt">

                                <!-- Rating Stars -->
                                <div class="star-rating ico-15 color--yellow clearfix">
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star-1"></span>
                                    <span class="flaticon-star"></span>
                                </div>

                                <!-- Text -->
                                <p>{{ __('The bridal makeup service exceeded my expectations! The artist created a look that photographed beautifully and lasted throughout my entire wedding day and night. Everyone in my bridal party was equally thrilled with their makeovers. I\'ll treasure these memories forever.') }}
                                </p>

                                <!-- Author -->
                                <div class="author-data clearfix">

                                    <!-- Avatar -->
                                    <div class="review-avatar">
                                        <img src="/assets/images/review-author-6.jpg" alt="review-avatar">
                                        <div class="rs-logo"><img src="/assets/images/crowd.png" alt="review-logo"></div>
                                    </div>

                                    <!-- Data -->
                                    <div class="review-author">
                                        <p>{{ __('Elizabeth Ross') }}</p>
                                        <span>{{ __('2 month ago') }}</span>
                                    </div>

                                </div>
                                <!-- End Author -->

                            </div>
                            <!-- End Text -->

                        </div>
                    </div>
                    <!-- END TESTIMONIAL #6 -->


                </div>
                <!-- End row -->
            </div>
            <!-- END TESTIMONIALS-3 WRAPPER -->


        </div>
        <!-- End container -->
    </div>
    <!-- END TESTIMONIALS-3 -->




    <!-- PAGE PAGINATION
                                    ============================================= -->
    <div class="py-8 page-pagination theme-pagination">
        {{-- <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination ico-20 justify-content-center">
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"><span
                                        class="flaticon-back"></span></a>
                            </li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span
                                        class="flaticon-next"></span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div> --}}
        <!-- End container -->
    </div>
    <!-- END PAGE PAGINATION -->




    <!-- BANNER-2
                                    ============================================= -->
    <section class="bg--03 bg--scroll py-8 banner-2 banner-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="banner-2-txt text-center color--white">

                        <!-- Section ID -->
                        <span class="section-id">{{ __('This Week Only') }}</span>

                        <!-- Title -->
                        <h2>{{ __('Get') }} <span>{{ __('30% OFF') }}</span></h2>
                        <h3>{{ __('Quick Face Makeup') }}</h3>

                        <!-- Button -->
                        <a href="{{ route('salon-services') }}" class="btn btn--tra-white hover--white">{{ __('Book an Appointment') }}</a>

                    </div>
                </div>
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </section>
    <!-- END BANNER-2 -->




    <!-- WORKING HOURS
                                    ============================================= -->
    <!-- Home Page Working Hours Section -->
    @include('filawidgets.common.working-hours-section', ['workingHours' => $workingHours, 'workingHoursContent' => $workingHoursContent, 'qrCode' => $qrCode])





    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection
