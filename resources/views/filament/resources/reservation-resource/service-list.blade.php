<div class="filament-resource-service-list">
    <div class="space-y-2">
        @foreach($services as $service)
            <div class="flex items-center p-2 bg-white rounded-md shadow hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                <div class="flex-1">
                    <div class="font-medium">{{ $service['name'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ number_format($service['price'], 2) }} SAR</span>
                        @if($service['duration'] > 0)
                            <span class="mx-1">•</span>
                            <span>{{ $service['duration'] }} min</span>
                        @endif
                    </div>
                </div>
                <button
                    type="button"
                    class="filament-button filament-button-size-sm relative inline-flex items-center justify-center rounded-md font-medium hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset h-9 px-4 text-sm text-white shadow focus:ring-white bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700"
                    onclick="logServiceData(
                        {{ $service['id'] }},
                        '{{ addslashes($service['name']) }}',
                        {{ $service['price'] }},
                        {{ $service['duration'] }},
                        '{{ $locationType }}'
                    )"
                >
                    <span class="">Add</span>
                </button>
            </div>
        @endforeach
    </div>

    <script>
        // Simple function to just log service data when button is clicked
        function logServiceData(id, name, price, duration, locationType) {
            const serviceData = {
                id: id,
                name: name,
                price: parseFloat(price),
                duration: parseInt(duration),
                location_type: locationType
            };

            console.log('Service data:', serviceData);
        }
    </script>
</div>
