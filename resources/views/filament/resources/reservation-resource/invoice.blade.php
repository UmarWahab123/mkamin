<div class="filament-resource-invoice">
    @if(is_array($getState()) && count($getState()) > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg mb-4">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Service</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Duration</th>
                        <th scope="col" class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Price</th>
                        <th scope="col" class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @foreach($getState() as $index => $service)
                        <tr>
                            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-900 dark:text-gray-200">
                                {{ $service['name'] }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($service['location_type']) }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-center text-gray-900 dark:text-gray-200">
                                {{ $service['duration'] }} min
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-right text-gray-900 dark:text-gray-200">
                                {{ number_format($service['price'], 2) }} SAR
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-center">
                                <button
                                    type="button"
                                    class="text-danger-600 hover:underline"
                                    data-index="{{ $index }}"
                                    onclick="window.handleRemoveService(this)"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 text-center text-gray-500 dark:text-gray-400">
            No services added yet. Select services from the left panel.
        </div>
    @endif

    <script>
        // Define a global function to handle removing services
        window.handleRemoveService = function(buttonElement) {
            const index = buttonElement.getAttribute('data-index');

            // Create a custom event to be handled by the main form
            const event = new CustomEvent('remove-service', {
                detail: {
                    index: parseInt(index)
                }
            });

            // Dispatch the event on the document so it can be caught by the main form
            document.dispatchEvent(event);
        };

        // Log a message to help debug the issue
        console.log('Loading reservation invoice handler');

        // Initialize the main event listeners for the form
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - setting up service handlers');

            // Function to find the services input element
            function findServicesInput() {
                // Debug all hidden inputs in the form
                const allHiddenInputs = document.querySelectorAll('input[type="hidden"]');
                console.log('Found', allHiddenInputs.length, 'hidden inputs');

                allHiddenInputs.forEach((input, i) => {
                    console.log(`Input #${i}:`, input.name, input.value ? input.value.substring(0, 30) + '...' : '(empty)');
                });

                // First try with the exact name
                let input = document.querySelector('input[name="services_data"]');
                if (input) {
                    console.log('Found services_data input directly');
                    return input;
                }

                // Then try with data attribute
                input = document.querySelector('input[data-field-name="services_data"]');
                if (input) {
                    console.log('Found services_data input via data attribute');
                    return input;
                }

                // Try to find by partial name
                const inputs = Array.from(document.querySelectorAll('input[type="hidden"]'));

                // Look for any input with "services" or "data" in its name
                const dataInput = inputs.find(input =>
                    input.name && (
                        input.name.includes('services') ||
                        input.name.includes('data')
                    )
                );

                if (dataInput) {
                    console.log('Found potential services input by name pattern:', dataInput.name);
                    return dataInput;
                }

                // If still not found, look for any input with JSON array value
                for (const input of inputs) {
                    try {
                        const value = input.value ? JSON.parse(input.value) : null;
                        if (Array.isArray(value)) {
                            console.log('Found input with array value:', input.name);
                            return input;
                        }
                    } catch (e) {
                        // Not JSON, continue
                    }
                }

                console.error('Could not find services_data input or any suitable alternative');
                return null;
            }

            // Listen for the custom add-service event
            document.addEventListener('add-service', function(e) {
                console.log('Add service event received:', e.detail);
                const serviceData = e.detail;

                // Find the services input
                const servicesInput = findServicesInput();

                if (!servicesInput) {
                    console.error('Could not find services_data input');
                    return;
                }

                // Get the current services from the input
                let servicesData = [];
                try {
                    servicesData = JSON.parse(servicesInput.value || '[]');
                    console.log('Current services:', servicesData);
                } catch (e) {
                    console.error('Error parsing services_data:', e);
                }

                // Check if service already exists
                const exists = servicesData.some(item =>
                    item.id == serviceData.id &&
                    item.location_type == serviceData.location_type
                );

                if (exists) {
                    console.log('Service already exists, not adding duplicate');
                    return; // Don't add duplicates
                }

                // Add the new service
                servicesData.push(serviceData);
                console.log('Updated services data:', servicesData);

                // Update the input value
                servicesInput.value = JSON.stringify(servicesData);

                // Trigger a change event to notify the form
                const event = new Event('input', { bubbles: true });
                servicesInput.dispatchEvent(event);

                // Also try with change event
                const changeEvent = new Event('change', { bubbles: true });
                servicesInput.dispatchEvent(changeEvent);

                // Try to find the closest form
                const form = servicesInput.closest('form');
                if (form) {
                    try {
                        console.log('Attempting to submit form');
                        form.requestSubmit();
                    } catch (e) {
                        console.error('Error submitting form:', e);
                    }
                } else {
                    console.warn('Could not find parent form');
                }

                // Attempt to find the Livewire component and update it
                try {
                    const livewireEl = servicesInput.closest('[wire\\:id]');
                    if (livewireEl) {
                        const componentId = livewireEl.getAttribute('wire:id');
                        if (window.Livewire && componentId) {
                            console.log('Found Livewire component, attempting direct update');
                            window.Livewire.find(componentId).set('services_data', servicesData);
                        }
                    }
                } catch (e) {
                    console.error('Error updating Livewire component:', e);
                }
            });

            // Listen for the custom remove-service event
            document.addEventListener('remove-service', function(e) {
                console.log('Remove service event received:', e.detail);
                const index = e.detail.index;

                // Find the services input
                const servicesInput = findServicesInput();

                if (!servicesInput) {
                    console.error('Could not find services_data input');
                    return;
                }

                // Get the current services from the input
                let servicesData = [];
                try {
                    servicesData = JSON.parse(servicesInput.value || '[]');
                    console.log('Current services:', servicesData);
                } catch (e) {
                    console.error('Error parsing services_data:', e);
                }

                // Remove the service at the specified index
                if (index >= 0 && index < servicesData.length) {
                    servicesData.splice(index, 1);
                    console.log('Service removed, updated data:', servicesData);
                }

                // Update the input value
                servicesInput.value = JSON.stringify(servicesData);

                // Trigger a change event to notify the form
                const event = new Event('input', { bubbles: true });
                servicesInput.dispatchEvent(event);

                // Also try with change event
                const changeEvent = new Event('change', { bubbles: true });
                servicesInput.dispatchEvent(changeEvent);

                // Try to find the closest form
                const form = servicesInput.closest('form');
                if (form) {
                    try {
                        console.log('Attempting to submit form');
                        form.requestSubmit();
                    } catch (e) {
                        console.error('Error submitting form:', e);
                    }
                } else {
                    console.warn('Could not find parent form');
                }

                // Attempt to find the Livewire component and update it
                try {
                    const livewireEl = servicesInput.closest('[wire\\:id]');
                    if (livewireEl) {
                        const componentId = livewireEl.getAttribute('wire:id');
                        if (window.Livewire && componentId) {
                            console.log('Found Livewire component, attempting direct update');
                            window.Livewire.find(componentId).set('services_data', servicesData);
                        }
                    }
                } catch (e) {
                    console.error('Error updating Livewire component:', e);
                }
            });
        });
    </script>
</div>
