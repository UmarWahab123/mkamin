@php
    $fieldType = $getRecord()->field_type;
    $value = $getRecord()->value;
@endphp

@if ($fieldType === 'image')
    <div class="w-10 h-10 overflow-hidden rounded-full">
        <img src="{{ asset('storage/' . $value) }}" alt="{{ $getRecord()->key }}" class="w-full h-full object-cover">
    </div>
@elseif ($fieldType === 'color_picker')
    <div class="flex items-center">
        <div class="w-6 h-6 rounded-full" style="background-color: {{ $value }}"></div>
        <span style="color: {{ $value }}; margin: 0px 10px;">{{ $value }}</span>
    </div>
@elseif ($fieldType === 'rich_text_editor')
    <div>
        {{ Str::limit(strip_tags($value), 50) }}
    </div>
@elseif ($fieldType === 'date')
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
        </svg>
        <span>{{ $value }}</span>
    </div>
@elseif ($fieldType === 'time')
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ $value }}</span>
    </div>
@elseif ($fieldType === 'day')
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-18 0h18" />
        </svg>
        @php
            $days = [
                '0' => __('Sunday'),
                '1' => __('Monday'),
                '2' => __('Tuesday'),
                '3' => __('Wednesday'),
                '4' => __('Thursday'),
                '5' => __('Friday'),
                '6' => __('Saturday'),
            ];
        @endphp
        <span>{{ $days[$value] ?? $value }}</span>
    </div>
@elseif ($fieldType === 'text' && Str::length($value) > 50)
    <div>
        {{ Str::limit($value, 50) }}
    </div>
@else
    <div>
        {{ $value }}
    </div>
@endif
