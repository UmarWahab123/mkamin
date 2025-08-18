@php use Illuminate\Support\Str; @endphp
<section class="pt-8 ct-03 content-section division">
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <div class="txt-block left-column wow fadeInRight">
                    <div class="ct-03-txt">
                        <span class="section-id">{{ __($content['left_section_id'] ?? 'Mind, Body and Soul') }}</span>
                        <h2 class="h2-md">{{ __($content['left_title'] ?? 'Luxury salon where you will feel unique') }}</h2>
                        <p class="mb-5">{{ __($content['left_body'] ?? '') }}</p>
                    </div>

                    @if(!empty($content['left_image']))
                        @php
                            $leftImage = $content['left_image'];
                            $leftImageUrl = Str::startsWith($leftImage, ['/','http']) ? asset($leftImage) : asset('storage/'.$leftImage);
                        @endphp
                        <div class="ct-03-img">
                            <img class="img-fluid" src="{{ $leftImageUrl }}" alt="content-image">
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="txt-block right-column wow fadeInLeft">
                    @if(!empty($content['right_image']))
                        @php
                            $rightImage = $content['right_image'];
                            $rightImageUrl = Str::startsWith($rightImage, ['/','http']) ? asset($rightImage) : asset('storage/'.$rightImage);
                        @endphp
                        <div class="ct-03-img mb-5">
                            <img class="img-fluid" src="{{ $rightImageUrl }}" alt="content-image">
                        </div>
                    @endif

                    <div class="ct-03-txt">
                        <p class="mb-0">{{ __($content['right_body'] ?? '') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
