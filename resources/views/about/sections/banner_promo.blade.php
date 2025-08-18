@php use Illuminate\Support\Str; @endphp
<section class="pt-8 banner-1 banner-section">
    <div class="container">
        <div class="banner-1-wrapper bg--fixed"
             @if(!empty($content['background']))
                style="background-image: url('{{ Str::startsWith($content['background'], ['/','http']) ? asset($content['background']) : asset('storage/'.$content['background']) }}'); background-size:cover; background-position:center;"
             @endif>
            <div class="row">
                <div class="col">
                    <div class="banner-1-txt text-center color--white">
                        <span class="section-id">{{ __($content['small_title'] ?? 'This Week Only') }}</span>
                        <h2>{{ __($content['title'] ?? 'Get 30% OFF') }}</h2>
                        <h3>{{ __($content['subtitle'] ?? 'Manicure + Gel Polish') }}</h3>
                        <a href="{{ $content['button_link'] ?? route('salon-services') }}" class="btn btn--tra-white hover--white">
                            {{ __($content['button_text'] ?? 'Book an Appointment') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
