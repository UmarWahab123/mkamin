@extends('layouts.app')

@section('title', __('FAQs - mcs.sa Salon'))

@section('content')

<h1 style="text-align:center; margin-top:20vh; font-family: Arial, sans-serif; color:#333;">
    🚧 This page is under development. We'll be back soon!
</h1>

{{-- 
    <!-- INNER PAGE HERO
                        ============================================= -->
    <section id="faqs-page" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ __('Salon FAQs') }}</h2>
                        <p>{{ __('Everything you need to know about our salon services.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->




    <!-- FAQs-1
                                    ============================================= -->
    <section id="faqs-1" class="pt-8 faqs-section division">
        <div class="container">


            <!-- QUESTIONS WRAPPER -->
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="questions-wrapper">


                        <!-- QUESTION #1 -->
                        <div class="question row wow fadeInUp">

                            <!-- Question -->
                            <div class="col-lg-6">
                                <h5 class="h5-md">{{ __('How do I schedule an appointment?') }}</h5>
                            </div>

                            <!-- Answer -->
                            <div class="col-lg-6">

                                <!-- Text -->
                                <p>{{ __('You can schedule an appointment through our website by selecting your desired service, stylist, and available time slot. Our online booking system is available 24/7 for your convenience.') }}</p>

                                <!-- Text -->
                                <p class="mb-0">{{ __('Alternatively, you can call our salon directly during business hours to speak with our receptionist who will help you book the perfect appointment time.') }}</p>

                            </div>

                        </div>
                        <!-- END QUESTION #1 -->


                        <hr>
                        <!-- DIVIDER LINE -->


                        <!-- QUESTION #2 -->
                        <div class="question row wow fadeInUp">

                            <!-- Question -->
                            <div class="col-lg-6">
                                <h5 class="h5-md">{{ __('Who should I choose as my stylist?') }}</h5>
                            </div>

                            <!-- Answer -->
                            <div class="col-lg-6">

                                <!-- Text -->
                                <p class="mb-0">{{ __('Each of our stylists specializes in different techniques and styles. We recommend viewing our stylist profiles on our website to learn about their expertise and specialties. For new clients, we offer a complimentary consultation to match you with the stylist who best suits your hair type, desired style, and personal preferences.') }}</p>

                            </div>

                        </div>
                        <!-- END QUESTION #2 -->


                        <hr>
                        <!-- DIVIDER LINE -->


                        <!-- QUESTION #3 -->
                        <div class="question row wow fadeInUp">

                            <!-- Question -->
                            <div class="col-lg-6">
                                <h5 class="h5-md">{{ __('If I need to cancel my appointment, what should I do?') }}</h5>
                            </div>

                            <!-- Answer -->
                            <div class="col-lg-6">

                                <!-- List -->
                                <ul class="simple-list">

                                    <li class="list-item">
                                        <p>{{ __('We require at least 24 hours notice for cancellations. You can cancel through our online booking system or by calling the salon directly during business hours.') }}</p>
                                    </li>

                                </ul>

                            </div>

                        </div>
                        <!-- END QUESTION #3 -->


                        <hr>
                        <!-- DIVIDER LINE -->


                        <!-- QUESTION #4 -->
                        <div class="question row wow fadeInUp">

                            <!-- Question -->
                            <div class="col-lg-6">
                                <h5 class="h5-md">{{ __('Do you take coupons, gift certificates, or gift cards?') }}</h5>
                            </div>

                            <!-- Answer -->
                            <div class="col-lg-6">

                                <!-- Text -->
                                <p class="mb-0">{{ __('Yes, we accept gift cards and certificates purchased directly from our salon. We also run seasonal promotions and special offers that can be redeemed at the time of service. All gift cards are valid for one year from the date of purchase and cannot be replaced if lost or stolen.') }}</p>

                            </div>

                        </div>
                        <!-- END QUESTION #4 -->


                        <hr>
                        <!-- DIVIDER LINE -->


                        <!-- QUESTION #5 -->
                        <div class="question row wow fadeInUp">

                            <!-- Question -->
                            <div class="col-lg-6">
                                <h5 class="h5-md">{{ __('What brand of products do you carry?') }}</h5>
                            </div>

                            <!-- Answer -->
                            <div class="col-lg-6">

                                <!-- Text -->
                                <p>{{ __('We pride ourselves on using only premium, professional-grade hair care products in our salon.') }}</p>

                                <!-- List -->
                                <ul class="simple-list">

                                    <li class="list-item">
                                        <p>{{ __('Our stylists work with leading brands including Kerastase, Olaplex, Redken, and Kevin Murphy to ensure the best results for various hair types and concerns.') }}</p>
                                    </li>

                                    <li class="list-item">
                                        <p class="mb-0">{{ __('All products used during your service are available for purchase in our salon, and our stylists will be happy to recommend the best options for maintaining your new look at home.') }}</p>
                                    </li>

                                </ul>

                            </div>

                        </div>
                        <!-- END QUESTION #5 -->


                        <hr>
                        <!-- DIVIDER LINE -->


                    </div>
                </div>
            </div>
            <!-- END QUESTIONS WRAPPER -->


            <!-- BUTTON -->
            <div class="row">
                <div class="col">
                    <div class="more-btn mt-4">
                        <a href="{{ route('contact') }}" class="btn btn--tra-black hover--black">{{ __('Have More Beauty Questions?') }}</a>
                    </div>
                </div>
            </div>
            <!-- END BUTTON -->


        </div>
        <!-- End container -->
    </section>
    <!-- FAQs-1 -->


 --}}


    <!-- Cart Float Component -->
    {{-- @include('components.cart-float') --}}
@endsection
