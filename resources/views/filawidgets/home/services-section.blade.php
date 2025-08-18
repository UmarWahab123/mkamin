<!-- Home Page Services Section - DYNAMIC WIDGET
============================================= -->
{{-- @php
    use App\Helpers\WidgetHelper;
    dd(getImageUrl($section->content['services'][0]['image']));
    dd($section->content);
@endphp --}}
<section id="services-2" class="pt-8 services-section division">
    <div class="container">
        <!-- Home Page Services Section WRAPPER -->
        <div class="sbox-2-wrapper text-center">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">

                @if(isset($section->content['services']) && is_array($section->content['services']))
                    @foreach ($section->content['services'] as $index => $service)
                        <!-- SERVICES BOX #{{ $index + 1 }} -->
                        <div class="col">
                            <div class="sbox-2 sb-{{ $index + 1 }} wow fadeInUp">
                                <!-- Icon or Image -->
                                <div class="sbox-ico ico-65">
                                    @if (isset($service['image']) && $service['image'])
                                        <img src="{{ getImageUrl($service['image']) }}"
                                            alt="{{ $service['title'] ?? 'Service Image' }}" class="service-img">
                                    @else
                                        <span class="{{ $service['icon'] ?? 'flaticon-beauty' }} color--gold"></span>
                                    @endif
                                </div>
                                <!-- Text -->
                                <div class="sbox-txt">
                                    <h5 class="h5-lg" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                                        {{ isset($service['title']) ? __($service['title']) : __('Beauty Service') }}</h5>
                                    <p style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                        {{ isset($service['description']) ? __($service['description']) : __('Professional beauty service description') }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- END SERVICES BOX #{{ $index + 1 }} -->
                    @endforeach
                @else
                    <!-- DEFAULT SERVICES IF NO DATA AVAILABLE -->
                    <div class="col">
                        <div class="sbox-2 sb-1 wow fadeInUp">
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-facial-treatment color--gold"></span>
                            </div>
                            <div class="sbox-txt">
                                <h5 class="h5-lg" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                                    {{ __('Facial Treatments') }}</h5>
                                <p style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                    {{ __('Rejuvenating facial treatments for glowing, healthy skin') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="sbox-2 sb-2 wow fadeInUp">
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-hair-brush color--gold"></span>
                            </div>
                            <div class="sbox-txt">
                                <h5 class="h5-lg" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                                    {{ __('Hair Styling') }}</h5>
                                <p style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                    {{ __('Professional hair cutting, coloring and styling services') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="sbox-2 sb-3 wow fadeInUp">
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-foundation color--gold"></span>
                            </div>
                            <div class="sbox-txt">
                                <h5 class="h5-lg" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                                    {{ __('Nail Care') }}</h5>
                                <p style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                    {{ __('Complete manicure and pedicure services') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="sbox-2 sb-4 wow fadeInUp">
                            <div class="sbox-ico ico-65">
                                <span class="flaticon-cosmetics color--gold"></span>
                            </div>
                            <div class="sbox-txt">
                                <h5 class="h5-lg" style="color: {{ $section->content['titleColor'] ?? '#363636' }}">
                                    {{ __('Makeup Services') }}</h5>
                                <p style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                    {{ __('Professional makeup for all special occasions') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <!-- End row -->
        </div>
        <!-- END Home Page Services Section WRAPPER -->
    </div>
    <!-- End container -->
</section>
<!-- END Home Page Services Section -->