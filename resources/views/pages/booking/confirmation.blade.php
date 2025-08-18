@extends('layouts.app')

@section('title', __('Booking Confirmed - mcs.sa Salon'))

@section('content')
    <!-- CONFIRMATION SECTION -->
    <div id="confirmation" class="pt-8 pb-7 confirmation-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-panel text-center mb-5">
                        <div class="confirmation-icon mb-4">
                            @switch($reservation->status)
                                @case('pending')
                                    <i class="fas fa-clock fa-5x text-warning"></i>
                                @break

                                @case('confirmed')
                                    <i class="fas fa-check-circle fa-5x text-primary"></i>
                                @break

                                @case('completed')
                                    <i class="fas fa-check-circle fa-5x text-success"></i>
                                @break

                                @case('cancelled')
                                    <i class="fas fa-times-circle fa-5x text-danger"></i>
                                @break

                                @default
                                    <i class="fas fa-check-circle fa-5x text-success"></i>
                            @endswitch
                        </div>

                        @switch($reservation->status)
                            @case('pending')
                                <h3 class="h3-md mb-3">{{ __('Your Booking Has Been Received') }}</h3>
                                <p class="alert alert-info">
                                    {{ __('Your booking is pending payment. Complete your payment online to confirm your appointment.') }}
                                </p>
                            @break

                            @case('confirmed')
                                <h3 class="h3-md mb-3">{{ __('Your Booking Has Been Confirmed') }}</h3>
                                <p class="mb-4">
                                    {{ __('We\'ve confirmed your booking request. A confirmation email with the details of your appointment has been sent to your email address.') }}
                                </p>
                            @break

                            @case('completed')
                                <h3 class="h3-md mb-3">{{ __('Your Service Has Been Completed') }}</h3>
                                <p class="mb-4">
                                    {{ __('Thank you for choosing our services. We hope you enjoyed your experience with us.') }}
                                </p>
                            @break

                            @case('cancelled')
                                <h3 class="h3-md mb-3">{{ __('Your Booking Has Been Cancelled') }}</h3>
                                <p class="mb-4">
                                    {{ __('Your booking has been cancelled. If you have any questions regarding the cancellation, please contact us.') }}
                                </p>
                            @break

                            @default
                                <h3 class="h3-md mb-3">{{ __('Your Booking Has Been Confirmed') }}</h3>
                                <p class="mb-4">
                                    {{ __('We\'ve received your booking request. A confirmation email with the details of your appointment has been sent to your email address.') }}
                                </p>
                        @endswitch
                        @if ($reservation->status == 'confirmed' || $reservation->status == 'completed')
                            <div class="booking-reference mb-5">
                                <h4 class="text-muted">{{ $reservation->invoice->invoice_number }}</h4>
                            </div>
                        @endif

                        <!-- Booking Details -->
                        <div class="booking-details mb-5 text-start">

                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="h5-md text-white mb-0">{{ __('Customer Information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $customerDetail = json_decode($reservation->customer_detail);
                                            $name = 'name_' . app()->getLocale();
                                        @endphp
                                        <div class="d-flex col-12 col-sm-6 align-items-end">
                                            <h6 class="fw-bold lh-sm mb-2">{{ __('Name:') }}</h6>
                                            <p class="ms-3 lh-sm mb-2">{{ $customerDetail->$name }}</p>
                                        </div>
                                        <div class="d-flex col-12 col-sm-6 align-items-end">
                                            <h6 class="fw-bold lh-sm mb-2">{{ __('Email:') }}</h6>
                                            <p class="ms-3 lh-sm mb-2">{{ $customerDetail->email }}</p>
                                        </div>
                                        <div class="d-flex col-12 col-sm-6 align-items-end">
                                            <h6 class="fw-bold lh-sm mb-2">{{ __('Phone:') }}</h6>
                                            <p class="ms-3 lh-sm mb-2">{{ $customerDetail->phone }}</p>
                                        </div>
                                        @if ($reservation->notes)
                                            <hr>
                                            <div class="d-flex col-12 col-sm-6 align-items-end">
                                                <h6 class="fw-bold lh-sm mb-2">{{ __('Additional Notes:') }}</h6>
                                                <p class="ms-3 lh-sm mb-2">{{ $reservation->notes }}</p>
                                            </div>
                                        @endif
                                        @if ($reservation->address)
                                            <div class="d-flex align-items-start mt-3">
                                                <h6 class="fw-bold lh-sm mb-2">{{ __('Address:') }}</h6>
                                                <p class="ms-3 lh-sm mb-2">{{ $reservation->address }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="text-white mb-0">{{ __('Services') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div>
                                        @foreach ($reservation->items as $item)
                                            <div class="service-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h5 class="mb-1 text-capitalize font-weight-bolder">
                                                            {{ $item->name }}</h5>
                                                        @php
                                                            $staffDetail = json_decode($item->staff_detail);
                                                        @endphp
                                                        <small class="text-muted">{{ __('Staff:') }}
                                                            {{ $staffDetail->$name }}</small> <br>
                                                        <small
                                                            class="text-muted"><strong>{{ __('Service Type:') }}</strong>
                                                            {{ ucfirst($item->service_location) }}
                                                            {{ __('Service') }}</small> <br>
                                                        <small class="text-muted"><strong>{{ __('Date:') }}</strong>
                                                            {{ date('l, F j, Y', strtotime($item->appointment_date)) }}
                                                            <span
                                                                class="mx-2">{{ date('g:i A', strtotime($item->start_time)) }}
                                                                - {{ date('g:i A', strtotime($item->end_time)) }}</span>
                                                        </small>
                                                    </div>
                                                    <div class="text-end">
                                                        <p class="mb-0">{{ number_format($item->price, 2) }}
                                                            {{ __('SAR') }} x
                                                            {{ $item->quantity }}</p>
                                                        <p class="fw-bold mb-0">{{ number_format($item->total, 2) }}
                                                            {{ __('SAR') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="summary-totals mt-4 pt-3 border-top">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('Subtotal:') }}</span>
                                            <span>{{ number_format($reservation->subtotal, 2) }}
                                                {{ __('SAR') }}</span>
                                        </div>
                                        @if ($reservation->discount_amount > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>{{ __('Discount:') }}</span>
                                                <span>{{ number_format($reservation->discount_amount, 2) }}
                                                    {{ __('SAR') }}</span>
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('VAT (15%):') }}</span>
                                            <span>{{ number_format($reservation->vat_amount, 2) }}
                                                {{ __('SAR') }}</span>
                                        </div>
                                        @if ($reservation->other_taxes_amount > 0)
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>{{ __('Other Taxes:') }}</span>
                                                <span>{{ number_format($reservation->other_taxes_amount, 2) }}
                                                    {{ __('SAR') }}</span>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>{{ __('Total:') }}</span>
                                            <span>{{ number_format($reservation->total_price, 2) }}
                                                {{ __('SAR') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="h5-md text-white mb-0">{{ __('Payment Information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <strong>{{ __('Payment Method:') }}</strong>
                                        <span class="ms-3">{{ ucfirst($reservation->payment_method) }}</span>
                                    </p>
                                    <p><strong>{{ __('Payment Status:') }}</strong>
                                        @if ($reservation->status == 'pending' && $reservation->total_amount_paid <= 0)
                                            <span class="badge bg-warning ms-3">{{ __('Awaiting Payment') }}</span>
                                        @else
                                            <span class="ms-3"> {{ ucfirst($reservation->status) }}</span>
                                        @endif
                                    </p>
                                    @if ($reservation->total_paid_cash > 0)
                                        <p>
                                            <strong>{{ __('Paid (Cash):') }}</strong>
                                            <span class="ms-3"> {{ number_format($reservation->total_paid_cash, 2) }}
                                                {{ __('SAR') }} </span>
                                        </p>
                                    @endif
                                    @if ($reservation->total_paid_online > 0)
                                        <p>
                                            <strong>{{ __('Paid (Online):') }}</strong>
                                            <span class="ms-3"> {{ number_format($reservation->total_paid_online, 2) }}
                                                {{ __('SAR') }} </span>
                                        </p>
                                    @endif
                                    @if ($reservation->status == 'pending' && $reservation->total_amount_paid <= 0)
                                        <p><strong>{{ __('Amount Due:') }}</strong>
                                            <span
                                                class="text-danger fw-bold ms-3">{{ number_format($reservation->total_price, 2) }}
                                                {{ __('SAR') }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if ($reservation->status == 'pending' && $reservation->total_amount_paid <= 0)
                                @if ($has_overlap)
                                    <div class="alert alert-warning">
                                        {{ $overlap_message }}
                                        <div class="mt-5 d-flex justify-content-between">
                                            <button class="btn btn-sm p-3 rounded btn-danger"
                                                onclick="openCancelModal()">{{ __('Cancel Booking') }}</button>
                                            <button class="btn btn-sm p-3 rounded btn-dark me-2"
                                                onclick="rescheduleAppointment()">{{ __('Reschedule Appointment') }}</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-dark text-white">
                                            <h5 class="h5-md text-white mb-0">{{ __('Pay Now') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <hr>
                                            <div class="payment-method-selector mb-3">
                                                <h6 class="mb-3">{{ __('Select Payment Method') }}</h6>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input payment-method-radio" type="radio"
                                                        name="payment_method" id="visa_mastercard" value="visa_mastercard"
                                                        checked>
                                                    <label class="form-check-label ms-3" for="visa_mastercard">
                                                        <img src="{{ asset('assets/images/visa.svg') }}" alt="visa"
                                                            style="height: 20px;" class="me-1"> <img
                                                            src="{{ asset('assets/images/master.svg') }}" alt="master"
                                                            style="height: 20px;" class="me-1">
                                                        {{ __('Visa/Mastercard') }}
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input payment-method-radio" type="radio"
                                                        name="payment_method" id="mada" value="mada">
                                                    <label class="form-check-label ms-3" for="mada">
                                                        <img src="{{ asset('assets/images/mada.svg') }}" alt="Mada"
                                                            style="height: 40px;" class="me-1"> {{ __('Mada') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex justify-content-end">
                                                <button id="payNowBtn"
                                                    class="btn btn--gold hover-tra-white">{{ __('Pay Now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Next Steps -->
                        <div class="next-steps p-4 bg-light rounded mb-5">
                            <h4 class="h4-md mb-3">{{ __('What\'s Next?') }}</h4>

                            @switch($reservation->status)
                                @case('pending')
                                    <p>{{ __('Our team will review your booking and contact you to confirm your appointment. Please make sure your contact information is accurate.') }}
                                    </p>
                                @break

                                @case('confirmed')
                                    @php
                                        $salonServices = 0;
                                        $homeServices = 0;
                                        foreach ($reservation->items as $item) {
                                            if ($item->service_location == 'salon') {
                                                $salonServices++;
                                            } elseif ($item->service_location == 'home') {
                                                $homeServices++;
                                            }
                                        }
                                    @endphp

                                    @if ($homeServices > 0 && $salonServices > 0)
                                        <p>{{ __('Your appointment is confirmed! For salon services, please arrive 10 minutes before your scheduled time. For home services, our staff will contact you to confirm the location details - please stay reachable.') }}
                                        </p>
                                    @elseif($homeServices > 0)
                                        <p>{{ __('Your appointment is confirmed! Our staff will contact you to confirm the location details for your home service. Please make sure to stay reachable on your registered contact number.') }}
                                        </p>
                                    @else
                                        <p>{{ __('Your appointment is confirmed! Please arrive 10 minutes before your scheduled time. If you need to prepare anything specific for your service, our team will contact you.') }}
                                        </p>
                                    @endif
                                @break

                                @case('completed')
                                    <p>{{ __('We hope you enjoyed your service! Your feedback is important to us. Consider leaving a review of your experience to help us improve.') }}
                                    </p>
                                @break

                                @case('cancelled')
                                    <p>{{ __('Your booking has been cancelled. If you\'d like to reschedule, please feel free to make a new booking at your convenience.') }}
                                    </p>
                                @break

                                @default
                                    <p>{{ __('Our team will review your booking and contact you to confirm your appointment. Please make sure your contact information is accurate.') }}
                                    </p>
                            @endswitch

                            <p class="mb-0">
                                {{ __('If you need to modify or cancel your booking, please contact us at') }} <a
                                    href="tel:{{ $default_phone_number }}">{{ $default_phone_number }}</a>
                                {{ __('or') }} <a href="mailto:{{ $default_email }}">{{ $default_email }}</a>.</p>
                        </div>

                        <!-- Return to Home Button -->
                        <div class="mt-4">
                            @if ($reservation->status == 'confirmed' || $reservation->status == 'completed')
                                <a href="{{ route('invoices.print', $reservation->invoice->id) }}" target="_blank"
                                    class="btn my-2 btn--gold hover--gold me-2">{{ __('Print Invoice') }}</a>
                            @endif
                            <a href="{{ route('customer.bookings') }}"
                                class="btn my-2 btn--black hover--black">{{ __('View All Bookings') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONFIRMATION SECTION -->


    <!-- Cancel Booking Modal -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelBookingModalLabel">{{ __('Cancel Booking') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to cancel this booking?') }}</p>
                    <div class="mb-3">
                        <label for="cancelNote"
                            class="form-label">{{ __('Optional Note (Reason for cancellation)') }}</label>
                        <textarea class="form-control" id="cancelNote" rows="3"
                            placeholder="{{ __('Enter reason for cancellation (optional)') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-danger"
                        onclick="cancelBooking()">{{ __('Confirm Cancellation') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection

@section('styles')
    <style>
        .confirmation-panel {
            background: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        .confirmation-icon {
            color: #28a745;
        }

        .booking-reference {
            margin: 30px 0;
        }

        .reference-number {
            font-family: monospace;
            letter-spacing: 1px;
        }

        .booking-details .card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
        }

        .booking-details .card-header {
            background-color: #212529;
            color: white;
            border: none;
        }

        .service-item {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .service-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('scripts')
    @include('components.notification')
    <script>
        $(document).ready(function() {
            // Handle Pay Now button click
            $('#payNowBtn').on('click', function() {
                // Show loading state
                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> {{ __('Processing...') }}');

                // Call HyperPay checkout API
                $.ajax({
                    url: '{{ route('hyperpay.checkout') }}',
                    method: 'POST',
                    data: JSON.stringify({
                        reservation_id: {{ $reservation->id }},
                        amount: {{ $reservation->total_price }},
                        payment_method: $('.payment-method-radio:checked').val() ||
                            'visa_mastercard'
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(paymentResponse) {
                        if (paymentResponse.success) {
                            // Redirect to the payment form page
                            window.location.href = '{{ route('hyperpay.form') }}';
                        } else {
                            $btn.prop('disabled', false).html($btn.attr('id') === 'payNowBtn' ?
                                '{{ __('Pay Now') }}' : '{{ __('Pay Online') }}');
                        }
                    },
                    error: function(xhr) {
                        displayNotification('error',
                            '{{ __('Payment initialization failed: ') }}' + (
                                xhr.responseJSON.message || '{{ __('Unknown error') }}'
                            ));
                        $btn.prop('disabled', false).html($btn.attr('id') === 'payNowBtn' ?
                            '{{ __('Pay Now') }}' : '{{ __('Pay Online') }}');
                        window.location.href = xhr.responseJSON.redirect;

                    }
                });
            });

        });
    </script>
    <script>
        function openCancelModal() {
            var modal = new bootstrap.Modal(document.getElementById('cancelBookingModal'));
            modal.show();
        }

        function rescheduleAppointment() {
            // Show loading state on the button
            const rescheduleButton = document.querySelector('button[onclick="rescheduleAppointment()"]');
            const originalText = rescheduleButton.innerHTML;
            rescheduleButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Processing...') }}';
            rescheduleButton.disabled = true;

            // Get the current cart items from localStorage
            let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

            // Get the reservation items from the current page
            const reservationItems = @json($reservation->items);

            // Create an array of promises for API calls
            const apiPromises = reservationItems.map(item => {
                return fetch(`/api/services/${item.product_and_service_id}`)
                    .then(response => response.json())
                    .then(serviceData => {
                        const cartItem = {
                            id: serviceData.id,
                            unique_id: `${serviceData.id}-${item.service_location}`,
                            name: serviceData.name,
                            price: item.service_location === 'home' ? serviceData.price_home : serviceData
                                .price,
                            price_home: serviceData.price_home,
                            image: serviceData.image,
                            can_be_done_at_home: serviceData.can_be_done_at_home,
                            category_name: serviceData.category.name,
                            category_id: serviceData.category_id,
                            quantity: item.quantity,
                            service_type: item.service_location,
                            duration_minutes: serviceData.duration_minutes,
                            is_reschedule: true,
                            pending_reservation_id: {{ $reservation->id }}
                        };
                        return cartItem;
                    });
            });

            // Wait for all API calls to complete
            Promise.all(apiPromises)
                .then(newCartItems => {
                    // Remove any existing items with the same IDs
                    const newItemUniqueIds = newCartItems.map(item => item.unique_id);
                    cartItems = cartItems.filter(item => !newItemUniqueIds.includes(item.unique_id));

                    // Add all new items to cart
                    cartItems = [...cartItems, ...newCartItems];

                    // Save updated cart items back to localStorage
                    localStorage.setItem('cart', JSON.stringify(cartItems));

                    // Redirect to cart page
                    window.location.href = '{{ route('cart') }}';
                })
                .catch(error => {
                    console.error('Error fetching service details:', error);
                    displayNotification('error', '{{ __('Failed to load service details. Please try again.') }}');
                    // Reset button state on error
                    rescheduleButton.innerHTML = originalText;
                    rescheduleButton.disabled = false;
                });
        }

        function cancelBooking() {
            const note = document.getElementById('cancelNote').value;
            const reservationId = '{{ $reservation->id }}';

            // Show loading state
            const cancelButton = document.querySelector('#cancelBookingModal .btn-danger');
            const originalText = cancelButton.innerHTML;
            cancelButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Cancelling...') }}';
            cancelButton.disabled = true;

            // Make API call
            fetch(`{{ route('bookings.cancel', $reservation->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        note: note
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('cancelBookingModal')).hide();

                        // Show success message and redirect
                        displayNotification('success', '{{ __('Booking cancelled successfully') }}');
                        window.location.href = '{{ route('customer.bookings') }}'; // Redirect to bookings page
                    } else {
                        throw new Error(data.message || '{{ __('Failed to cancel booking') }}');
                    }
                })
                .catch(error => {
                    displayNotification('error', error.message);
                    // Reset button state
                    cancelButton.innerHTML = originalText;
                    cancelButton.disabled = false;
                });
        }
    </script>
@endsection
