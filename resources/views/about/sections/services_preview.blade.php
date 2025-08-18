@php use Illuminate\Support\Str; @endphp
<div id="services-3" class="pt-8 services-section division">
    <div class="container">
        <div class="sbox-3-wrapper text-center">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6">
                @foreach($content['services'] ?? [] as $index => $service)
                    <div class="col">
                        <div class="sbox-3 sb-{{ $index + 1 }} wow fadeInUp">
                            <div class="sbox-ico ico-65">
                                <span class="{{ $service['icon_class'] ?? 'flaticon-placeholder' }} color--black"></span>
                            </div>
                            <div class="sbox-txt">
                                <p>{{ __($service['name'] ?? '') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="more-btn mt-5">
                    <a href="{{ route('menu') }}" class="btn btn--tra-black hover--black">{{ __($content['button_text'] ?? 'View Our Menu') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
