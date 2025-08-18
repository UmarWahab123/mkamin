@extends('layouts.app')

@section('title', __('Contact us - mcs.sa Salon'))

@section('content')


    <!-- INNER PAGE TITLE
                        ============================================= -->
    <section id="contacts-page" class="pb-6 inner-page-title division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-title-txt">
                        <h2>{{ __('Let\'s Talk Beauty!') }}</h2>
                        <p>{{ __('Got Questions? Please, don\'t hesitate to get in touch with us') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE TITLE -->


    <!-- Contact Info Section -->

    @include('filawidgets.contact.contact-info-section', ['contactSectionContent' => $contactSectionContent])


    <!-- WORKING HOURS
                                    ============================================= -->
    <!-- Home Page Working Hours Section -->
    @include('filawidgets.common.working-hours-section', ['workingHours' => $workingHours, 'workingHoursContent' => $workingHoursContent, 'qrCode' => $qrCode])
    <!-- END Home Page Working Hours Section -->

    <!-- BANNER-1
                                    ============================================= -->
    <section class="pt-6 banner-1 banner-section">
        <div class="container">
            <div class="banner-1-wrapper banner-1-hair bg--fixed">
                <div class="row">
                    <div class="col">
                        <div class="banner-1-txt text-center color--white">

                            <!-- Section ID -->
                            <span class="section-id">{{ __('This Week Only') }}</span>

                            <!-- Title -->
                            <h2>{{ __('Get') }} <span>{{ __('30% OFF') }}</span></h2>
                            <h3>{{ __('Custom Color Service') }}</h3>

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
