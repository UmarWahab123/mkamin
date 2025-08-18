<div>
    <div class="form-group">
        <label for="appointment_date" class="form-label text-gray-700 dark:text-gray-300">
            {{ __('Appointment Date') }}
        </label>

        @if(!$pointOfSaleId)
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                {{ __('Please select a point of sale first to see available appointment dates.') }}
            </div>
            <input type="text" disabled
                class="form-input bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600"
                placeholder="{{ __('Select a Point of Sale first') }}">
        @else
            <input type="text" id="appointment_date" wire:model.live="selectedDate"
                class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 cursor-pointer"
                placeholder="{{ __('Select date') }}" readonly>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ __('Today:') }} {{ \Carbon\Carbon::today()->format('Y-m-d') }}
            </div>
        @endif
    </div>

    <!-- Flatpickr Script -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script>
        document.addEventListener('livewire:initialized', () => {
            initDatePicker();

            // Listen for custom refresh event
            Livewire.on('refresh-date-selector', (disabledDates) => {
                // Add a small delay to ensure the component has updated with new disabled dates
                setTimeout(() => initDatePicker(disabledDates), 100);
            });
        });

        // Listen for pointOfSaleChanged event to reinitialize date picker
        document.addEventListener('livewire:navigating', () => {
            setTimeout(initDatePicker, 100); // Small delay to ensure DOM is updated
        });

        // Reinitialize when the component is updated
        document.addEventListener('livewire:navigated', () => {
            setTimeout(initDatePicker, 100); // Small delay to ensure DOM is updated
        });

        // Also reinitialize on regular component updates
        document.addEventListener('livewire:update', () => {
            initDatePicker();
        });

        function initDatePicker(externalDisabledDates = null) {
            const dateInput = document.getElementById('appointment_date');

            // Only initialize if the date input exists (point of sale is selected)
            if (!dateInput) return;

            // Add a visual cue that this is clickable
            dateInput.style.cursor = 'pointer';

            // Use the external disabled dates if provided, otherwise use the component's disabled dates
            let disabledDates = externalDisabledDates || @json($disabledDates);

            // Ensure disabledDates is properly formatted for Flatpickr
            // If it's an array of objects with 'from' and 'to' properties, it's already in the right format
            // If it's a nested array, we need to flatten it
            if (Array.isArray(disabledDates) && disabledDates.length > 0 && Array.isArray(disabledDates[0])) {
                // Flatten the nested array
                disabledDates = disabledDates.flat();
            }

            const today = new Date();

            // Destroy existing flatpickr instance if it exists
            if (dateInput._flatpickr) {
                dateInput._flatpickr.destroy();
            }

            flatpickr(dateInput, {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: disabledDates,
                defaultDate: today,
                locale: {
                    firstDayOfWeek: 1
                },
                onChange: function(selectedDates, dateStr) {
                    @this.set('selectedDate', dateStr);
                }
            });
        }
    </script>
</div>
