@extends('layouts.app')

@section('title', __('My Profile - mcs.sa Salon'))

@section('content')
    <div class="container py-8">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-10">
                <!-- Page Title -->
                <div class="text-center mb-5">
                    <h2 class="h2-md">{{ __('My Profile') }}</h2>
                    <p class="p-lg">{{ __('Manage your account information') }}</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info mb-4" role="alert">
                        {{ session('info') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!$user->hasVerifiedEmail())
                    <div class="alert alert-warning mb-4" role="alert">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small id="verification-message">
                                    {{ __('Your email address has not been verified. Please verify your email to ensure full access to all features.') }}
                                </small>
                            </div>
                            <button id="resend-verification-btn" class="btn btn-sm btn--black rounded px-2 hover--black">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h4-md mb-4">{{ __('Profile Information') }}</h3>

                                <form method="POST" action="{{ route('user.profile.update') }}"
                                    id="customer-profile-form">
                                    @csrf
                                    <div class="row">

                                        @if ($user->hasRole('customer') && $customer)
                                            <div class="mb-3 col-sm-6">
                                                <label for="name_en" class="form-label">{{ __('Name (English)') }}</label>
                                                <input id="name_en" type="text"
                                                    class="form-control @error('name_en') is-invalid @enderror"
                                                    name="name_en" value="{{ old('name_en', $customer->name_en) }}"
                                                    required>
                                                @error('name_en')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-6">
                                                <label for="name_ar" class="form-label">{{ __('Name (Arabic)') }}</label>
                                                <input id="name_ar" type="text"
                                                    class="form-control @error('name_ar') is-invalid @enderror"
                                                    name="name_ar" value="{{ old('name_ar', $customer->name_ar) }}"
                                                    required>
                                                @error('name_ar')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="mb-3 col-sm-6">
                                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name', $user->name) }}" required autocomplete="name">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="mb-3 col-sm-6">
                                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email', $user->email) }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ __('If you change your email, you will need to verify it again.') }}
                                            </small>
                                        </div>

                                        @if ($user->hasRole('customer') && $customer)
                                            <div class="mb-3 col-sm-6">
                                                @include('components.phone-input', [
                                                    'fieldName' => 'phone_number',
                                                    'label' => 'Phone Number',
                                                    'required' => true,
                                                    'defaultValue' => old('phone_number', $customer->phone_number),
                                                ])

                                                @error('phone_number')
                                                    <span class="invalid-feedback d-block mt-1">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                                <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <div class="col-12">
                                                    @include('components.location-map', [
                                                        'id' => 'profile',
                                                        'buttonClass' => 'btn--gold',
                                                        'addressFieldId' => 'address',
                                                        'default_lat' => $customer->latitude ?? null,
                                                        'default_lng' => $customer->longitude ?? null,
                                                    ])
                                                </div>
                                            </div>

                                        @endif
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn--black hover--black">
                                            {{ __('Update Profile') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="h4-md mb-4">{{ __('Update Password') }}</h3>

                                <form method="POST" action="{{ route('user.password.update') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="current_password"
                                            class="form-label">{{ __('Current Password') }}</label>
                                        <input id="current_password" type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            name="current_password" required>
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">{{ __('New Password') }}</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password-confirm"
                                            class="form-label">{{ __('Confirm New Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn--black hover--black">
                                            {{ __('Update Password') }}
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p>{{ __('Or') }}</p>
                                        <a href="{{ route('password.request', ['redirect' => 'user.profile']) }}"
                                            class="btn btn-link">
                                            {{ __('Reset Password via Email') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes check-pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.2);
                }

                100% {
                    transform: scale(1);
                }
            }

            .check-animation {
                display: inline-block;
                animation: check-pulse 1s ease-in-out;
                animation-iteration-count: 2;
            }
        </style>
    @endpush

    @push('scripts')
        @include('components.notification')
        <script>
            // Here you could add JavaScript for handling address/map features
            // For example, you might want to add Google Maps integration for selecting an address

            // Resend verification email with AJAX
            $(document).ready(function() {
                $('#resend-verification-btn').on('click', function() {
                    var $btn = $(this);

                    // Disable button during request
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('resendVerificationEmail', ['redirect' => 'user.profile']) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Display success notification
                            displayNotification('success',
                                '{{ __('Verification email has been sent successfully!') }}',
                                'topRight', 5);
                            $('#verification-message').text(
                                '{{ __('Open your email and click the verification link to verify your email address.') }}'
                            );
                            $btn.addClass('d-none');

                            // Keep button disabled
                            $btn.prop('disabled', true);
                        },
                        error: function(xhr) {
                            // Enable button again
                            $btn.prop('disabled', false);

                            // Display error notification
                            let errorMessage = 'Failed to send verification email.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            displayNotification('error', errorMessage, 'topRight', 5);
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const profileForm = document.getElementById('customer-profile-form');

                if (profileForm) {
                    profileForm.addEventListener('submit', function(e) {
                        // Validate phone number before submission
                        if (!window.validatePhoneInputForSubmit('phone_number')) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
