@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.css" />
    <style>
        /* Ensure Leaflet map stays below UI elements like notifications and select dropdowns */
        .leaflet-map-container {
            z-index: 1 !important;
        }
        .leaflet-pane,
        .leaflet-control,
        .leaflet-top,
        .leaflet-bottom {
            z-index: 10 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>
@endpush

<x-dynamic-component :component="$getFieldWrapperView()" :id="$getId()" :label="$getLabel()" :helper-text="$getHelperText()" :hint="$getHint()"
    :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div wire:ignore x-data="{
        state: $wire.entangle('{{ $getStatePath() }}'),
        mapInitialized: false,
        marker: null,
        map: null,
        searchLoading: false,
        init() {
            // Wait a bit to ensure the DOM is ready
            setTimeout(() => {
                this.initMap();
            }, 200);

            // Watch for external changes to state (from fields)
            this.$watch('state', (newVal) => {
                if (this.map && newVal?.lat && newVal?.lng) {
                    const newLatLng = L.latLng(newVal.lat, newVal.lng);
                    if (this.marker) {
                        this.marker.setLatLng(newLatLng);
                    } else {
                        this.marker = L.marker(newLatLng, { draggable: true }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.updateState(pos.lat, pos.lng);
                            this.reverseGeocode(pos.lat, pos.lng);
                        });
                    }
                    this.map.setView(newLatLng, this.map.getZoom());
                }
            });

            // Handle window resize to fix map display issues
            window.addEventListener('resize', () => {
                if (this.map) {
                    this.map.invalidateSize();
                }
            });

            // Listen for filament panel/tab changes that might affect map display
            window.addEventListener('filament-tabs::changed', () => {
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 200);
            });
        },
        initMap() {
            if (this.mapInitialized || typeof L === 'undefined' || typeof L.Control.Geocoder === 'undefined') return;
            this.mapInitialized = true;

            // Determine the initial location based on state or default
            let initialLocation;
            let initialZoom = 13; // Always use zoom level 13

            if (this.state?.lat && this.state?.lng) {
                initialLocation = [this.state.lat, this.state.lng];
            } else {
                initialLocation = {!! $getExtraAttributeBag()->get('data-default-location') ?: json_encode([24.7136, 46.6753]) !!};
            }

            const mapElement = this.$refs.map;
            this.map = L.map(mapElement).setView(initialLocation, initialZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            // Place existing marker
            if (this.state?.lat && this.state?.lng) {
                this.marker = L.marker([this.state.lat, this.state.lng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.updateState(pos.lat, pos.lng);
                    this.reverseGeocode(pos.lat, pos.lng);
                });
            } else {
                // Try to get user location if no coordinates set
                this.getUserLocation();
            }

            if (L.Control.Geocoder) {
                const geocoder = L.Control.Geocoder.nominatim();
                L.Control.geocoder({
                    geocoder: geocoder,
                    defaultMarkGeocode: false,
                    placeholder: 'Search address...',
                    collapsed: false
                }).on('markgeocode', (e) => {
                    const latlng = e.geocode.center;
                    if (this.marker) {
                        this.marker.setLatLng(latlng);
                    } else {
                        this.marker = L.marker(latlng, { draggable: true }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.updateState(pos.lat, pos.lng);
                            this.reverseGeocode(pos.lat, pos.lng);
                        });
                    }
                    // Update state with the selected location's coordinates and address
                    this.updateState(latlng.lat, latlng.lng, e.geocode.name);
                    // Force update the address field
                    $wire.set('{{ $getStatePath() }}.address', e.geocode.name);
                    this.map.setView(latlng, 13); // Always use zoom level 13
                }).addTo(this.map);
            }

            // Click on map sets marker
            this.map.on('click', (e) => {
                const latlng = e.latlng;
                if (this.marker) {
                    this.marker.setLatLng(latlng);
                } else {
                    this.marker = L.marker(latlng, { draggable: true }).addTo(this.map);
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateState(pos.lat, pos.lng);
                        this.reverseGeocode(pos.lat, pos.lng);
                    });
                }
                this.updateState(latlng.lat, latlng.lng);
                this.reverseGeocode(latlng.lat, latlng.lng);
                this.map.setView(latlng, 13); // Always use zoom level 13
            });

            // Final resize to ensure map renders correctly
            setTimeout(() => {
                this.map.invalidateSize();
            }, 200);
        },
        updateState(lat, lng, address = '') {
            if (!this.state) {
                this.state = {};
            }

            this.state = {
                lat: lat,
                lng: lng,
                address: address || this.state?.address || ''
            };

            try {
                // Check if the state path exists and initialize it if needed
                const statePath = '{{ $getStatePath() }}';
                if (!$wire.get(statePath)) {
                    $wire.set(statePath, {});
                }

                // Now set the properties
                $wire.set(`${statePath}.lat`, lat);
                $wire.set(`${statePath}.lng`, lng);
                if (address) {
                    $wire.set(`${statePath}.address`, address);
                }
            } catch (error) {
                console.error('Error updating location state:', error);
            }
        },
        getUserLocation() {
            if (!navigator.geolocation) {
                console.error('Geolocation is not supported by your browser');
                return;
            }

            const geoOptions = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // Update map and marker
                    this.map.setView([userLat, userLng], 15);

                    if (this.marker) {
                        this.marker.setLatLng([userLat, userLng]);
                    } else {
                        this.marker = L.marker([userLat, userLng], { draggable: true }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.updateState(pos.lat, pos.lng);
                            this.reverseGeocode(pos.lat, pos.lng);
                        });
                    }

                    // Update state with exact coordinates
                    this.updateState(userLat, userLng);

                    // Get address for the location
                    this.reverseGeocode(userLat, userLng);
                },
                (error) => {
                    console.error('Error getting location:', error.message);
                    // Use default location if geolocation fails
                    const defaultLocation = {!! $getExtraAttributeBag()->get('data-default-location') ?: json_encode([24.7136, 46.6753]) !!};
                    this.map.setView(defaultLocation, 13);
                },
                geoOptions
            );
        },
        reverseGeocode(lat, lng) {
            // Create a direct fetch request to Nominatim API with the correct zoom parameter
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&zoom=13&addressdetails=1&format=json`)
                .then(response => response.json())
                .then(data => {
                    // Extract the full address from the geocoding results
                    let fullAddress = '';

                    // Try to get the display name which usually contains the full address
                    if (data.display_name) {
                        fullAddress = data.display_name;
                    } else if (data.name) {
                        fullAddress = data.name;
                    }

                    // If we still don't have a good address, try to build it from components
                    if (!fullAddress && data.address) {
                        const address = data.address;
                        const components = [];

                        // Add address components in order of specificity
                        if (address.road) components.push(address.road);
                        if (address.house_number) components.push(address.house_number);
                        if (address.suburb) components.push(address.suburb);
                        if (address.city) components.push(address.city);
                        if (address.state) components.push(address.state);
                        if (address.postcode) components.push(address.postcode);
                        if (address.country) components.push(address.country);

                        fullAddress = components.join(', ');
                    }

                    // Update the state with the full address
                    this.updateState(lat, lng, fullAddress);
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                });
        }
    }">
        <div class="space-y-2">
            <div x-ref="map" class="leaflet-map-container" style="width: 100%; height: 400px; border-radius: 0.5rem;"></div>

            <div class="flex justify-end">
                <button type="button"
                        x-on:click="getUserLocation()"
                        class="inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button px-3 py-2 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Get My Location
                </button>
            </div>
        </div>
    </div>
</x-dynamic-component>
