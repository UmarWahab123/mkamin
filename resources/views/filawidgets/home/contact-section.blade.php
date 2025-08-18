<!-- Home Page Contact Section - DYNAMIC WIDGET
============================================= -->
{{-- @php
use App\Helpers\WidgetHelper;
dd($section->content);
dd($workingHours);
@endphp --}}
<section id="contacts-2" class="py-8 contacts-section division" style="background-color: {{ $section->content['backgroundColor'] ?? '#f8f9fa' }}">
    <div class="container">
        <div class="row d-flex align-items-center" style="color: {{ $section->content['textColor'] ?? '#333' }}">
            <!-- HOURS & LOCATION -->
            <div class="col-lg-4">
                <!-- WORKING HOURS -->
                <div class="cbox-2 cb-1 mb-5">
                    <!-- Title -->
                    <h4 style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($section->content['hoursTitle']) ? __($section->content['hoursTitle']) : __('Working Hours') }}</h4>
                    <!-- Text -->
                    @if(isset($workingHours) && is_array($workingHours) && count($workingHours) > 0)
                        @foreach($workingHours as $hours)
                            <p>
                                <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($hours['day']) ? __($hours['day']) : __('Day') }} </span>
                                <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($hours['time']) ? __($hours['time']) : __('Time') }}</span>
                            </p>
                        @endforeach
                    @elseif(isset($section->content['workingHours']) && is_array($section->content['workingHours']) && count($section->content['workingHours']) > 0)
                        @foreach($section->content['workingHours'] as $hours)
                            <p>
                                <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($hours['day']) ? __($hours['day']) : __('Day') }} </span>
                                <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($hours['time']) ? __($hours['time']) : __('Time') }}</span>
                            </p>
                        @endforeach
                    @else
                        <!-- DEFAULT WORKING HOURS IF NO DATA AVAILABLE -->
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Sunday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Monday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Tuesday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Wednesday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Thursday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Friday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('2:00 PM - 9:00 PM') }}</span>
                        </p>
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('Saturday') }} </span>
                            <span style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ __('9:00 AM - 9:00 PM') }}</span>
                        </p>
                    @endif
                </div>

                <!-- LOCATION -->
                <div class="cbox-2 cb-2">
                    <!-- Title -->
                    <h4 style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($section->content['locationTitle']) ? __($section->content['locationTitle']) : __('Our Location') }}</h4>
                    <!-- Address -->
                    <p style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($section->content['locationAr']) ? __($section->content['locationAr']) : __('الرياض، المملكة العربية السعودية') }}</p>
                    <p style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ isset($section->content['locationEn']) ? __($section->content['locationEn']) : __('Riyadh, Saudi Arabia') }}</p>
                    <!-- Contacts -->
                    <div class="cbox-2-contacts">
                        @if(isset($section->content['phoneNo1']) && $section->content['phoneNo1'])
                            <p><a href="tel:{{ $section->content['phoneNo1'] }}" style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ $section->content['phoneNo1'] }}</a></p>
                        @endif
                        @if(isset($section->content['phoneNo2']) && $section->content['phoneNo2'])
                            <p><a href="tel:{{ $section->content['phoneNo2'] }}" style="color: {{ $section->content['textColor'] ?? '#333' }}">{{ $section->content['phoneNo2'] }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- END HOURS & LOCATION -->

            <!-- GOOGLE MAP -->
            <div class="col-lg-8">
                <div class="google-map">
                    <iframe
                        src="{{ $section->content['mapSrc'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.140396280634!2d46.6856!3d24.7136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e79fd5!2sRiyadh%20Saudi%20Arabia!5e0!3m2!1sen!2s!4v1629000000000!5m2!1sen!2s' }}"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <!-- END GOOGLE MAP -->
        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Contact Section -->