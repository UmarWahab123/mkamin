{{-- <div class="p-4 bg-white rounded-lg shadow">
    <h3 class="text-lg font-medium mb-4">Footer Preview</h3>
    
    <div class="space-y-4">
        <!-- Social Media -->
        @if (!empty($social_links))
            <div>
                <h4 class="font-medium mb-2">Social Media</h4>
                <ul class="flex space-x-4">
                    @foreach ($social_links as $socialLink)
                        @if (($socialLink['active'] ?? true) && !empty($socialLink['platform']))
                            @php
                                $platform = collect(\App\Models\FooterSetting::socialPlatforms())
                                    ->firstWhere('name', $socialLink['platform']);
                            @endphp
                            <li>
                                <a href="{{ $socialLink['url'] ?? '#' }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    @if ($platform && isset($platform['icon']))
                                        <i class="fab fa-facebook text-xl"></i>
                                    @else
                                        <i class="fas fa-link text-xl"></i>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Navigation Links -->
        @if (!empty($navigation_links))
            <div>
                <h4 class="font-medium mb-2">Navigation Links</h4>
                <ul class="flex flex-wrap gap-4">
                    @foreach ($navigation_links as $navLink)
                        @if (($navLink['active'] ?? true) && !empty($navLink['label']))
                            <li>
                                <a href="{{ $navLink['url'] ?? '#' }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $navLink['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Copyright -->
        <div>
            <h4 class="font-medium mb-2">Copyright</h4>
            <div class="text-sm text-gray-600 space-y-1">
                <p>&copy; {{ $copyright_text ?: '2025 mcs.sa. All Rights Reserved' }}</p>
                <p>
                    <a href="{{ $designer_url ?: '#' }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                        {{ $designer_text ?: 'Designed by SWU' }}
                    </a>
                </p>
            </div>
        </div>
            
    </div>
</div> --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@php
    use App\Models\FooterSetting;
    $footer = App\Models\FooterSetting::first();
@endphp

<footer id="footer-2" class="py-6 footer division">
    <div class="container text-center color--black">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if (!empty($social_links))
                    <ul class="bottom-footer-socials ico-30 clearfix">
                        @foreach ($social_links as $socialLink)
                            @if (($socialLink['active'] ?? true) && !empty($socialLink['platform']))
                                @php
                                    $platform = collect(FooterSetting::socialPlatforms())->firstWhere(
                                        'name',
                                        $socialLink['platform'],
                                    );
                                @endphp
                                <li>
                                    <a href="{{ $socialLink['url'] ?? '#' }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">
                                        @if ($platform && isset($platform['icon']))
                                            <i class="fab {{ $platform['icon'] }} text-xl"></i>
                                        @else
                                            <i class="fas fa-link text-xl"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                @if (!empty($navigation_links))
                    <div class="footer-links">
                        <ul class="foo-links clearfix">
                            @foreach ($navigation_links as $navLink)
                                @if (($navLink['active'] ?? true) && !empty($navLink['label']))
                                    <li>
                                        <p><a href="{{ $navLink['url'] ?? '#' }}">{{ __($navLink['label']) }}</a></p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                @endif


                <!-- FOOTER SOCIALS -->
                {{-- @if (!empty($footer->social_links))
                    <ul class="bottom-footer-socials ico-30 clearfix">
                        @foreach ($footer->social_links as $socialLink)
                            @if ($socialLink['active'] ?? true)
                                @php
                                    $platform = collect(FooterSetting::socialPlatforms())->firstWhere(
                                        'name',
                                        $socialLink['platform'],
                                    );
                                @endphp
                                <li>
                                    <a href="{{ $socialLink['url'] }}" target="_blank">
                                        <span class="fa-brands {{ $platform['icon'] ?? 'fa-link' }}"></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif --}}

                <!-- FOOTER LINKS -->
                {{-- @if (!empty($footer->navigation_links))
                    <div class="footer-links">
                        <ul class="foo-links clearfix">
                            @foreach ($footer->navigation_links as $navLink)
                                @if ($navLink['active'] ?? true)
                                    <li>
                                        <p><a href="{{ $navLink['url'] }}">{{ __($navLink['label']) }}</a></p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
            </div>
        </div>

        <!-- FOOTER COPYRIGHT -->
        {{-- <div class="bottom-footer">
            <div class="row">
                <div class="col">
                    <div class="footer-copyright">
                        <p>&copy; {{ __($footer->copyright_text) }}</p>
                        <p>&copy; {{ __('Designed by') }} <a href="{{ $footer->designer_url }}" target="_blank">{{ $footer->designer_text }}</a></p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</footer>
