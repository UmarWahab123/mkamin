@extends('layouts.app')

@section('title', __('Payment - mcs.sa Salon'))

@section('content')
<div class="container py-5">
    <div class="row pt-8 justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h3 class="mb-0">{{ __('Complete Your Payment') }}</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p>{{ __('Please enter your payment details to complete the booking.') }}</p>
                    </div>

                    <!-- HyperPay Payment Form -->
                    <div id="payment-form">
                        <form action="{{ route('hyperpay.response') }}" class="paymentWidgets" data-brands="{{ $payment_method === 'mada' ? 'MADA' : 'MASTER VISA' }}"></form>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">{{ __('Cancel and return') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var wpwlOptions = {
        locale: "{{ app()->getLocale() }}"
    }
</script>
<!-- HyperPay Script -->
<script
    src="{{ config('services.hyperpay.base_url') }}paymentWidgets.js?checkoutId={{ $checkoutId }}"
    @if($integrity) integrity="{{ $integrity }}" crossorigin="anonymous" @endif
></script>
@endsection
