@props(['serviceId' => null, 'quantity' => 1, 'serviceType' => 'salon'])

<div id="cart-manager" class="d-none">
    <!-- This is a hidden component that provides cart management functionality -->
</div>

@push('scripts')
<script>
    // Cart Manager Component
    const CartManager = {
        /**
         * Add a service to the cart
         * @param {number} serviceId - The ID of the service to add
         * @param {number} quantity - The quantity to add (default: 1)
         * @param {string} serviceType - The service type ('home' or 'salon')
         * @returns {Promise} - A promise that resolves when the service is added to cart
         */
        addToCart: function(serviceId, quantity = 1, serviceType = 'salon') {
            return new Promise((resolve, reject) => {
                // Validate parameters
                if (!serviceId) {
                    reject(new Error('{{ __("Service ID is required") }}'));
                    return;
                }

                // Ensure quantity is valid
                quantity = Math.max(1, Math.min(10, parseInt(quantity) || 1));

                // Ensure service type is valid
                serviceType = ['home', 'salon'].includes(serviceType) ? serviceType : 'salon';

                // Create unique identifier
                const uniqueId = `${serviceId}-${serviceType}`;

                // Call API to get service data
                $.ajax({
                    url: '/api/services/' + serviceId,
                    method: 'GET',
                    success: (response) => {
                        // Check if response is valid
                        if (response && response.id) {
                            const service = response;

                            // Initialize cart array if it doesn't exist
                            let cart = [];
                            const savedCart = localStorage.getItem('cart');
                            if (savedCart) {
                                cart = JSON.parse(savedCart);
                            }

                            // Check if the service with the same ID and type is already in the cart
                            const existingIndex = cart.findIndex(item =>
                                item.unique_id === uniqueId
                            );

                            if (existingIndex >= 0) {
                                // Update quantity if already in cart
                                cart[existingIndex].quantity += quantity;
                            } else {
                                // Add new service to cart
                                cart.push({
                                    id: serviceId,
                                    unique_id: uniqueId,
                                    name: service.name,
                                    price: parseFloat(service.price),
                                    price_home: parseFloat(service.price_home),
                                    image: service.image || '',
                                    can_be_done_at_home: service.can_be_done_at_home,
                                    category_name: service.category ? service.category.name : '',
                                    category_id: service.category_id,
                                    quantity: quantity,
                                    service_type: serviceType,
                                    duration_minutes: service.duration_minutes
                                });
                            }

                            // Save to localStorage
                            localStorage.setItem('cart', JSON.stringify(cart));

                            // Update the cart count if the function exists
                            if (typeof updateCartCount === 'function') {
                                updateCartCount();
                            }

                            // Animate the cart button if the function exists
                            if (typeof animateCartButton === 'function') {
                                animateCartButton();
                            }

                            // Show notification
                            displayNotification('success', '{{ __("Service added to cart!") }}', 'topRight', 3);

                            resolve(cart);
                        } else {
                            reject(new Error('{{ __("Invalid service data received") }}'));
                        }
                    },
                    error: (xhr, status, error) => {
                        reject(new Error('{{ __("Error fetching service data:") }} ' + error));
                    }
                });
            });
        },

        /**
         * Remove a service from the cart
         * @param {string} uniqueId - The unique identifier of the service to remove
         * @returns {Array} - The updated cart
         */
        removeFromCart: function(uniqueId) {
            let cart = [];
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
            }

            // Find and remove the service
            const index = cart.findIndex(item => item.unique_id === uniqueId);
            if (index >= 0) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));

                // Update the cart count if the function exists
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            }

            return cart;
        },

        /**
         * Update the quantity of a service in the cart
         * @param {string} uniqueId - The unique identifier of the service
         * @param {number} quantity - The new quantity
         * @returns {Array} - The updated cart
         */
        updateQuantity: function(uniqueId, quantity) {
            let cart = [];
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
            }

            // Ensure quantity is valid
            quantity = Math.max(1, Math.min(10, parseInt(quantity) || 1));

            // Find and update the service
            const index = cart.findIndex(item => item.unique_id === uniqueId);
            if (index >= 0) {
                cart[index].quantity = quantity;
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            return cart;
        },

        /**
         * Clear the entire cart
         * @returns {Array} - An empty cart array
         */
        clearCart: function() {
            localStorage.setItem('cart', JSON.stringify([]));

            // Update the cart count if the function exists
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }

            return [];
        },

        /**
         * Get the current cart
         * @returns {Array} - The current cart
         */
        getCart: function() {
            let cart = [];
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
            }
            return cart;
        }
    };

    // Make CartManager available globally
    window.CartManager = CartManager;


</script>
@endpush
