@php
    use App\Models\FooterSetting;
    $footer = App\Models\FooterSetting::first();
@endphp

<footer id="footer-2" class="py-6 footer division">
    <div class="container text-center color--black">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- FOOTER SOCIALS -->
                @if(!empty($footer->social_links))
                    <ul class="bottom-footer-socials ico-30 clearfix">
                        @foreach($footer->social_links as $socialLink)
                            @if($socialLink['active'] ?? true)
                                @php
                                    $platform = collect(FooterSetting::socialPlatforms())
                                        ->firstWhere('name', $socialLink['platform']);
                                @endphp
                                <li>
                                    <a href="{{ $socialLink['url'] }}" target="_blank">
                                        <span class="fa-brands {{ $platform['icon'] ?? 'fa-link' }}"></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                <!-- FOOTER LINKS -->
                @if(!empty($footer->navigation_links))
                    <div class="footer-links">
                        <ul class="foo-links clearfix">
                            @foreach($footer->navigation_links as $navLink)
                                @if($navLink['active'] ?? true)
                                    <li>
                                        <p><a href="{{ $navLink['url'] }}">{{ __($navLink['label']) }}</a></p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- FOOTER COPYRIGHT -->
        <div class="bottom-footer">
            <div class="row">
                <div class="col">
                    <div class="footer-copyright">
                        <p>&copy; {{ __($footer->copyright_text) }}</p>
                        <p>&copy; {{ __('Designed by') }} <a href="{{ $footer->designer_url }}" target="_blank">{{ $footer->designer_text }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>