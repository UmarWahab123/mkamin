@extends('layouts.app')

@section('title', __('Checkout - mcs.sa Salon'))

@section('content')

    <!-- CHECKOUT SECTION -->
    <div id="checkout" class="pt-8 pb-7 checkout-section division">
        <div class="container">
            <div class="row mt-5">
                <!-- Left Column - Customer Information -->
                <div class="col-lg-7 order-lg-1 order-2">
                    <div class="checkout-form-panel mb-5">
                        @auth
                            <h3 class="h3-md mb-4">{{ __('Personal Information') }}</h3>

                            <form id="checkoutForm" class="row">
                                <!-- Personal Information -->
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }}*</label>
                                    <input type="text" class="form-control" id="name"
                                        value="{{ auth()->user()->customer?->name ?? auth()->user()->name }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address*') }}</label>
                                    <input type="email" class="form-control" id="email"
                                        value="{{ auth()->user()->customer?->email ?? auth()->user()->email }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    @include('components.phone-input', [
                                        'fieldName' => 'phone_number',
                                        'label' => 'Phone Number',
                                        'defaultValue' => auth()->user()->customer?->phone_number ?? auth()->user()->phone_number,
                                        'required' => true,
                                    ])
                                </div>

                                <!-- Home Service Details (shown only for home services) -->
                                <div id="homeServiceDetails" class="col-12 mb-3 d-none">
                                    <h4 class="h4-xs mt-4 mb-3">{{ __('Home Service Details') }}</h4>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">{{ __('Address*') }}</label>
                                            <input type="text" class="form-control" id="address"
                                                value="{{ auth()->user()->customer?->address }}">
                                        </div>

                                        <div class="col-12">
                                            @include('components.location-map', [
                                                'id' => 'staff',
                                                'buttonClass' => 'btn--gold',
                                                'addressFieldId' => 'address',
                                                'default_lat' => auth()->user()->customer?->latitude ?? null,
                                                'default_lng' => auth()->user()->customer?->longitude ?? null,
                                            ])
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Notes -->
                                <div class="col-12 mb-3">
                                    <label for="notes" class="form-label">{{ __('Additional Notes') }}</label>
                                    <textarea class="form-control" id="notes" rows="3"></textarea>
                                </div>

                                <!-- Payment Method Selection -->
                                <div class="col-12 mb-4">
                                    <label class="form-label">{{ __('Payment Method') }}</label>
                                    <div class="payment-methods">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-1" type="radio" name="payment_option"
                                                id="visa_mastercard" value="visa_mastercard" checked>
                                            <label class="form-check-label ms-3" for="visa_mastercard">
                                                <img src="{{ asset('assets/images/visa.svg') }}" alt="visa"
                                                    style="height: 20px;" class="me-1"> <img
                                                    src="{{ asset('assets/images/master.svg') }}" alt="master"
                                                    style="height: 20px;" class="me-1"> {{ __('Visa/Mastercard') }} </label>
                                        </div>
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-1" type="radio" name="payment_option"
                                                id="mada" value="mada">
                                            <label class="form-check-label ms-3" for="mada">
                                                <img src="{{ asset('assets/images/mada.svg') }}" alt="Mada"
                                                    style="height: 40px;" class="me-1"> {{ __('Mada') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termsAccepted" required>
                                        <label class="form-check-label" for="termsAccepted">
                                            {{ __('I accept the') }} <a href="#"
                                                class="text-decoration-underline">{{ __('Terms and Conditions') }}</a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn--black hover--black">{{ __('Proceed to Payment') }}</button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <h3 class="h3-md mb-3">{{ __('Please Login or Register') }}</h3>
                                <p class="text-muted mb-4">
                                    {{ __('To complete your booking, please login or create an account.') }}</p>

                                <!-- Tabs Navigation -->
                                <ul class="nav nav-tabs justify-content-center mb-4" id="authTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="login-tab" data-bs-toggle="tab"
                                            data-bs-target="#login-form" type="button" role="tab"
                                            aria-controls="login-form" aria-selected="false">
                                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="register-tab" data-bs-toggle="tab"
                                            data-bs-target="#register-form" type="button" role="tab"
                                            aria-controls="register-form" aria-selected="true">
                                            <i class="fas fa-user-plus me-2"></i>{{ __('Register') }}
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content" id="authTabsContent">
                                    <!-- Login Tab -->
                                    <div class="tab-pane fade" id="login-form" role="tabpanel" aria-labelledby="login-tab">
                                        <form method="POST" action="{{ route('login', ['redirect' => 'checkout']) }}"
                                            class="text-start">
                                            @csrf
                                            <input type="hidden" name="redirect" value="checkout">

                                            <div class="mb-3">
                                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}" required autofocus>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn--black hover--black">
                                                    {{ __('Login') }}
                                                </button>
                                            </div>

                                            <div class="text-center mt-3">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request', ['redirect' => 'checkout']) }}"
                                                        class="text-decoration-underline">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="text-center mt-3">
                                                <p class="text-muted mb-0">
                                                    {{ __('New here?') }}
                                                    <a href="#" class="text-decoration-underline switch-tab"
                                                        data-target="register-tab">
                                                        {{ __('Please register') }}
                                                    </a>
                                                </p>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Register Tab -->
                                    <div class="tab-pane fade show active" id="register-form" role="tabpanel"
                                        aria-labelledby="register-tab">
                                        <form method="POST"
                                            action="{{ route('quickRegisteration', ['redirect' => 'checkout']) }}"
                                            class="text-start" id="quick-registration-form">
                                            @csrf
                                            <input type="hidden" name="redirect" value="checkout">

                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    id="reg-name" name="name" value="{{ old('name') }}" required
                                                    autofocus>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="reg-email" class="form-label">{{ __('Email Address') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="reg-email"
                                                    name="email" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                @include('components.phone-input', [
                                                    'fieldName' => 'phone',
                                                    'label' => 'Phone Number',
                                                    'required' => true,
                                                ])
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="reg-password" class="form-label">{{ __('Password') }}</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="reg-password" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password-confirm"
                                                    class="form-label">{{ __('Confirm Password') }}</label>
                                                <input type="password" class="form-control" id="password-confirm"
                                                    name="password_confirmation" required>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn--black hover--black">
                                                    {{ __('Register') }}
                                                </button>
                                            </div>

                                            <div class="text-center mt-3">
                                                <p class="text-muted mb-0">
                                                    {{ __('Already have an account?') }}
                                                    <a href="#" class="text-decoration-underline switch-tab"
                                                        data-target="login-tab">
                                                        {{ __('Login now') }}
                                                    </a>
                                                </p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Right Column - Booking Summary -->
                <div class="col-lg-5 order-lg-2 order-1 mb-5 mb-lg-0">
                    <div class="booking-summary-panel">
                        <h3 class="h3-md mb-4">{{ __('Booking Summary') }}</h3>

                        <!-- Selected Services -->
                        <div id="summarySelectedServices" class="mb-4">
                            <h4 class="h4-xs mb-3">{{ __('Selected Services') }}</h4>
                            <div class="selected-services-list">
                                @if (isset($bookingData) && !empty($bookingData['services']))
                                    @foreach ($bookingData['services'] as $service)
                                        @php
                                            $price =
                                                $service['service_type'] === 'home'
                                                    ? $service['price_home']
                                                    : $service['price'];
                                            $total = $price * $service['quantity'];
                                        @endphp
                                        <div class="summary-service-item">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">{{ $service['name'] }}</div>
                                                        <div class="small d-flex align-items-end">
                                                            <span
                                                                class="service-location-badge {{ $service['service_type'] === 'home' ? 'home-available-badge' : 'salon-only-badge' }}">
                                                                <i
                                                                    class="fas fa-{{ $service['service_type'] === 'home' ? 'home' : 'building' }}"></i>
                                                                {{ $service['service_type'] === 'home' ? __('Home service') : __('Salon service') }}
                                                            </span>
                                                            <span class="mx-4">{{ number_format($price, 2) }} <span
                                                                    class="icon-saudi_riyal"></span> x
                                                                {{ $service['quantity'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        {{ number_format($total, 2) }} <span
                                                            class="icon-saudi_riyal"></span>
                                                    </div>
                                                </div>
                                                @if (isset($service['appointment_date']))
                                                    <div
                                                        class="small d-flex align-items-center justify-content-start flex-wrap text-muted mt-1">
                                                        <span class="text-nowrap"><i class="far fa-calendar-alt"></i>
                                                            {{ $service['appointment_date'] }}</span>
                                                        <span class="mx-3 text-nowrap"><i class="far fa-clock ms-1"></i>
                                                            {{ $service['appointment_time'] }}</span>

                                                        <span class="text-nowrap">
                                                            {{ $service['staff_name'] ?? 'Any available staff' }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="small text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        {{ __('Not scheduled') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-summary-message">{{ __('No services found') }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Discount Code -->
                        <div id="discountCodeSection" class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="discountCode"
                                    placeholder="{{ __('Enter discount code') }}"
                                    value="{{ $bookingData['discount_code'] ?? '' }}"
                                    {{ isset($bookingData['discount_code']) ? 'readonly' : '' }}>
                                <button
                                    class="btn {{ isset($bookingData['discount_code']) ? 'btn-danger' : 'btn--black hover--black' }}"
                                    type="button"
                                    id="applyDiscount">{{ isset($bookingData['discount_code']) ? __('Remove') : __('Apply') }}</button>
                            </div>
                            <div id="discountMessage"
                                class="small mt-1 {{ isset($bookingData['discount_code']) ? 'text-success' : 'd-none' }}">
                                {{ isset($bookingData['discount_code']) ? __('Discount applied successfully') : '' }}
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="summary-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Subtotal:') }}</span>
                                <span id="summarySubtotal">{{ number_format($bookingData['subtotal'] ?? 0, 2) }} <span
                                        class="icon-saudi_riyal"></span></span>
                            </div>
                            <div id="summaryDiscountRow"
                                class="d-flex justify-content-between mb-2 {{ isset($bookingData['discount_amount']) && $bookingData['discount_amount'] > 0 ? '' : 'd-none' }}">
                                <span>{{ __('Discount:') }}</span>
                                <span id="summaryDiscount"
                                    class="text-success">-{{ number_format($bookingData['discount_amount'] ?? 0, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>
                            <div id="summaryAfterDiscountRow"
                                class="d-flex justify-content-between mb-2 {{ isset($bookingData['after_discount']) ? '' : 'd-none' }}">
                                <span>{{ __('After Discount:') }}</span>
                                <span
                                    id="summaryAfterDiscount">{{ number_format($bookingData['after_discount'] ?? 0, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>
                            <div id="summaryVatRow" class="d-flex justify-content-between mb-2">
                                <span id="summaryVatLabel">{{ __('VAT') }} (15%):</span>
                                <span id="summaryVat">{{ number_format($bookingData['vat'] ?? 0, 2) }} <span
                                        class="icon-saudi_riyal"></span></span>
                            </div>
                            <div id="summaryOtherTaxesRow"
                                class="d-flex justify-content-between mb-2 {{ isset($bookingData['other_taxes']) && $bookingData['other_taxes'] > 0 ? '' : 'd-none' }}">
                                <span>{{ __('Other Taxes:') }}</span>
                                <span id="summaryOtherTaxes">{{ number_format($bookingData['other_taxes'] ?? 0, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>{{ __('Total:') }}</span>
                                <span id="summaryTotal">{{ number_format($bookingData['total'] ?? 0, 2) }} <span
                                        class="icon-saudi_riyal"></span></span>
                            </div>
                            @if (isset($bookingData['discount_amount']) && $bookingData['discount_amount'] > 0)
                                <div id="totalSavingsMessage" class="text-success small mt-2 text-end">
                                    <i class="fas fa-tags"></i> {{ __('You saved') }}
                                    {{ number_format($bookingData['discount_amount'], 2) }} <span
                                        class="icon-saudi_riyal"></span>
                                </div>
                            @endif
                        </div>

                        <!-- Back to Cart Button -->
                        <div class="mt-4 text-center">
                            <a href="/cart" class="btn btn--tra-black hover--black w-100">{{ __('Back to Cart') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CHECKOUT SECTION -->
@endsection

@section('styles')
    <style>
        .checkout-form-panel {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .booking-summary-panel {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            position: sticky;
            top: 20px;
        }

        .selected-services-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .summary-service-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .empty-summary-message {
            color: #6f6f6f;
            text-align: center;
            padding: 20px;
        }

        .summary-totals {
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 0;
            margin-bottom: 20px;
        }

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

        @media (max-width: 991px) {
            .booking-summary-panel {
                margin-bottom: 30px;
            }
        }
    </style>
@endsection

@section('scripts')
    @include('components.notification')
    <script>
        var bookingData = @json($bookingData ?? null);
        // console.log(bookingData);

        $(document).ready(function() {
            // Handle tab switching links
            $('.switch-tab').on('click', function(e) {
                e.preventDefault();
                const targetTab = $(this).data('target');
                $(`#${targetTab}`).tab('show');
            });


            if (!bookingData) {
                // No booking data found, redirect to cart page
                window.location.href = '/cart';
                return;
            }

            // Check if any services are home services
            const hasHomeServices = bookingData.services.some(service => service.service_type === 'home');

            // Show/hide home service details form based on service types
            if (hasHomeServices) {
                $('#homeServiceDetails').removeClass('d-none');
                $('#address, #staff-latitude, #staff-longitude').attr('required', true);
                // Initialize map when home services are detected
                // setTimeout(initMap, 200);
            } else {
                $('#homeServiceDetails').addClass('d-none');
                $('#address, #staff-latitude, #staff-longitude').removeAttr('required');
            }

            // Handle apply discount button click
            $('#applyDiscount').on('click', function() {
                const $btn = $(this);
                const isRemoving = $btn.hasClass('btn-danger');

                if (isRemoving) {
                    removeDiscount();
                    return;
                }

                // Get discount code
                const discountCode = $('#discountCode').val().trim();

                // Validate discount code
                if (!discountCode) {
                    showDiscountMessage('error', 'Please enter a discount code');
                    return;
                }

                // Disable apply button and show loading state
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Applying...'
                );

                // Extract service IDs and quantities
                const cartItems = bookingData.services.map(item => ({
                    service_id: item.id,
                    quantity: item.quantity,
                    service_type: item.service_type
                }));

                // Call API to verify discount and calculate new totals
                $.ajax({
                    url: '/discount/verify',
                    method: 'POST',
                    data: JSON.stringify({
                        discount_code: discountCode,
                        customer_id: '{{ auth()->user()?->customer->id ?? null }}',
                        items: cartItems
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update bookingData with new values
                            bookingData = {
                                ...bookingData,
                                discount_amount: response.discount_amount,
                                discount_code: response.discount_details.code,
                                discount_details: response.discount_details,
                                other_taxes: response.other_taxes_amount,
                                subtotal: response.subtotal,
                                total: response.total,
                                vat: response.vat_amount
                            };

                            // Show success message
                            showDiscountMessage('success', 'Discount applied successfully');

                            // Update UI with new totals
                            updateTotals(response);

                            // Disable discount code input
                            $('#discountCode').prop('readonly', true);

                            // Change apply button to remove button and enable it
                            $btn.removeClass('btn--black hover--black')
                                .addClass('btn-danger')
                                .html('{{ __('Remove') }}')
                                .prop('disabled', false);
                        } else {
                            // Show error message
                            showDiscountMessage('error', response.message ||
                                'Failed to apply discount code');

                            // Reset apply button
                            $btn.prop('disabled', false).html('{{ __('Apply') }}');
                        }
                    },
                    error: function(xhr) {
                        // Parse error message
                        let errorMessage = 'Failed to apply discount code';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        // Show error message
                        showDiscountMessage('error', errorMessage);

                        // Reset apply button
                        $btn.prop('disabled', false).html('{{ __('Apply') }}');
                    }
                });
            });

            // Function to remove applied discount
            function removeDiscount() {
                const $btn = $('#applyDiscount');

                // Show loading state on the button
                $btn.prop('disabled', true)
                    .html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Removing...') }}'
                    );

                // Call API to remove discount from session
                $.ajax({
                    url: '/discount/remove',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update bookingData to remove discount
                            bookingData = {
                                ...bookingData,
                                discount_amount: 0,
                                discount_code: null,
                                discount_details: null,
                                subtotal: response.subtotal,
                                total: response.total,
                                vat: response.vat_amount
                            };

                            // Update UI with original totals
                            updateTotals(response);

                            // Clear discount code input
                            $('#discountCode').val('').prop('readonly', false);

                            // Hide discount message
                            $('#discountMessage').addClass('d-none');

                            // Change button back to apply and enable it
                            $btn.removeClass('btn-danger')
                                .addClass('btn--black hover--black')
                                .html('{{ __('Apply') }}')
                                .prop('disabled', false);
                        } else {
                            // Show error message
                            showDiscountMessage('error', response.message ||
                                '{{ __('Failed to remove discount') }}');

                            // Reset button state
                            $btn.prop('disabled', false).html('{{ __('Remove') }}');
                        }
                    },
                    error: function(xhr) {
                        // Show error message
                        let errorMessage = '{{ __('Failed to remove discount') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showDiscountMessage('error', errorMessage);

                        // Reset button state
                        $btn.prop('disabled', false).html('{{ __('Remove') }}');
                    }
                });
            }

            // Function to update totals in the UI
            function updateTotals(response) {
                // Update subtotal
                $('#summarySubtotal').html(response.subtotal.toFixed(2) +
                    ' <span class="icon-saudi_riyal"></span>');

                // Handle discount
                if (response.discount_amount && response.discount_amount > 0) {
                    $('#summaryDiscountRow').removeClass('d-none');
                    $('#summaryDiscount').html('-' + response.discount_amount.toFixed(2) +
                        ' <span class="icon-saudi_riyal"></span>');
                    $('#summaryAfterDiscountRow').removeClass('d-none');
                    $('#summaryAfterDiscount').html(response.after_discount.toFixed(2) +
                        ' <span class="icon-saudi_riyal"></span>');
                } else {
                    $('#summaryDiscountRow').addClass('d-none');
                    $('#summaryAfterDiscountRow').addClass('d-none');
                }

                // Update VAT
                $('#summaryVat').html(response.vat_amount.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');

                // Handle other taxes
                if (response.other_taxes_amount && response.other_taxes_amount > 0) {
                    $('#summaryOtherTaxesRow').removeClass('d-none');
                    $('#summaryOtherTaxes').html(response.other_taxes_amount.toFixed(2) +
                        ' <span class="icon-saudi_riyal"></span>');
                } else {
                    $('#summaryOtherTaxesRow').addClass('d-none');
                }

                // Update total
                $('#summaryTotal').html(response.total.toFixed(2) + ' <span class="icon-saudi_riyal"></span>');

                // Update savings message
                if (response.discount_amount && response.discount_amount > 0) {
                    const savingsMessage = `
                        <div id="totalSavingsMessage" class="text-success small mt-2 text-end">
                            <i class="fas fa-tags"></i> {{ __('You saved') }} ${response.discount_amount.toFixed(2)} <span class="icon-saudi_riyal"></span>
                        </div>
                    `;
                    if ($('#totalSavingsMessage').length === 0) {
                        $('#summaryTotal').parent().after(savingsMessage);
                    } else {
                        $('#totalSavingsMessage').replaceWith(savingsMessage);
                    }
                } else {
                    $('#totalSavingsMessage').remove();
                }
            }

            // Function to show discount messages
            function showDiscountMessage(type, message) {
                const messageElement = $('#discountMessage');
                messageElement.removeClass('d-none text-success text-danger');

                if (type === 'success') {
                    messageElement.addClass('text-success');
                } else {
                    messageElement.addClass('text-danger');
                }

                messageElement.text(message);
            }

            // Modify the form submission handler
            $('#checkoutForm').submit(function(e) {
                e.preventDefault();
                // Validate phone number using the component's validation function
                if (!window.validatePhoneInputForSubmit('phone_number')) {
                    return false;
                }
                // Disable submit button to prevent double submission
                $('button[type="submit"]').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}'
                );

                // Collect all required form data
                const formData = new FormData();

                // Add customer details (required fields based on the error)
                formData.append('name', $('#name').val());
                formData.append('email', $('#email').val());
                formData.append('phone', $('#phone_number').val());

                // Only use HyperPay as payment method
                formData.append('paymentMethod', 'hyperpay');

                // Add notes if available
                formData.append('notes', $('#notes').val() || '');

                // Add user_id if available
                const userId = '{{ auth()->id() ?? '' }}';
                if (userId) {
                    formData.append('user_id', userId);
                }

                // Add customer_id if available
                const customerId = '{{ auth()->user()->customer->id ?? '' }}';
                if (customerId && customerId.trim() !== '') {
                    formData.append('customer_id', customerId);
                }

                // If home service, add address details
                if ($('#homeServiceDetails').is(':visible')) {
                    formData.append('address', $('#address').val() || '');
                    formData.append('latitude', $('#staff-latitude').val() || '');
                    formData.append('longitude', $('#staff-longitude').val() || '');
                }

                // Add booking data - this is critical
                if (!bookingData) {
                    displayNotification('error',
                        '{{ __('Error: No booking data found. Please try again.') }}');
                    $('button[type="submit"]').prop('disabled', false).html(
                        '{{ __('Complete Booking') }}');
                    return;
                }

                // Add order items data with appointment details
                const orderItems = bookingData.services.map(service => ({
                    ...service,
                    product_and_service_id: service.id,
                    appointment_date: service.appointment_date,
                    start_time: service.start_time,
                    end_time: service.end_time,
                    staff_id: service.staff_id
                }));

                formData.append('order_items', JSON.stringify(orderItems));

                // Add discount information if it exists
                if (bookingData.discount_code) {
                    formData.append('discount_code', bookingData.discount_code);
                    formData.append('discount_amount', bookingData.discount_amount);
                }

                // Add totals - these are required fields
                formData.append('subtotal', bookingData.subtotal);
                formData.append('vat', bookingData.vat);
                formData.append('other_taxes', bookingData.other_taxes || 0);
                formData.append('total', bookingData.total);

                // Submit the form via AJAX
                $.ajax({
                    url: '/api/booking/process',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.payment_pending) {
                                // New HyperPay flow: Prepare checkout and redirect to payment form
                                $.ajax({
                                    url: '{{ route('hyperpay.checkout') }}',
                                    method: 'POST',
                                    data: JSON.stringify({
                                        reservation_id: response.reservation_id,
                                        amount: response.amount,
                                        payment_method: $(
                                            'input[name="payment_option"]:checked'
                                        ).val() || 'visa_mastercard'
                                    }),
                                    contentType: 'application/json',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    success: function(paymentResponse) {
                                        if (paymentResponse.success) {
                                            localStorage.removeItem('cart');
                                            // Redirect to the payment form page
                                            window.location.href =
                                                '{{ route('hyperpay.form') }}';
                                        } else {
                                            // Show error
                                            displayNotification('error',
                                                'Payment initialization failed: ' +
                                                (paymentResponse.message ||
                                                    'Unknown error'));
                                            $('button[type="submit"]').prop(
                                                'disabled', false).html(
                                                '{{ __('Complete Booking') }}'
                                            );
                                        }
                                    },
                                    error: function(xhr) {
                                        console.error('Payment Error:', xhr);
                                        displayNotification('error',
                                            'Payment initialization failed. Please try again.'
                                        );
                                        $('button[type="submit"]').prop('disabled',
                                            false).html(
                                            '{{ __('Complete Booking') }}');
                                    }
                                });
                            } else {
                                // Clear cart data
                                localStorage.removeItem('cart');

                                // Redirect to confirmation page
                                window.location.href = response.redirect;
                            }
                        } else {
                            // Show error message
                            displayNotification('error', 'Error: ' + (response.message ||
                                'An error occurred'));
                            $('button[type="submit"]').prop('disabled', false).html(
                                '{{ __('Complete Booking') }}');
                        }
                    },
                    error: function(xhr) {
                        console.error('XHR Error:', xhr);

                        // Parse error message
                        let errorMessage = 'An error occurred while processing your booking.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;

                            // If we have validation errors, show them in detail
                            if (xhr.responseJSON.errors) {
                                errorMessage += '\n\nDetails:';
                                for (const field in xhr.responseJSON.errors) {
                                    errorMessage += '\n• ' + xhr.responseJSON.errors[field][0];
                                }
                            }

                            // If we have a redirect URL, redirect to it
                            if (xhr.responseJSON.redirect) {
                                displayNotification('error', xhr.responseJSON.message);
                                setTimeout(() => {
                                    window.location.href = xhr.responseJSON.redirect;
                                    return;
                                }, 2500);
                            }
                        } else {
                            // Show error message
                            displayNotification('error', 'Error: ' + errorMessage);
                        }

                        $('button[type="submit"]').prop('disabled', false).html(
                            '{{ __('Complete Booking') }}');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('quick-registration-form');

            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // Validate phone number before submission
                    if (!window.validatePhoneInputForSubmit('phone')) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
@endsection
