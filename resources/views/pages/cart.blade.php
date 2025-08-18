@extends('layouts.app')

@section('title', __('Shopping Cart - mcs.sa Salon'))

@section('content')

    <!-- CART SECTION -->
    <div id="cart" class="pt-8 pb-7 cart-section division">
        <div class="container">
            <div class="row mt-5">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-items-panel mb-5">
                        <h3 class="h3-md mb-4">{{ __('Cart Items') }}</h3>

                        <!-- Empty Cart Message -->
                        <div id="emptyCartMessage" class="text-center py-5 d-none">
                            <div class="empty-cart-icon mb-3">
                                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                            </div>
                            <h4 class="h4-md">{{ __('Your cart is empty') }}</h4>
                            <p class="mb-4">{{ __('Browse our services and add items to your cart') }}</p>
                            <a href="{{ route('salon-services') }}" class="btn btn--black hover--black">{{ __('Browse Services') }}</a>
                        </div>

                        <!-- Cart Items Table -->
                        <div id="cartItemsTable" class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Service') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cartItemsList">
                                    <!-- Cart items will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Continue Shopping Button -->
                        <div class="d-flex justify-content-between align-items-stretch mt-4">
                            <a href="{{ route('salon-services') }}" class="btn btn--tra-black hover--black">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('Continue Shopping') }}
                            </a>

                            <button id="clearCartBtn" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i> {{ __('Clear Cart') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary-panel">
                        <h3 class="h3-md mb-4">{{ __('Cart Summary') }}</h3>

                        <!-- Summary Totals -->
                        <div class="summary-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Subtotal:') }}</span>
                                <span id="subtotal">0.00 <span class="icon-saudi_riyal"></span></span>
                            </div>
                            <div id="vatRow" class="d-flex justify-content-between mb-2">
                                <span id="vatLabel">{{ __('VAT:') }}</span>
                                <span id="vat">0.00 <span class="icon-saudi_riyal"></span></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>{{ __('Total:') }}</span>
                                <span id="total">0.00 <span class="icon-saudi_riyal"></span></span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-4">
                            <button id="proceedToCheckout" class="btn btn--black hover--black w-100" disabled>
                                {{ __('Proceed to Checkout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CART SECTION -->

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">{{ __('Confirm Action') }}</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body p-3" id="confirmationModalBody">
                    {{ __('Are you sure you want to proceed with this action?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--tra-black hover--black" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn--black hover--black" id="confirmActionBtn">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include notification component -->
    @include('components.notification')
@endsection

@section('styles')
<style>
    /* Cart Items Panel */
    .cart-items-panel {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .cart-summary-panel {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 25px;
        position: sticky;
        top: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .summary-totals {
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 0;
        margin-bottom: 20px;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        overflow: hidden;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        display: flex;
        align-items: center;
    }


    .cart-item-name {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .cart-item-category {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
    }

    .quantity-input {
        width: 40px;
        height: 30px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .btn-decrease, .btn-increase {
        width: 30px;
        height: 30px;
        background: #f0f0f0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-decrease:hover, .btn-increase:hover {
        background: #e0e0e0;
    }

    .remove-item {
        color: #cc0000;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 1.2rem;
    }

    .empty-cart-icon {
        opacity: 0.5;
    }

    /* Service location badges */
    .service-location-badge {
        display: inline-block;
        font-size: 0.7rem;
        padding: 0.15rem 0.4rem;
        border-radius: 3px;
        margin-top: 4px;
    }

    .salon-only-badge {
        background-color: #f8d7da;
        color: #721c24;
    }

    .home-available-badge {
        background-color: #d4edda;
        color: #155724;
    }

    /* Calendar Modal Styles */
    .calendar-modal-content {
        padding: 20px;
    }

    .calendar-input {
        width: 100%;
        height: 40px;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        cursor: pointer;
        background-color: #fff;
    }

    .staff-select {
        width: 100%;
        margin-top: 15px;
    }

    .timeslot-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .timeslot-btn {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .timeslot-btn:hover {
        background-color: #e9ecef;
    }

    .timeslot-btn.selected {
        background-color: #0d6efd;
        color: white;
    }

    .timeslot-btn.booked {
        background-color: #f8d7da;
        color: #721c24;
        cursor: not-allowed;
    }

    .staff-working-hours {
        background-color: #d4edda;
        color: #155724;
        padding: 8px 12px;
        border-radius: 4px;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .availability-message {
        margin-top: 10px;
        font-style: italic;
    }
</style>

<!-- Include Flatpickr CSS -->
<link rel="stylesheet" href="/assets/css/flatpickr.min.css">
@endsection

@section('scripts')
<!-- Include Flatpickr JS -->
<script src="/assets/js/flatpickr.js"></script>

<script>
    $(document).ready(function() {
        // Initialize variables
        let cart = [];
        let confirmActionCallback = null;

        // Check if we need to remove all scheduling information
        @if(session('removeSchedules'))
        function removeAllSchedulingInfo() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                let cartData = JSON.parse(savedCart);

                // Remove scheduling details from each item
                cartData = cartData.map(item => {
                    // Remove all scheduling properties
                    delete item.appointment_date;
                    delete item.appointment_time;
                    delete item.start_time;
                    delete item.end_time;
                    delete item.staff_id;
                    delete item.staff_name;

                    return item;
                });

                // Save the updated cart back to localStorage
                localStorage.setItem('cart', JSON.stringify(cartData));

            }
        }

        // Execute immediately
        removeAllSchedulingInfo();
        @endif

        // Helper functions for time formatting
        function convertTimeToMinutes(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }

        function formatMinutesToTime(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
        }

        function formatMinutesToTimeLabel(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            const period = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12; // Convert 0 to 12 for 12 AM

            return `${displayHours}:${String(mins).padStart(2, '0')} ${period}`;
        }

        function formatTime(timeStr) {
            return formatMinutesToTimeLabel(convertTimeToMinutes(timeStr));
        }

        // Load cart from localStorage
        function loadCart() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);

                // Ensure all required price properties exist
                cart = cart.map(item => {
                    // Make sure price_home is a valid number
                    if (!item.price_home || isNaN(parseFloat(item.price_home))) {
                        item.price_home = item.price * 1.5; // Set default home price if missing
                    }
                    return item;
                });

                updateCartUI();
            } else {
                showEmptyCart();
            }
        }

        // Save cart to localStorage
        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
        }

        // Helper function to get the appropriate price
        function getItemPrice(item) {
            // Use the price based on the service type
            if (item.service_type === 'home') {
                return parseFloat(item.price_home);
            } else {
                return parseFloat(item.price);
            }
        }

        // Update UI elements
        function updateCartUI() {
            if (cart.length === 0) {
                showEmptyCart();
                return;
            }

            // Show cart items table and hide empty message
            $('#emptyCartMessage').addClass('d-none');
            $('#cartItemsTable').removeClass('d-none');

            // Clear existing items
            $('#cartItemsList').empty();

            // Add each cart item to the table
            cart.forEach((item, index) => {
                // Get the price for the current service mode
                const price = getItemPrice(item);
                const totalPrice = price * item.quantity;

                // Service location badge
                let locationBadge = '';
                if (item.service_type === 'home') {
                    locationBadge = `<span class="service-location-badge home-available-badge">
                        <i class="fas fa-home"></i> {{ __('Home service') }}
                    </span>`;
                } else {
                    locationBadge = `<span class="service-location-badge salon-only-badge">
                        <i class="fas fa-building"></i> {{ __('Salon service') }}
                    </span>`;
                }

                const itemHtml = `
                    <tr data-index="${index}" data-service-id="${item.id}" data-unique-id="${item.unique_id || ''}" data-service-type="${item.service_type}">
                        <td>
                            <div class="cart-item-details">
                                <div class="cart-item-image">
                                    <img src="storage/${item.image}" alt="${item.name}">
                                </div>
                                <div class="cart-item-info ms-2">
                                    <h6 class="cart-item-name">${item.name}</h6>
                                    <span class="cart-item-category">${item.category_name} <span class="badge bg-light text-dark border border-secondary ms-1"><i class="far fa-clock fa-sm"></i> ${item.duration_minutes || 60} {{ __('minutes') }} </span></span>
                                    <div class="cart-item-location mt-1">
                                        ${locationBadge}
                                    </div>
                                    <div class="cart-item-scheduling mt-3">
                                        <button type="button" class="btn btn-sm p-2 rounded-1 btn-outline-primary schedule-service-btn" data-service-id="${item.id}" data-unique-id="${item.unique_id || ''}" data-service-type="${item.service_type}" data-service-duration="${item.duration_minutes}">
                                            <i class="far fa-calendar-alt"></i> ${item.appointment_date ? '{{ __("Reschedule") }}' : '{{ __("Schedule") }}'}
                                        </button>
                                        ${item.appointment_date ?
                                        `<div class="scheduled-info mt-2">
                                            <div class="text-success"><i class="far fa-check-circle"></i> {{ __('Scheduled') }}</div>
                                            <small class="d-block">{{ __('Date') }}: ${item.appointment_date}</small>
                                            <small class="d-block">{{ __('Start Time') }}: ${item.appointment_time}</small>
                                            ${item.end_time ? `<small class="d-block">{{ __('End Time') }}: ${formatMinutesToTimeLabel(convertTimeToMinutes(item.end_time))}</small>` : ''}
                                            <small class="d-block">{{ __('Staff') }}: ${item.staff_name || '{{ __("Any available") }}'}</small>
                                        </div>` : ''}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>${price.toFixed(2)} <span class="icon-saudi_riyal"></span></td>
                        <td>
                            <div class="quantity-controls">
                                <button type="button" class="btn-decrease">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="10">
                                <button type="button" class="btn-increase">+</button>
                            </div>
                        </td>
                        <td>${totalPrice.toFixed(2)} <span class="icon-saudi_riyal"></span></td>
                        <td>
                            <button type="button" class="remove-item" data-index="${index}">&times;</button>
                        </td>
                    </tr>
                `;

                $('#cartItemsList').append(itemHtml);
            });

            // Calculate and update totals
            calculateTotals();

            // Enable/disable checkout button
            $('#proceedToCheckout').prop('disabled', cart.length === 0);
        }

        // Show empty cart message
        function showEmptyCart() {
            $('#emptyCartMessage').removeClass('d-none');
            $('#cartItemsTable').addClass('d-none');
            $('#proceedToCheckout').prop('disabled', true);
            calculateTotals();
        }

        // Calculate order totals
        function calculateTotals() {
            // Check if cart is empty
            if (cart.length === 0) {
                $('#subtotal').html('0.00 <span class="icon-saudi_riyal"></span>');
                $('#vat').html('0.00 <span class="icon-saudi_riyal"></span>');
                $('#otherTaxes').html('0.00 <span class="icon-saudi_riyal"></span>');
                $('#total').html('0.00 <span class="icon-saudi_riyal"></span>');
                return;
            }

            // Extract service IDs and quantities
            const cartItems = cart.map(item => ({
                service_id: item.id,
                quantity: item.quantity,
                service_type: item.service_type
            }));

            // Call API to calculate totals including taxes
            $.ajax({
                url: '/api/cart/calculate-totals',
                method: 'POST',
                data: JSON.stringify({ items: cartItems }),
                contentType: 'application/json',
                success: function(response) {
                    // Calculate VAT percentage
                    let vatPercentage = 15; // Default
                    if (response.subtotal > 0) {
                        vatPercentage = Math.round((response.vat_amount / response.subtotal) * 100);
                    }

                    // Update cart display with accurate tax information
                    $('#subtotal').html(response.subtotal.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');
                    $('#vatLabel').html(`{{ __('VAT') }} (${vatPercentage}%):`);
                    $('#vat').html(response.vat_amount.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');

                    // Add other taxes display if not already present
                    if ($('#otherTaxes').length === 0) {
                        $('#vatRow').after(`
                            <div id="otherTaxesRow" class="d-flex justify-content-between mb-2">
                                <span>{{ __('Other Taxes:') }}</span>
                                <span id="otherTaxes">${response.other_taxes_amount.toFixed(2)} <span class="icon-saudi_riyal"></span></span>
                            </div>
                        `);
                    } else {
                        $('#otherTaxes').html(response.other_taxes_amount.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');
                    }

                    $('#total').html(response.total.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');
                },
                error: function() {
                    // Fallback calculation if API fails
                    let subtotal = 0;

                    // Sum all cart items
                    cart.forEach(item => {
                        // Get the price for the current service mode
                        const price = getItemPrice(item);
                        subtotal += price * item.quantity;
                    });

                    const vatRate = 0.15; // 15%
                    const vat = subtotal * vatRate;
                    const total = subtotal + vat;

                    // Calculate VAT percentage (using the default rate in fallback)
                    const vatPercentage = Math.round(vatRate * 100);

                    // Update the display
                    $('#subtotal').html(subtotal.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');
                    $('#vatLabel').html(`{{ __('VAT') }} (${vatPercentage}%):`);
                    $('#vat').html(vat.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');

                    // Ensure other taxes row exists
                    if ($('#otherTaxes').length === 0) {
                        $('#vatRow').after(`
                            <div id="otherTaxesRow" class="d-flex justify-content-between mb-2">
                                <span>{{ __('Other Taxes:') }}</span>
                                <span id="otherTaxes">0.00 <span class="icon-saudi_riyal"></span></span>
                            </div>
                        `);
                    } else {
                        $('#otherTaxes').html('0.00 <span class="icon-saudi_riyal"></span>');
                    }

                    $('#total').html(total.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');

                    console.error('{{ __("Failed to calculate taxes via API, using fallback calculation") }}');
                }
            });
        }

        // Event: Quantity increase button
        $(document).on('click', '.btn-increase', function() {
            const index = $(this).closest('tr').data('index');
            if (index >= 0 && index < cart.length) {
                // Check if the service is already scheduled
                if (cart[index].appointment_date && cart[index].start_time) {
                    // Ask for confirmation before changing quantity
                    if (confirm('{{ __("This service is already scheduled. If you want to change the quantity first un schedule it") }}')) {
                        // Remove scheduling data
                        delete cart[index].appointment_date;
                        delete cart[index].appointment_time;
                        delete cart[index].start_time;
                        delete cart[index].end_time;
                        delete cart[index].staff_id;
                        delete cart[index].staff_name;

                        // Then increase quantity
                        cart[index].quantity++;

                        // Show message
                        displayNotification('warning', '{{ __("Service has been unscheduled. Please schedule it again.") }}', 'topRight', 5);

                        saveCart();
                    }
                } else {
                    // Service not scheduled, proceed normally
                    cart[index].quantity++;
                    saveCart();
                }
            }
        });

        // Event: Quantity decrease button
        $(document).on('click', '.btn-decrease', function() {
            const index = $(this).closest('tr').data('index');
            if (index >= 0 && index < cart.length && cart[index].quantity > 1) {
                // Check if the service is already scheduled
                if (cart[index].appointment_date && cart[index].start_time) {
                    // Ask for confirmation before changing quantity
                    if (confirm('{{ __("This service is already scheduled. If you want to change the quantity first un schedule it") }}')) {
                        // Remove scheduling data
                        delete cart[index].appointment_date;
                        delete cart[index].appointment_time;
                        delete cart[index].start_time;
                        delete cart[index].end_time;
                        delete cart[index].staff_id;
                        delete cart[index].staff_name;

                        // Then decrease quantity
                        cart[index].quantity--;

                        // Show message
                        displayNotification('warning', '{{ __("Service has been unscheduled. Please schedule it again.") }}', 'topRight', 5);

                        saveCart();
                    }
                } else {
                    // Service not scheduled, proceed normally
                    cart[index].quantity--;
                    saveCart();
                }
            }
        });

        // Event: Quantity input change
        $(document).on('change', '.quantity-input', function() {
            const index = $(this).closest('tr').data('index');
            let quantity = parseInt($(this).val());

            // Ensure quantity is valid
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            } else if (quantity > 10) {
                quantity = 10;
            }

            // Update input value
            $(this).val(quantity);

            // Update cart
            if (index >= 0 && index < cart.length) {
                // Check if the service is already scheduled and quantity is changing
                if (cart[index].appointment_date && cart[index].start_time && cart[index].quantity !== quantity) {
                    // Ask for confirmation before changing quantity
                    if (confirm('{{ __("This service is already scheduled. If you want to change the quantity first un schedule it") }}')) {
                        // Remove scheduling data
                        delete cart[index].appointment_date;
                        delete cart[index].appointment_time;
                        delete cart[index].start_time;
                        delete cart[index].end_time;
                        delete cart[index].staff_id;
                        delete cart[index].staff_name;

                        // Then update quantity
                        cart[index].quantity = quantity;

                        // Show message
                        displayNotification('warning', '{{ __("Service has been unscheduled. Please schedule it again.") }}', 'topRight', 5);

                        saveCart();
                    } else {
                        // Reset to original quantity if user cancels
                        $(this).val(cart[index].quantity);
                    }
                } else {
                    // Service not scheduled, proceed normally
                    cart[index].quantity = quantity;
                    saveCart();
                }
            }
        });

        // Event: Remove item button click
        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            if (index >= 0 && index < cart.length) {
                cart.splice(index, 1);
                saveCart();

                // Show notification
                displayNotification('general', '{{ __("Item removed from cart") }}', 'topRight', 3);
            }
        });

        // Event: Clear cart button click
        $('#clearCartBtn').click(function() {
            if (cart.length === 0) return;

            // Show confirmation modal
            $('#confirmationModalLabel').text('{{ __("Clear Cart") }}');
            $('#confirmationModalBody').html('{{ __("Are you sure you want to remove all items from your cart?") }}');

            // Set the callback function
            confirmActionCallback = function() {
                cart = [];
                saveCart();
                $('#confirmationModal').modal('hide');

                // Show notification
                displayNotification('general', '{{ __("Cart has been cleared") }}', 'topRight', 3);
            };

            // Show the modal
            $('#confirmationModal').modal('show');
        });

        // Event: Proceed to checkout button click
        $('#proceedToCheckout').click(function() {
            if (cart.length === 0) {
                displayNotification('error', '{{ __("Your cart is empty. Please add services before proceeding to checkout.") }}', 'topRight', 5);
                return;
            }

            // Check if all services are scheduled
            const unscheduledServices = cart.filter(item => !item.appointment_date || !item.appointment_time);
            if (unscheduledServices.length > 0) {
                const serviceNames = unscheduledServices.map(item => item.name).join(', ');
                displayNotification('error', `{{ __("Please schedule the following services before proceeding to checkout:") }} ${serviceNames}`, 'topRight', 5);
                return;
            }

            // Prepare booking data
            const bookingData = {
                services: cart.map(item => ({
                    ...item,
                    // Ensure we're using the correct price based on service type
                    final_price: item.service_type === 'home' ? item.price_home : item.price
                })),
                subtotal: parseFloat($('#subtotal').text()),
                vat: parseFloat($('#vat').text()),
                other_taxes: parseFloat($('#otherTaxes').text() || '0'),
                total: parseFloat($('#total').text())
            };

            // Encode the data for URL
            const encodedData = encodeURIComponent(JSON.stringify(bookingData));

            // Redirect to checkout page with data
            window.location.href = `/checkout?data=${encodedData}`;
        });

        // Event: Confirm action button click
        $('#confirmActionBtn').click(function() {
            if (confirmActionCallback) {
                confirmActionCallback();
                confirmActionCallback = null;
            }
        });

        // Initialize
        loadCart();

        // Update cart count in floating button
        if (typeof updateCartCount === 'function') {
            updateCartCount();
        }

        // Add the scheduling modal HTML dynamically
        $('body').append(`
            <div class="modal fade" id="scheduleServiceModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __("Schedule Service") }}</h5>
                        </div>
                        <div class="modal-body calendar-modal-content">
                            <div id="schedulingError" class="alert alert-danger d-none"></div>

                            <div class="mb-3">
                                <label for="scheduleDateInput" class="form-label">{{ __("Select Date") }}</label>
                                <input type="text" id="scheduleDateInput" class="calendar-input" placeholder="{{ __("Select a date") }}" readonly>
                                <div id="dateMessage" class="small text-muted mt-1"></div>
                            </div>

                            <div id="staffSelectionContainer" class="mb-3 d-none">
                                <label for="staffSelect" class="form-label">{{ __("Select Staff") }}</label>
                                <select id="staffSelect" class="form-select staff-select">
                                    <option value="" selected disabled>{{ __("Choose a staff member") }}</option>
                                </select>
                                <div id="staffWorkingHours" class="staff-working-hours d-none"></div>
                            </div>

                            <div id="timeslotContainer" class="mb-3 d-none">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">{{ __("Select Time") }}</label>
                                    <span id="totalDurationInfo" class="text-primary small"></span>
                                </div>
                                <div id="timeslots" class="timeslot-container"></div>
                                <div id="noTimeslotsMessage" class="availability-message text-danger d-none">
                                    No available time slots for the selected date and staff. This may be due to the service duration, your selected quantity, or existing bookings.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                            <button type="button" class="btn btn-primary" id="confirmScheduleBtn" disabled>{{ __("Confirm") }}</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Initialize variables for scheduling
        let scheduleModal;
        let calendarInstance;
        let currentServiceId;
        let currentServiceType;
        let currentServiceDuration;
        let currentServiceQuantity;
        let currentCartIndex; // Store the exact index in the cart
        let selectedDate;
        let selectedStaffId;
        let selectedTimeslot;
        let availableDates = [];
        let mainPosId = 1; // Assuming the main branch POS ID is 1

        // Initialize the scheduling modal
        scheduleModal = new bootstrap.Modal(document.getElementById('scheduleServiceModal'));

        // Handle schedule button click
        $(document).on('click', '.schedule-service-btn', function() {
            // Reset previous selections
            resetSchedulingForm();

            // Get service details
            currentServiceId = $(this).data('service-id');
            currentServiceType = $(this).data('service-type');
            currentServiceDuration = parseInt($(this).data('service-duration')) || 60;

            // Get the unique ID directly from the data attribute
            window.currentUniqueId = $(this).data('unique-id') || null;

            // Get the current row's index to find the exact item in the cart
            const rowIndex = $(this).closest('tr').data('index');

            // Store the row index for later use when confirming
            currentCartIndex = rowIndex;

            // Use the exact item from the cart by index or unique ID
            let serviceItem;

            // First try to use the exact index
            if (rowIndex !== undefined && rowIndex >= 0 && rowIndex < cart.length) {
                serviceItem = cart[rowIndex];
            }
            // Then try to use the unique ID
            else if (window.currentUniqueId) {
                serviceItem = cart.find(item => item.unique_id === window.currentUniqueId);
            }
            // Fallback to ID and service type
            else {
                serviceItem = cart.find(item => item.id == currentServiceId && item.service_type === currentServiceType);
            }

            // Get the quantity from the found item
            currentServiceQuantity = serviceItem ? parseInt(serviceItem.quantity) : 1;
            // Load available dates
            loadAvailableDates();

            // Show the modal
            scheduleModal.show();
        });

        // Initialize date picker when dates are loaded
        function initDatePicker() {
            const dateInput = document.getElementById('scheduleDateInput');

            // Destroy existing instance if it exists
            if (calendarInstance) {
                calendarInstance.destroy();
            }

            // Configure flatpickr
            calendarInstance = flatpickr(dateInput, {
                minDate: "today",
                dateFormat: "Y-m-d",
                enable: availableDates, // Only enable the available dates
                onChange: function(selectedDates, dateStr) {
                    selectedDate = dateStr;

                    // Log date selection
                    let serviceItem;

                    // First try to find the item by unique ID if available
                    if (window.currentUniqueId) {
                        serviceItem = cart.find(item => item.unique_id === window.currentUniqueId);
                    }

                    // Fallback to service ID and type
                    if (!serviceItem) {
                        serviceItem = cart.find(item => item.id == currentServiceId && item.service_type === currentServiceType);
                    }

                    const totalDuration = currentServiceDuration * currentServiceQuantity;
                    // When date is selected, load staff
                    loadAvailableStaff();
                }
            });

            // Update message
            if (availableDates.length > 0) {
                $('#dateMessage').text(`${availableDates.length} {{ __("days available") }}`);
            } else {
                $('#dateMessage').text('{{ __("No available dates found") }}');
            }
        }

        // Load available dates from API
        function loadAvailableDates(serviceId, serviceType) {
            // Clear any previous errors
            $('#schedulingError').addClass('d-none');

            // Make sure we have the service ID
            serviceId = serviceId || currentServiceId;
            serviceType = serviceType || currentServiceType;

            // Call the API to get available dates - use the correct endpoint
            $.ajax({
                url: `/api/available-dates/${mainPosId}/${currentServiceId}/${currentServiceType}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        availableDates = response.data;
                        initDatePicker();
                    } else {
                        $('#schedulingError').removeClass('d-none').text('{{ __("No available dates found") }}');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading dates:', xhr);
                    $('#schedulingError').removeClass('d-none').text('{{ __("Error loading available dates") }}');
                }
            });
        }

        // Load available staff for the selected date
        function loadAvailableStaff() {
            $('#staffSelectionContainer').addClass('d-none');
            $('#timeslotContainer').addClass('d-none');
            $('#confirmScheduleBtn').prop('disabled', true);

            // Clear previous error message
            $('#schedulingError').addClass('d-none');

            // If no date is selected, don't try to load staff
            if (!selectedDate) {
                return;
            }

            $.ajax({
                url: `/api/available-staff/${mainPosId}/${selectedDate}/${currentServiceId}/${currentServiceType}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        const staffSelect = $('#staffSelect');
                        staffSelect.empty();
                        staffSelect.append('<option value="" selected disabled>{{ __("Choose a staff member") }}</option>');
                        response.data.forEach(staff => {
                            staffSelect.append(`<option value="${staff.id}"
                                data-start="${staff.working_hours.start_time}"
                                data-end="${staff.working_hours.end_time}">
                                ${staff.name} (${staff.position})
                            </option>`);
                        });

                        // Show staff selection
                        $('#staffSelectionContainer').removeClass('d-none');

                        // Reset staff ID and timeslot
                        selectedStaffId = null;
                        selectedTimeslot = null;
                    } else {
                        $('#schedulingError').removeClass('d-none').text('{{ __("No staff available for the selected date") }}');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading staff:', xhr);
                    $('#schedulingError').removeClass('d-none').text('{{ __("Error loading available staff") }}');
                }
            });
        }

        // Handle staff selection
        $(document).on('change', '#staffSelect', function() {
            selectedStaffId = $(this).val();

            if (selectedStaffId) {
                const startTime = $(this).find(':selected').data('start');
                const endTime = $(this).find(':selected').data('end');
                const staffName = $(this).find(':selected').text();

                // Log staff selection
                const totalDuration = currentServiceDuration * currentServiceQuantity;
                // Show working hours
                $('#staffWorkingHours').removeClass('d-none').html(`
                    <i class="far fa-clock"></i> {{ __("Working hours") }}: ${formatTime(startTime)} - ${formatTime(endTime)}
                `);

                // Load time slots
                loadTimeSlots(selectedStaffId);
            } else {
                $('#staffWorkingHours').addClass('d-none');
                $('#timeslotContainer').addClass('d-none');
                $('#confirmScheduleBtn').prop('disabled', true);
            }
        });

        // Load time slots based on staff schedule
        function loadTimeSlots(staffId) {
            $('#timeslotContainer').addClass('d-none');
            $('#noTimeslotsMessage').addClass('d-none');
            $('#timeslots').empty();
            $('#confirmScheduleBtn').prop('disabled', true);

            // Remove any previous end time badge
            $('#timeEndBadge').remove();

            $.ajax({
                url: `/api/staff-schedule/${staffId}/${selectedDate}/${currentServiceId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const { working_hours, booked_slots } = response.data;

                        // Create a copy of booked_slots to avoid modifying the original
                        let updatedBookedSlots = [...booked_slots];

                        // Check cart items for matching staff and date
                        cart.forEach(item => {
                            if (item.staff_id == staffId && item.appointment_date === selectedDate) {
                                // Add the cart item's time slot to booked_slots
                                updatedBookedSlots.push({
                                    start_time: item.start_time,
                                    end_time: item.end_time,
                                    formatted_start: formatMinutesToTimeLabel(convertTimeToMinutes(item.start_time)),
                                    formatted_end: formatMinutesToTimeLabel(convertTimeToMinutes(item.end_time))
                                });
                            }
                        });

                        // Ensure quantity is a number
                        currentServiceQuantity = parseInt(currentServiceQuantity) || 1;
                        const totalDuration = currentServiceDuration * currentServiceQuantity;

                        // Display total service duration info in the label area
                        $('#totalDurationInfo').text(`Total duration: ${totalDuration} min (${currentServiceDuration} × ${currentServiceQuantity})`);

                        // Generate time slots using the updated booked slots
                        const timeSlots = generateTimeSlots(
                            working_hours.start_time,
                            working_hours.end_time,
                            currentServiceDuration,
                            currentServiceQuantity,
                            updatedBookedSlots
                        );

                        if (timeSlots.length > 0) {
                            // Add time slots to the container
                            timeSlots.forEach(slot => {
                                const isBooked = slot.booked;
                                const btnClass = isBooked ? 'timeslot-btn booked' : 'timeslot-btn';
                                const disabled = isBooked ? 'disabled' : '';

                                $('#timeslots').append(`
                                    <button type="button" class="${btnClass}" ${disabled}
                                        data-start="${slot.start}"
                                        data-end="${slot.end}"
                                        data-end-display="${slot.endDisplay}">
                                        ${slot.start_label}
                                    </button>
                                `);
                            });

                            $('#timeslotContainer').removeClass('d-none');
                        } else {
                            $('#noTimeslotsMessage').removeClass('d-none');
                            $('#timeslotContainer').removeClass('d-none');
                        }
                    } else {
                        $('#schedulingError').removeClass('d-none').text('{{ __("Error loading staff schedule") }}');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading schedule:', xhr);
                    $('#schedulingError').removeClass('d-none').text('{{ __("Error loading staff schedule") }}');
                }
            });
        }

        // Handle time slot selection
        $(document).on('click', '.timeslot-btn:not(.booked)', function() {
            // Remove selected class from all buttons
            $('.timeslot-btn').removeClass('selected');

            // Add selected class to this button
            $(this).addClass('selected');

            // Get start time and other data
            const startTime = $(this).data('start');
            const endTime = $(this).data('end');
            const endTimeDisplay = $(this).data('end-display');

            // Store selected timeslot
            selectedTimeslot = {
                start: startTime,
                end: endTime,
                label: $(this).text().trim()
            };

            // Ensure quantity is a number
            const serviceQuantity = parseInt(currentServiceQuantity) || 1;
            const serviceDuration = parseInt(currentServiceDuration) || 60;
            const totalDuration = serviceDuration * serviceQuantity;

            // Show end time in green badge
            $('#timeEndBadge').remove(); // Remove previous badge if exists
            $(this).after(`<div id="timeEndBadge" class="mt-2 text-center">
                <span class="badge bg-success">Service will end at ${endTimeDisplay}</span>
            </div>`);

            // Enable confirm button
            $('#confirmScheduleBtn').prop('disabled', false);
        });

        // Helper function to generate time slots
        function generateTimeSlots(startTime, endTime, duration, quantity, bookedSlots) {
            const slots = [];
            const interval = 30; // 30-minute intervals
            const serviceDuration = parseInt(duration) || 60; // Default to 60 minutes
            const serviceQuantity = parseInt(quantity) || 1; // Ensure quantity is a number
            const totalDuration = serviceDuration * serviceQuantity; // Total duration based on quantity

            // Convert times to minutes since midnight for easier calculation
            const startMinutes = convertTimeToMinutes(startTime);
            const endMinutes = convertTimeToMinutes(endTime);

            // Check if selected date is today
            const isToday = selectedDate === new Date().toISOString().split('T')[0];
            // Get current time in minutes if today
            const currentTimeMinutes = isToday ?
                (new Date().getHours() * 60 + new Date().getMinutes()) : 0;

            // Generate slots at 30-minute intervals
            for (let time = startMinutes; time <= endMinutes - totalDuration; time += interval) {
                const slotStart = time;
                const slotEnd = time + totalDuration;

                // Check if this slot overlaps with any booked slot
                let isBooked = bookedSlots.some(bookedSlot => {
                    const bookedStart = convertTimeToMinutes(bookedSlot.start_time);
                    const bookedEnd = convertTimeToMinutes(bookedSlot.end_time);

                    // Check for any overlap between the proposed time slot and booked slot
                    const hasOverlap = (slotStart < bookedEnd && slotEnd > bookedStart);

                    return hasOverlap;
                });

                // If date is today, check if time slot is in the past
                if (isToday && slotStart <= currentTimeMinutes) {
                    isBooked = true;
                }

                slots.push({
                    start: formatMinutesToTime(slotStart),
                    end: formatMinutesToTime(slotEnd),
                    start_label: formatMinutesToTimeLabel(slotStart),
                    endDisplay: formatMinutesToTimeLabel(slotEnd),
                    booked: isBooked
                });
            }

            return slots;
        }

        // Reset the scheduling form
        function resetSchedulingForm() {
            $('#schedulingError').addClass('d-none');
            $('#staffSelectionContainer').addClass('d-none');
            $('#timeslotContainer').addClass('d-none');
            $('#staffWorkingHours').addClass('d-none');
            $('#noTimeslotsMessage').addClass('d-none');
            $('#confirmScheduleBtn').prop('disabled', true);

            selectedDate = null;
            selectedStaffId = null;
            selectedTimeslot = null;

            if (calendarInstance) {
                calendarInstance.clear();
            }
        }

        // Handle confirm button click
        $('#confirmScheduleBtn').on('click', function() {
            if (!selectedTimeslot) {
                displayNotification('error', '{{ __("Please select a time slot first") }}', 'topRight', 5);
                return;
            }

            // First check if the index is valid in the global cart array
            if (currentCartIndex !== undefined && currentCartIndex >= 0 && currentCartIndex < cart.length) {
                // Update the item in the global cart array directly
                let item = cart[currentCartIndex];

                // Update the item in the cart with scheduling details using the correct property names
                item.appointment_date = selectedDate;
                item.appointment_time = selectedTimeslot.label;
                item.start_time = selectedTimeslot.start;
                item.end_time = selectedTimeslot.end;
                item.staff_id = selectedStaffId;

                // Get staff name for display
                item.staff_name = $('#staffSelect option:selected').text();

                // Save the updated cart to localStorage
                saveCart();

                // Show success notification
                displayNotification('success', '{{ __("Service scheduled successfully") }}', 'topRight', 5);

                // Close the scheduling modal
                scheduleModal.hide();

                // Update the UI to show scheduled status
                updateCartUI();
            } else {
                // Fallback: search for the item by unique_id or ID+service_type
                let itemIndex = -1;

                // First try by unique ID
                if (window.currentUniqueId) {
                    itemIndex = cart.findIndex(i => i.unique_id === window.currentUniqueId);
                }

                // If not found, try by ID and service type
                if (itemIndex === -1) {
                    itemIndex = cart.findIndex(i => i.id == currentServiceId && i.service_type === currentServiceType);
                }

                if (itemIndex !== -1) {
                    // We found the item in the global cart array
                    let item = cart[itemIndex];

                    // Update the item in the cart with scheduling details using the correct property names
                    item.appointment_date = selectedDate;
                    item.appointment_time = selectedTimeslot.label;
                    item.start_time = selectedTimeslot.start;
                    item.end_time = selectedTimeslot.end;
                    item.staff_id = selectedStaffId;

                    // Get staff name for display
                    item.staff_name = $('#staffSelect option:selected').text();
                    // Save the updated cart to localStorage
                    saveCart();

                    // Show success notification
                    displayNotification('success', '{{ __("Service scheduled successfully") }}', 'topRight', 5);

                    // Close the scheduling modal
                    scheduleModal.hide();

                    // Update the UI to show scheduled status
                    updateCartUI();
                } else {
                    // Could not find the item in the global cart
                    displayNotification('error', '{{ __("Could not find the service in the cart") }}', 'topRight', 5);
                }
            }
        });

        // Load time slots for selected date
        function loadAvailableTimeSlots(selectedDate) {
            // Clear previous time slots
            resetTimeSlots();

            // Clear any previous errors
            $('#schedulingError').addClass('d-none');

            // Make sure we have the service ID and selected date
            if (!currentServiceId || !selectedDate) {
                return;
            }


            // We need to use the staff-schedule endpoint after we have a selected staff member
            // This needs to be handled differently since we need a staff ID first
            // For now, let's just show a message to select a staff member
            $('#schedulingError').removeClass('d-none').text('{{ __("Please select a staff member first to view available time slots") }}');
        }

        // Reset time slots dropdown
        function resetTimeSlots() {
            $('#timeSlotSelector').empty();
            $('#timeSlotSelector').append('<option value="">{{ __("Select a time slot") }}</option>');
            $('#timeSlotSelector').prop('disabled', true);
            $('#staffSelector').empty();
            $('#staffSelector').append('<option value="">{{ __("Select a staff member") }}</option>');
            $('#staffSelector').prop('disabled', true);
            $('#scheduler-message').html('');
        }

        // Load staff members for selected time slot
        function loadStaffMembers(selectedDate, selectedTimeSlot) {
            // Clear previous staff members
            $('#staffSelector').empty();
            $('#staffSelector').append('<option value="">{{ __("Select a staff member") }}</option>');
            $('#staffSelector').prop('disabled', true);

            // Clear any previous errors
            $('#schedulingError').addClass('d-none');

            // Make sure we have the service ID, selected date, and time slot
            if (!currentServiceId || !selectedDate || !selectedTimeSlot) {
                return;
            }

            // Use the correct endpoint for loading staff
            $.ajax({
                url: `/api/available-staff/${mainPosId}/${selectedDate}/${currentServiceId}/${currentServiceType}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        const staffSelect = $('#staffSelector');
                        staffSelect.empty();
                        staffSelect.append('<option value="" selected disabled>{{ __("Choose a staff member") }}</option>');

                        response.data.forEach(staff => {
                            staffSelect.append(`<option value="${staff.id}"
                                data-start="${staff.working_hours.start_time}"
                                data-end="${staff.working_hours.end_time}">
                                ${staff.name} (${staff.position})
                            </option>`);
                        });

                        // Enable the staff selector
                        $('#staffSelector').prop('disabled', false);
                    } else {
                        $('#schedulingError').removeClass('d-none').text('{{ __("No staff available for the selected date and time") }}');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading staff:', xhr);
                    $('#schedulingError').removeClass('d-none').text('{{ __("Error loading available staff") }}');
                }
            });
        }
    });
</script>
@endsection
