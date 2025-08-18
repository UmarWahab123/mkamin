@php use Illuminate\Support\Str; @endphp
<section class="pt-8 ct-05 content-section">
    <div class="container stone--shape">
        <div class="row d-flex align-items-center">
            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-block left-column wow fadeInRight">
                    <span class="section-id">{{ __($content['features_section_id'] ?? 'You Are Beauty') }}</span>
                    <h2 class="h2-md">{{ __($content['features_title'] ?? 'Give the pleasure of beautiful to yourself') }}</h2>

                    <div class="accordion accordion-wrapper mt-5">
                        <ul class="accordion">
                            @foreach($content['features_accordion'] ?? [] as $index => $item)
                                <li class="accordion-item {{ $index === 0 ? 'is-active' : '' }}">
                                    <div class="accordion-thumb">
                                        <p>{{ __($item['title'] ?? '') }}</p>
                                    </div>
                                    <div class="accordion-panel">
                                        <p class="mb-0">{{ __($item['content'] ?? '') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-lg-6 order-first order-lg-2">
                <div class="ct-05-img right-column wow fadeInLeft">
                    @if(!empty($content['features_image']))
                        @php
                            $img = $content['features_image'];
                            $imgUrl = Str::startsWith($img, ['/','http']) ? asset($img) : asset('storage/'.$img);
                        @endphp
                        <img class="img-fluid" src="{{ $imgUrl }}" alt="content-image">
                    @else
                        <img class="img-fluid" src="/assets/images/woman_02.jpg" alt="content-image">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
