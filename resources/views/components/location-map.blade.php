{{-- Location Map Component --}}
{{-- Props:
    - id (optional): ID prefix for the map elements (default: 'location')
    - showCoordinateFields (optional): Whether to show latitude/longitude fields (default: true)
    - initialLat (optional): Initial latitude (default: 24.7136 - Riyadh)
    - initialLng (optional): Initial longitude (default: 46.6753 - Riyadh)
    - default_lat (optional): Default latitude to use (overrides initialLat if provided)
    - default_lng (optional): Default longitude to use (overrides initialLng if provided)
    - addressFieldId (optional): ID of the address field to update (default: 'address')
    - buttonClass (optional): CSS class for buttons (default: 'btn--black hover--black')
--}}

@props([
    'id' => 'location',
    'showCoordinateFields' => false,
    'initialLat' => 24.7136,
    'initialLng' => 46.6753,
    'default_lat' => null,
    'default_lng' => null,
    'addressFieldId' => 'address',
    'buttonClass' => 'btn--black hover--black'
])

{{-- Map Container --}}
<div class="mb-3">
    <label class="form-label">{{ __('Select Location on Map') }}</label>
    <div id="{{ $id }}-map" style="height: 300px; border-radius: 8px; position: relative;"></div>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <p class="small text-muted mb-0">
            <i class="fas fa-info-circle"></i>
            {{ __('Drag the marker to adjust your exact location') }}
        </p>
        <button type="button" class="btn btn-sm {{ $buttonClass }}" id="{{ $id }}-get-my-location-btn">
            <i class="fas fa-crosshairs"></i> {{ __('Get My Location') }}
        </button>
    </div>
</div>

{{-- Hidden original search field (will be moved to map) --}}
<div style="display: none">
    <div class="input-group mb-2">
        <input type="text" class="form-control" id="{{ $id }}-search"
            placeholder="{{ __('Search for your location') }}">
        <button class="btn {{ $buttonClass }}" type="button" id="{{ $id }}-search-btn">
            <i class="fas fa-search"></i> {{ __('Search') }}
        </button>
    </div>
</div>

{{-- Coordinate Fields (can be hidden with showCoordinateFields prop) --}}
@if($showCoordinateFields)
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="{{ $id }}-latitude" class="form-label">{{ __('Latitude') }}</label>
        <input type="text" class="form-control" id="{{ $id }}-latitude" name="latitude">
    </div>

    <div class="col-md-6 mb-3">
        <label for="{{ $id }}-longitude" class="form-label">{{ __('Longitude') }}</label>
        <input type="text" class="form-control" id="{{ $id }}-longitude" name="longitude">
    </div>
</div>
@else
<input type="hidden" id="{{ $id }}-latitude" name="latitude">
<input type="hidden" id="{{ $id }}-longitude" name="longitude">
@endif

{{-- Map CSS --}}
<style>
    .map-search-control {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 1000;
        background: white;
        padding: 3px;
        border-radius: 4px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        width: 235px;
    }
    .map-search-control .input-group {
        margin-bottom: 0 !important;
    }
    .map-search-control .form-control {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        height: calc(1.5em + 0.5rem + 2px);
    }
    .map-search-control .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .map-search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1001;
        background: white;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        max-height: 200px;
        overflow-y: auto;
        display: none;
        margin-top: 3px;
    }
    .map-search-suggestions.active {
        display: block;
    }
    .map-search-suggestion {
        padding: 6px 10px;
        cursor: pointer;
        font-size: 0.875rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .map-search-suggestion:hover {
        background-color: #f8f9fa;
    }
    .map-search-suggestion:last-child {
        border-bottom: none;
    }
    .map-search-no-results {
        padding: 8px 10px;
        font-size: 0.875rem;
        color: #6c757d;
        text-align: center;
        font-style: italic;
    }
</style>

{{-- Map Initialization Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if this is the first time loading the component
    if (!window.{{ $id }}MapInitialized) {
        // Wait a bit to ensure container is rendered
        setTimeout(function() {
            initializeMap{{ ucfirst($id) }}();
        }, 500);

        window.{{ $id }}MapInitialized = true;
    }
});

function initializeMap{{ ucfirst($id) }}() {
    // Map initialization
    let map, marker;
    let defaultLat = {{ $initialLat }};
    let defaultLng = {{ $initialLng }};

    // Check if default coordinates are provided
    const hasDefaultCoordinates = {{ $default_lat !== null && $default_lng !== null ? 'true' : 'false' }};

    // Use default coordinates if provided
    if (hasDefaultCoordinates) {
        defaultLat = {{ $default_lat ?? $initialLat }};
        defaultLng = {{ $default_lng ?? $initialLng }};
    }

    // Initialize map
    map = L.map('{{ $id }}-map').setView([defaultLat, defaultLng], 13);

    // Add tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Create custom search control and add to map
    const searchControlDiv = document.createElement('div');
    searchControlDiv.className = 'map-search-control';
    searchControlDiv.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" id="{{ $id }}-map-search"
                placeholder="{{ __('Search for your location') }}">
            <button class="btn {{ $buttonClass }}" type="button" id="{{ $id }}-map-search-btn">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="map-search-suggestions" id="{{ $id }}-map-search-suggestions"></div>
    `;
    document.getElementById('{{ $id }}-map').appendChild(searchControlDiv);

    // Add event listeners to the new search field
    document.getElementById('{{ $id }}-map-search-btn').addEventListener('click', function() {
        const searchText = document.getElementById('{{ $id }}-map-search').value;
        document.getElementById('{{ $id }}-search').value = searchText;
        searchLocation{{ ucfirst($id) }}(map, marker);
    });

    document.getElementById('{{ $id }}-map-search').addEventListener('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const searchText = document.getElementById('{{ $id }}-map-search').value;
            document.getElementById('{{ $id }}-search').value = searchText;
            searchLocation{{ ucfirst($id) }}(map, marker);
        }
    });

    // Add suggestions for search input
    const suggestionsContainer = document.getElementById('{{ $id }}-map-search-suggestions');
    const searchInput = document.getElementById('{{ $id }}-map-search');

    // Prevent map from moving when scrolling through suggestions
    suggestionsContainer.addEventListener('wheel', function(e) {
        e.stopPropagation();
    });

    suggestionsContainer.addEventListener('mouseenter', function() {
        // Disable map scroll zoom when hovering over suggestions
        if (map) {
            map.scrollWheelZoom.disable();
        }
    });

    suggestionsContainer.addEventListener('mouseleave', function() {
        // Re-enable map scroll zoom when leaving suggestions
        if (map) {
            map.scrollWheelZoom.enable();
        }
    });

    // Debounce function for input
    let searchTimeout = null;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Clear any existing timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // If search is empty, hide suggestions
        if (!query) {
            suggestionsContainer.classList.remove('active');
            suggestionsContainer.innerHTML = '';
            return;
        }

        // Set new timeout (300ms debounce)
        searchTimeout = setTimeout(function() {
            // Fetch suggestions from Nominatim
            getSuggestions{{ ucfirst($id) }}(query, function(suggestions) {
                // Clear suggestions container
                suggestionsContainer.innerHTML = '';

                if (suggestions.length > 0) {
                    // Add each suggestion
                    suggestions.forEach(function(suggestion) {
                        const div = document.createElement('div');
                        div.className = 'map-search-suggestion';
                        div.textContent = suggestion.display_name;
                        div.addEventListener('click', function() {
                            // Set input value
                            searchInput.value = suggestion.display_name;
                            document.getElementById('{{ $id }}-search').value = suggestion.display_name;

                            // Update map
                            const lat = parseFloat(suggestion.lat);
                            const lng = parseFloat(suggestion.lon);

                            map.setView([lat, lng], 15);

                            if (marker) {
                                marker.setLatLng([lat, lng]);
                            } else {
                                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                                marker.on('dragend', function(e) {
                                    const pos = e.target.getLatLng();
                                    $('#{{ $id }}-latitude').val(pos.lat);
                                    $('#{{ $id }}-longitude').val(pos.lng);
                                    reverseGeocode{{ ucfirst($id) }}(pos.lat, pos.lng);
                                });
                            }

                            // Update form fields
                            $('#{{ $id }}-latitude').val(lat);
                            $('#{{ $id }}-longitude').val(lng);
                            $('#{{ $addressFieldId }}').val(suggestion.display_name);

                            // Hide suggestions
                            suggestionsContainer.classList.remove('active');
                        });
                        suggestionsContainer.appendChild(div);
                    });

                    // Show suggestions
                    suggestionsContainer.classList.add('active');
                } else {
                    // No suggestions found - show message
                    const noResults = document.createElement('div');
                    noResults.className = 'map-search-no-results';
                    noResults.textContent = '{{ __("No results found") }}';
                    suggestionsContainer.appendChild(noResults);
                    suggestionsContainer.classList.add('active');
                }
            });
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.classList.remove('active');
        }
    });

    // Add marker at default position
    marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    // Get the existing lat/long if available
    const savedLat = $('#{{ $id }}-latitude').val();
    const savedLng = $('#{{ $id }}-longitude').val();

    if (savedLat && savedLng) {
        map.setView([savedLat, savedLng], 15);
        marker.setLatLng([savedLat, savedLng]);
    } else if (hasDefaultCoordinates) {
        // Use default coordinates and update form fields but not address
        map.setView([defaultLat, defaultLng], 15);
        marker.setLatLng([defaultLat, defaultLng]);
        $('#{{ $id }}-latitude').val(defaultLat);
        $('#{{ $id }}-longitude').val(defaultLng);
        // Note: Not updating the address field for default coordinates
    } else {
        // Try to get user's current location if no saved coordinates and no default coordinates
        getUserLocation{{ ucfirst($id) }}(map, marker);
    }

    // Update fields when marker is moved
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        $('#{{ $id }}-latitude').val(position.lat);
        $('#{{ $id }}-longitude').val(position.lng);

        // Reverse geocode to get address
        reverseGeocode{{ ucfirst($id) }}(position.lat, position.lng);
    });

    // Search button click handler (keep the original for compatibility)
    $('#{{ $id }}-search-btn').on('click', function() {
        searchLocation{{ ucfirst($id) }}(map, marker);
    });

    // Search on Enter key (keep the original for compatibility)
    $('#{{ $id }}-search').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            searchLocation{{ ucfirst($id) }}(map, marker);
        }
    });

    // Get user location button handler
    $('#{{ $id }}-get-my-location-btn').on('click', function() {
        // Show loading state
        const $btn = $(this);
        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Locating...') }}');

        // Force fresh location request (maximumAge: 0)
        getUserLocation{{ ucfirst($id) }}(map, marker, function() {
            // Reset button
            $btn.prop('disabled', false)
                .html('<i class="fas fa-crosshairs"></i> {{ __('Get My Location') }}');
        }, false); // false = not initial load, so we force fresh location
    });

    // Re-initialize map when window is resized
    $(window).on('resize', function() {
        if (map) {
            map.invalidateSize();
        }
    });
}

// Get user's current location
function getUserLocation{{ ucfirst($id) }}(map, marker, callback, isInitialLoad = false) {
    if (!navigator.geolocation) {
        if (typeof displayNotification === 'function') {
            displayNotification('error', '{{ __('Geolocation is not supported by your browser') }}');
        } else {
            console.error('Geolocation is not supported by your browser');
        }
        if (callback) callback();
        return;
    }

    // Different options based on whether this is an initial load or explicit user request
    const geoOptions = {
        enableHighAccuracy: true,
        timeout: 5000,
        // For initial load, allow using cached position from the last 10 seconds
        // For explicit location request via button, use fresh data
        maximumAge: isInitialLoad ? 10000 : 0
    };

    navigator.geolocation.getCurrentPosition(
        function(position) {
            // Get exact coordinates with full precision
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Log precision information
            const accuracy = position.coords.accuracy;
            console.log(`Location precision: ${accuracy} meters`);
            console.log(userLat, userLng);
            // Update map and marker with full precision coordinates
            map.setView([userLat, userLng], 15);
            marker.setLatLng([userLat, userLng]);

            // Update form fields with the EXACT values, not rounded
            $('#{{ $id }}-latitude').val(userLat);
            $('#{{ $id }}-longitude').val(userLng);

            // Reverse geocode to get address
            reverseGeocode{{ ucfirst($id) }}(userLat, userLng);

            if (callback) callback();
        },
        function(error) {
            console.log("Error getting location:", error.message);

            // Don't show error message on initial load - just use default coordinates
            if (!isInitialLoad) {
                // Show error message
                let errorMsg = '{{ __('Unable to retrieve your location') }}';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg = '{{ __('Location access was denied. Please enable location services.') }}';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg = '{{ __('Location information is unavailable.') }}';
                        break;
                    case error.TIMEOUT:
                        errorMsg = '{{ __('The request to get user location timed out.') }}';
                        break;
                }

                if (typeof displayNotification === 'function') {
                    displayNotification('error', errorMsg);
                } else {
                    console.error(errorMsg);
                }
            }

            if (callback) callback();

            // For initial load, when user denies location access, use default coordinates
            // No need to show error notification in this case
        },
        geoOptions
    );
}

// Simple reverse geocoding using Nominatim
function reverseGeocode{{ ucfirst($id) }}(lat, lng) {
    $.ajax({
        url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data && data.display_name) {
                $('#{{ $addressFieldId }}').val(data.display_name);
            }
        },
        error: function(error) {
            console.log("Error with geocoding:", error);
        }
    });
}

// Search for location by address
function searchLocation{{ ucfirst($id) }}(map, marker) {
    const query = $('#{{ $id }}-search').val().trim();
    if (!query) return;

    // Show loading indicator
    $('#{{ $id }}-search-btn').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Searching...') }}');

    // Also update the map search button if it exists
    $('#{{ $id }}-map-search-btn').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    $.ajax({
        url: `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=sa`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Reset buttons
            $('#{{ $id }}-search-btn').html('<i class="fas fa-search"></i> {{ __('Search') }}');
            $('#{{ $id }}-map-search-btn').html('<i class="fas fa-search"></i>');

            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);

                // Update map view
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);

                // Update form fields
                $('#{{ $id }}-latitude').val(lat);
                $('#{{ $id }}-longitude').val(lng);
                $('#{{ $addressFieldId }}').val(result.display_name);
            } else {
                if (typeof displayNotification === 'function') {
                    displayNotification('error', '{{ __('Location not found. Please try a different search term.') }}');
                } else {
                    console.error('Location not found. Please try a different search term.');
                }
            }
        },
        error: function(error) {
            // Reset buttons
            $('#{{ $id }}-search-btn').html('<i class="fas fa-search"></i> {{ __('Search') }}');
            $('#{{ $id }}-map-search-btn').html('<i class="fas fa-search"></i>');
            console.log("Error searching location:", error);

            if (typeof displayNotification === 'function') {
                displayNotification('error', '{{ __('Error searching for location. Please try again.') }}');
            } else {
                console.error('Error searching for location. Please try again.');
            }
        }
    });
}

// Function to get location suggestions from Nominatim
function getSuggestions{{ ucfirst($id) }}(query, callback) {
    // Don't search for very short queries
    if (query.length < 3) {
        callback([]);
        return;
    }

    $.ajax({
        url: `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=sa`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            callback(data);
        },
        error: function(error) {
            console.error('Error fetching suggestions:', error);
            callback([]);
        }
    });
}
</script>
