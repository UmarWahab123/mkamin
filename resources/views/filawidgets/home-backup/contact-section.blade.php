<!-- Home Page Contact Section
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<section id="contacts-2" class="py-8 contacts-section division" style="background-color: {{ $contactSectionContent['backgroundColor'] }}">
    <div class="container">
        <div class="row d-flex align-items-center" style="color: {{ $contactSectionContent['textColor'] }}">
            <!-- HOURS & LOCATION -->
            <div class="col-lg-4">
                <!-- WORKING HOURS -->
                <div class="cbox-2 cb-1 mb-5">
                    <!-- Title -->
                    <h4 style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['hoursTitle']) }}</h4>
                    <!-- Text -->
                    @foreach($workingHours as $hours)
                        <p>
                            <span style="display: inline-block; width: 100px; color: {{ $contactSectionContent['textColor'] }}">{{ __($hours['day']) }} </span>
                            <span style="color: {{ $contactSectionContent['textColor'] }}">{{ __($hours['time']) }}</span>
                        </p>
                    @endforeach
                </div>

                <!-- LOCATION -->
                <div class="cbox-2 cb-2">
                    <!-- Title -->
                    <h4 style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationTitle']) }}</h4>
                    <!-- Address -->
                    <p style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationAr']) }}</p>
                    <p style="color: {{ $contactSectionContent['textColor'] }}">{{ __($contactSectionContent['locationEn']) }}</p>
                    <!-- Contacts -->
                    <div class="cbox-2-contacts">
                        <p><a href="tel:{{ $contactSectionContent['phoneNo1'] }}" style="color: {{ $contactSectionContent['textColor'] }}">{{ $contactSectionContent['phoneNo1'] }}</a></p>
                        <p><a href="tel:{{ $contactSectionContent['phoneNo2'] }}" style="color: {{ $contactSectionContent['textColor'] }}">{{ $contactSectionContent['phoneNo2'] }}</a></p>
                    </div>
                </div>
            </div>
            <!-- END HOURS & LOCATION -->

            <!-- GOOGLE MAP -->
            <div class="col-lg-8">
                <div class="google-map">
                    <iframe
                        src="{{ $contactSectionContent['mapSrc'] }}"
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
