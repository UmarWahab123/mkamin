<!-- CONTACTS-1
============================================= -->
<div id="contacts-1" class="contacts-section division">
    <div class="container">
        <!-- GOOGLE MAP -->
        <div class="row">
            <div class="col">
                <div class="google-map">
                    <iframe src="{{ $contactSectionContent['mapSrc'] }}" width="1300" height="450" style="border:0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <!-- END GOOGLE MAP -->
    </div>
    <!-- End container -->
</div>
<!-- END CONTACTS-1 -->

<!-- CONTACTS-3
============================================= -->
<section id="contacts-3" class="pt-8 contacts-section division">
    <div class="container">
        <div class="row">
            <!-- CONTACT INFO -->
            <div class="col-lg-4">
                <!-- LOCATION -->
                <div class="cbox-2 cb-1 mb-5">
                    <!-- Title -->
                    <h4>{{ __($contactSectionContent['locationTitle']) }}</h4>

                    <!-- Address -->
                    <p>{{ __($contactSectionContent['locationEn']) }}</p>
                    <p>{{ __($contactSectionContent['locationAr']) }}</p>

                    <!-- Contacts -->
                    <div class="cbox-2-contacts">
                        <p><a
                                href="tel:{{ $contactSectionContent['phoneNo1'] }}">{{ $contactSectionContent['phoneNo1'] }}</a>
                        </p>
                        @if ($contactSectionContent['phoneNo2'])
                            <p><a
                                    href="tel:{{ $contactSectionContent['phoneNo2'] }}">{{ $contactSectionContent['phoneNo2'] }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- END CONTACT INFO -->

            <!-- CONTACT FORM -->
            <div class="col-lg-8">
                <div class="contact-form-wrapper">
                    <!-- Title -->
                    <h4>{{ __('Send a Message') }}</h4>

                    <!-- Contact Form -->
                    <div id="form-container">
                        <form name="contactform" class="row contact-form" id="contact-form">
                            @if(auth()->check())
                             <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            @endif
                            <!-- Form Input -->
                            <div class="col-lg-6">
                                <input type="text" name="name" class="form-control name"
                                    placeholder="{{ __('Your Name*') }}" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                            </div>

                            <!-- Form Input -->
                            <div class="col-lg-6">
                                <input type="email" name="email" class="form-control email"
                                    placeholder="{{ __('Email Address*') }}" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                            </div>

                            <!-- Form Input -->
                            <div class="col-md-12">
                                <input type="text" name="subject" class="form-control subject"
                                    placeholder="{{ __('What\'s this about?') }}">
                            </div>

                            <!-- Form Textarea -->
                            <div class="col-md-12">
                                <textarea name="message" class="form-control message" rows="6" placeholder="{{ __('Your Message ...') }}"></textarea>
                            </div>

                            <!-- Form Button -->
                            <div class="col-md-12 text-end">
                                <button type="submit"
                                    class="btn btn--tra-black hover--black submit">{{ __('Send Message') }}</button>
                            </div>

                            <!-- Form Message -->
                            <div class="col-md-12 contact-form-msg">
                                <div class="sending-msg"><span class="loading"></span></div>
                            </div>
                        </form>
                    </div>

                    <!-- Thank You Message (initially hidden) -->
                    <div id="thank-you-message" style="display: none; text-align: center;">
                        <div class="success-animation">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                        <h3 class="mt-4">{{ __('Thank You!') }}</h3>
                        <p class="mb-4">{{ __('Your message has been sent successfully. We will get back to you soon!') }}</p>
                        <a href="{{ route('salon-services') }}" class="btn btn--tra-black hover--black">{{ __('Explore our Services') }}</a>
                    </div>

                </div>
            </div>
            <!-- END CONTACT FORM -->
        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END CONTACTS-3 -->

@push('scripts')
<style>
    .success-animation {
        margin: 20px auto;
        width: 80px;
        height: 80px;
    }
    .checkmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #4bb71b;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px #4bb71b;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4bb71b;
        fill: none;
        animation: stroke .6s cubic-bezier(0.650, 0.000, 0.450, 1.000) forwards;
    }
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke .3s cubic-bezier(0.650, 0.000, 0.450, 1.000) .8s forwards;
    }
    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #4bb71b;
        }
    }
    #thank-you-message {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    #thank-you-message.show {
        opacity: 1;
    }
</style>
<script>
    $(document).ready(function() {
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var formData = $form.serializeArray();
            var jsonData = {};

            $.each(formData, function() {
                jsonData[this.name] = this.value;
            });

            var $submitBtn = $form.find('button[type="submit"]');
            var $sendingMsg = $form.find('.sending-msg');

            // Disable button and show loading
            $submitBtn.prop('disabled', true);
            $sendingMsg.html('<span class="loading">Sending message...</span>');

            $.ajax({
                url: '/api/contact',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(jsonData),
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Hide the form with a fade effect
                        $('#form-container').fadeOut(300, function() {
                            // Show the thank you message with animation
                            $('#thank-you-message').css('display', 'block');
                            setTimeout(function() {
                                $('#thank-you-message').addClass('show');
                            }, 50);
                        });

                        // Reset form for future use
                        $form[0].reset();
                        // If user is logged in, restore their pre-filled data for future submissions
                        @if(auth()->check())
                            $form.find('input[name="name"]').val('{{ auth()->user()->name }}');
                            $form.find('input[name="email"]').val('{{ auth()->user()->email }}');
                            $form.find('input[name="user_id"]').val('{{ auth()->id() }}');
                        @endif
                    } else {
                        $sendingMsg.html('<span class="text-danger">Error sending message. Please try again.</span>');
                    }
                },
                error: function(xhr, status, error) {
                    $sendingMsg.html('<span class="text-danger">Error sending message. Please try again.</span>');
                    console.error('Error:', error);
                },
                complete: function() {
                    // Re-enable button
                    $submitBtn.prop('disabled', false);
                }
            });
        });

    });
    </script>
@endpush
