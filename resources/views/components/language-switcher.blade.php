@php
    $currentLocale = app()->getLocale();
    $languages = \App\Models\Language::where('is_active', true)->where('show_in_navbar', true)->get();
    $currentLanguage = $languages->where('code', $currentLocale)->first();
@endphp

@if ($languages->count() > 1)
    <li aria-haspopup="true" class="float-end">
        <a href="#" class="h-link">
            <span class="wsarrow">
                @if ($currentLanguage && $currentLanguage->image)
                    <img src="{{ Storage::url($currentLanguage->image) }}" alt="{{ $currentLanguage->name }}"
                        class="language-flag me-1" style="height: 25px; width: auto; vertical-align: middle;">
                @endif
                {{ __(strtoupper($currentLocale)) }}
            </span>
        </a>
        <ul class="sub-menu">
            @foreach ($languages as $language)
                @if ($language->code !== $currentLocale)
                    <li aria-haspopup="true">
                        <a href="{{ route('language.switch', $language->code) }}">
                            @if ($language->image)
                                <img src="{{ Storage::url($language->image) }}" alt="{{ $language->name }}"
                                    class="language-flag me-1"
                                    style="height: 25px; width: auto; vertical-align: middle;">
                            @endif
                            {{ strtoupper($language->native_name) }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
@endif
