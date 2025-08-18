@php
    use App\Models\HeaderSettings;
    $header = HeaderSettings::first();
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

<div>
    {{-- Header Preview --}}
    <header class="flex justify-between items-center px-6 py-4 bg-red dark-menu navbar-light white-scroll">
        {{-- <?php dd($desktop_logo); ?> --}}
        {{-- @if (!empty($desktop_logo))
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . $desktop_logo) }}" alt="Logo" class="h-10">
            </div>
        @else
            <div class="flex items-center gap-4">
                <img src="{{ returnLogoForDarkBackground() }}" alt="Logo" class="h-10">
            </div>
        @endif --}}
        {{-- <div class="flex items-center gap-4">
            <img src="{{ returnLogoForDarkBackground() }}" alt="Logo" class="h-10">
        </div> --}}

        @if (!empty($navigation_links))

            <div class="flex items-center gap-6">
                @foreach ($navigation_links as $navLink)
                    @if ($navLink['active'] ?? true)
                        <a href="{{ $navLink['url'] ?? '#' }}" class="text-gray-600 hover:text-blue-600">
                            {{ __($navLink['label']) }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- <nav class="flex gap-6 text-sm font-medium">
            <a href="#" class="hover:text-blue-600">Home</a>
            <a href="#" class="hover:text-blue-600">About</a>
            <a href="#" class="hover:text-blue-600">Services</a>
            <a href="#" class="hover:text-blue-600">Contact</a>
        </nav> --}}

        <div class="flex items-center gap-4">
            <a href="#" class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-user"></i>
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </header>
</div>
