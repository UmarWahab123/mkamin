<!-- Home Page Services Section
============================================= -->
@php
    use App\Helpers\WidgetHelper;
@endphp
<section id="services-2" class="pt-8 services-section division">
    <div class="container">
        <!-- Home Page Services Section WRAPPER -->
        <div class="sbox-2-wrapper text-center">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">

                @foreach ($servicesContent['services'] as $index => $service)
                    <!-- SERVICES BOX #{{ $index + 1 }} -->
                    <div class="col">
                        <div class="sbox-2 sb-{{ $index + 1 }} wow fadeInUp">
                            <!-- Icon or Image -->
                            <div class="sbox-ico ico-65">
                                @if ($service['image'])
                                    <img src="{{ WidgetHelper::getImageUrl($service['image']) }}"
                                        alt="{{ $service['title'] }}" class="service-img">
                                @else
                                    <span class="{{ $service['icon'] }} color--gold"></span>
                                @endif
                            </div>
                            <!-- Text -->
                            <div class="sbox-txt">
                                <h5 class="h5-lg" style="color: {{ $servicesContent['titleColor'] }}">
                                    {{ __($service['title']) }}</h5>
                                <p style="color: {{ $servicesContent['descriptionColor'] }}">
                                    {{ __($service['description']) }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- END SERVICES BOX #{{ $index + 1 }} -->
                @endforeach

            </div>
            <!-- End row -->
        </div>
        <!-- END Home Page Services Section WRAPPER -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Services Section -->
