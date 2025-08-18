@php use Illuminate\Support\Str; @endphp
<section id="about-page" class="inner-page-hero division"
    @if(!empty($content['background_image']))
        style="background-image: url('{{ Str::startsWith($content['background_image'], ['/','http']) ? asset($content['background_image']) : asset('storage/'.$content['background_image']) }}'); background-size:cover; background-position:center;"
    @endif>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-hero-txt color--white">
                    <h2>{{ __($content['title'] ?? 'About mcs.sa') }}</h2>
                    <p>{{ __($content['description'] ?? 'Luxury salon where you will feel unique and special') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
