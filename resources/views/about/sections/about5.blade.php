@php use Illuminate\Support\Str; @endphp
<section class="pt-8 about-5 about-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div id="ab-5-1" class="about-5-img">
                    @if(!empty($content['image_1']))
                        @php $img= $content['image_1']; $url = Str::startsWith($img, ['/','http']) ? asset($img) : asset('storage/'.$img); @endphp
                        <img class="img-fluid" src="{{ $url }}" alt="about-image">
                    @else
                        <img class="img-fluid" src="/assets/images/beauty_02.jpg" alt="about-image">
                    @endif
                </div>
            </div>

            <div class="col-md-8 col-lg-7 order-first order-md-1">
                <div class="txt-block">
                    <span class="section-id">{{ __($content['small_title'] ?? 'Be Irresistible') }}</span>
                    <h2 class="h2-title">{{ __($content['title'] ?? 'The Ultimate Relaxation for Your Mind and Body') }}</h2>

                    @if(!empty($content['image_2']))
                        @php $img2 = $content['image_2']; $url2 = Str::startsWith($img2, ['/','http']) ? asset($img2) : asset('storage/'.$img2); @endphp
                        <div id="ab-5-2" class="about-5-img">
                            <img class="img-fluid" src="{{ $url2 }}" alt="about-image">
                        </div>
                    @endif
                </div>
            </div>

            <div class="col order-last order-md-2">
                <div id="ab-5-3" class="about-5-img">
                    @if(!empty($content['image_3']))
                        @php $img3 = $content['image_3']; $url3 = Str::startsWith($img3, ['/','http']) ? asset($img3) : asset('storage/'.$img3); @endphp
                        <img class="img-fluid" src="{{ $url3 }}" alt="about-image">
                    @else
                        <img class="img-fluid" src="/assets/images/beauty_04.jpg" alt="about-image">
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
