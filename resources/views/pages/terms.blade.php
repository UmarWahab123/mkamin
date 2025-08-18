@extends('layouts.app')

@section('title', __('Terms and Conditions - mcs.sa Salon'))

@section('content')

    <!-- INNER PAGE TITLE -->
    <section id="terms-page" class="pb-6 inner-page-title division">
        <div class="container py-5 rounded-3 shadow">
            <div class="row">
                <div class="col">
                    <div class="page-title-txt">
                        <h2>{{ __('Terms and Conditions') }}</h2>
                        <p>{{ __('Please read our privacy policy carefully') }}</p>
                    </div>
                </div>
            </div>
            <div class="row text-start mt-6">
                <div class="col-lg-10 offset-lg-1">
                    <div class="terms-box">
                        <h3 class="h3-md">{{ __('Privacy Policy – Maya Colors Ladies Beauty Salon') }}</h3>
                        <p>{{ __('At Maya Colors Salon, we value your privacy greatly. This policy explains how we collect, use, and protect your personal information when you use our website.') }}
                        </p>

                        <h4 class="h4-xs mt-5">1. {{ __('Information We Collect') }}:</h4>
                        <ul class="simple-list">
                            <li>{{ __('Name, phone number, and email address.') }}</li>
                            <li>{{ __('Booking and appointment details.') }}</li>
                            <li>{{ __('Any information you provide when contacting us through forms or email.') }}</li>
                        </ul>

                        <h4 class="h4-xs mt-4">2. {{ __('How We Use Information') }}:</h4>
                        <ul class="simple-list">
                            <li>{{ __('To confirm and manage appointments.') }}</li>
                            <li>{{ __('To improve our services and your user experience.') }}</li>
                            <li>{{ __('To respond to your inquiries and requests.') }}</li>
                            <li>{{ __('To send promotional offers (with your prior consent).') }}</li>
                        </ul>

                        <h4 class="h4-xs mt-4">3. {{ __('Information Sharing') }}:</h4>
                        <p>{{ __('We do not share your personal information with any third party, unless required legally or to improve service (such as electronic booking systems).') }}
                        </p>

                        <h4 class="h4-xs mt-4">4. {{ __('Information Protection') }}:</h4>
                        <p>{{ __('We use technical and organizational security measures to protect your data from unauthorized access or use.') }}
                        </p>

                        <h4 class="h4-xs mt-4">5. {{ __('Cookies') }}:</h4>
                        <p>{{ __('The website may use cookies to improve your experience, and you can control browser settings to disable them.') }}
                        </p>

                        <h4 class="h4-xs mt-4">6. {{ __('Your Rights') }}:</h4>
                        <p>{{ __('You can request to modify or delete your personal data at any time by contacting us.') }}
                        </p>

                        <h4 class="h4-xs mt-4">7. {{ __('Amendments') }}:</h4>
                        <p>{{ __('We may update this privacy policy from time to time, and changes will be posted on this page.') }}
                        </p>

                        <h4 class="h4-xs mt-4">{{ __('Contact Us') }}:</h4>
                        <p>{{ __('If you have any questions or inquiries about the privacy policy, please contact us via') }}:
                        </p>
                        <p>{{ $defaultEmail }}<br>
                            {{ $defaultPhoneNumber }}<br>
                            {{ $salonLocation }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END INNER PAGE TITLE -->


    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection
