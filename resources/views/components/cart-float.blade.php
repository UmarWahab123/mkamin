<!-- Cart Floating Button Container -->
<div class="cart-float-container" style="display: none;">
    <a href="/cart" class="cart-float-button">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count">0</span>
    </a>
</div>

<!-- Cart Float CSS -->
<style>
    .cart-float-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    /* Desktop screen styles */
    @media screen and (min-width: 768px) {
        .cart-float-container {
            bottom: 80px;
            right: 8px;
        }
    }

    .cart-float-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #000;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        position: relative;
        transition: all 0.3s ease;
    }

    .cart-float-button:hover {
        background-color: #333;
        transform: scale(1.05);
        color: #fff;
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
    }

    /* Animation for cart button */
    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    .cart-float-container.animate .cart-float-button {
        animation: bounce 0.5s;
    }
</style>

@push('scripts')
    <!-- Cart Float JavaScript -->
    <script>
        // Make sure jQuery is available
        (function(callback) {
            if (window.jQuery) {
                // jQuery is already loaded, initialize immediately
                callback(window.jQuery);
            } else {
                // Check if jQuery is being loaded elsewhere
                var checkJQuery = setInterval(function() {
                    if (window.jQuery) {
                        clearInterval(checkJQuery);
                        callback(window.jQuery);
                    }
                }, 100);
            }
        })(function($) {
            // This is the callback that runs once jQuery is available
            $(document).ready(function() {
                // Initialize the cart float
                initCartFloat();

                // Function to initialize the cart float
                function initCartFloat() {
                    // Load cart from localStorage
                    const savedCart = localStorage.getItem('cart');
                    if (savedCart) {
                        const cart = JSON.parse(savedCart);
                        updateCartFloat(cart);
                    }

                    // Listen for storage events (cart updates from other pages)
                    window.addEventListener('storage', function(e) {
                        if (e.key === 'cart') {
                            const cart = e.newValue ? JSON.parse(e.newValue) : [];
                            updateCartFloat(cart);
                        }
                    });

                    // Custom event for cart updates on same page
                    $(document).on('cartUpdated', function(e, cart) {
                        updateCartFloat(cart);
                    });
                }

                // Function to update the cart float
                function updateCartFloat(cart) {
                    const count = cart.reduce((total, item) => total + item.quantity, 0);

                    // Update count display
                    $('.cart-count').text(count);

                    // Show/hide cart float based on count
                    if (count > 0) {
                        $('.cart-float-container').fadeIn(300);
                    } else {
                        $('.cart-float-container').fadeOut(300);
                    }
                }

                // Function to animate the cart button (to be called when adding to cart)
                window.animateCartButton = function() {
                    $('.cart-float-container').addClass('animate');

                    // Remove animation class after animation completes
                    setTimeout(function() {
                        $('.cart-float-container').removeClass('animate');
                    }, 500);
                };

                // Define global updateCartCount function that others can call
                window.updateCartCount = function() {
                    const savedCart = localStorage.getItem('cart');
                    if (savedCart) {
                        const cart = JSON.parse(savedCart);
                        updateCartFloat(cart);
                        // Trigger cart updated event
                        $(document).trigger('cartUpdated', [cart]);
                        return cart;
                    }
                    return [];
                };
            });
        });
    </script>
@endpush
