@php
    use App\Models\HeaderSettings;
    $header = HeaderSettings::first();
@endphp

<header id="header" class="dark-menu navbar-light ">
    <div class="header-wrapper">
        <!-- MOBILE HEADER -->
        <div class="wsmobileheader clearfix">
            <span class="smllogo">
                <a href="{{ route('home') }}" class="logo-white"><img src="{{ returnMobileLogoFromDb() }}"
                        alt="{{ __('mobile-logo') }}"></a>
            </span>
            <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        </div>

        <!-- NAVIGATION MENU -->
        <div class="wsmainfull menu clearfix">
            <div class="wsmainwp clearfix">

                <!-- HEADER WHITE LOGO -->
                <div class="desktoplogo">
                    <a href="{{ route('home') }}" class="logo-white"><img src="{{ returnDesktopLogoFromDb() }}"
                            alt="{{ __('logo') }}"></a>
                </div>

                <!-- MAIN MENU -->
                <nav class="wsmenu clearfix ps-5">
                    <ul class="wsmenu-list nav-gold">

                        <!-- SIMPLE NAVIGATION LINK -->
                        @if (!empty($header->navigation_links))

                            @foreach ($header->navigation_links as $navLink)
                                @if (($navLink['active'] ?? true) && !empty($navLink['label']) && empty($navLink['dropdown']))
                                    <li class="nl-simple ms-3" aria-haspopup="true"><a
                                            href="{{ $navLink['url'] ?? '#' }}"
                                            class="mx-0 h-link">{{ __($navLink['label']) }}</a></li>
                                @endif
                                <!-- DROPDOWN MENU -->
                                @if (($navLink['active'] ?? true) && !empty($navLink['label']))
                                    @if (!empty($navLink['dropdown_links']) && $navLink['dropdown'] === true)
                                        <li aria-haspopup="true"><a href="#"
                                                class="mx-0 h-link">{{ __($navLink['label']) }} <span
                                                    class="wsarrow"></span></a>
                                            <ul class="sub-menu">
                                                @foreach ($navLink['dropdown_links'] as $subLink)
                                                    @if ($subLink['active'] ?? true)
                                                        <li aria-haspopup="true"><a
                                                                href="{{ $subLink['url'] }}">{{ __($subLink['label']) }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endif
                                <!-- DROPDOWN MENU END-->
                            @endforeach
                        @endif


                        @auth
                            <li aria-haspopup="true" class="float-end">
                                <a href="#" class="h-link">
                                    <span class="wsarrow">
                                        <i class="fas fa-user-circle fa-lg"></i> {{ __('My Account') }}
                                    </span>
                                </a>
                                <ul class="sub-menu">
                                    <li aria-haspopup="true"><a class="dropdown-item" href="{{ route('user.profile') }}"><i
                                                class="fas fa-user mr-2"></i> {{ __('My Profile') }}</a></li>
                                    @if (auth()->user()->isCustomer())
                                        <li aria-haspopup="true"><a class="dropdown-item"
                                                href="{{ route('customer.bookings') }}"><i
                                                    class="fas fa-calendar-check mr-2"></i> {{ __('My Bookings') }}</a>
                                        </li>
                                    @else
                                        <li aria-haspopup="true"><a class="dropdown-item" href="/admin"><i
                                                    class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}</a></li>
                                    @endif
                                    <li aria-haspopup="true">
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li aria-haspopup="true"><a class="dropdown-item" href="{{ route('logout') }}"><i
                                                class="fas fa-sign-out-alt mr-2"></i> {{ __('Logout') }}</a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nl-simple float-end" aria-haspopup="true"><a href="{{ route('login') }}"
                                    class="btn btn--gold hover--tra-white last-link"><i class="fas fa-sign-in-alt mr-1"></i>
                                    {{ __('Login') }}</a></li>
                        @endauth


                        <!-- LANGUAGE SWITCHER -->
                        <x-language-switcher />

                    </ul>
                </nav>
                <!-- END MAIN MENU -->
            </div>
        </div>
        <!-- END NAVIGATION MENU -->
    </div>
    <!-- End header-wrapper -->

    <!-- Development Banner -->
    <div
        style="position: fixed; bottom: 0; left: 0; background-color: #ff9800; color: white; padding: 5px 10px; font-size: 12px; border-top-right-radius: 5px; z-index: 1000;">
        {{ __('This website is still under development') }}
    </div>
</header>
