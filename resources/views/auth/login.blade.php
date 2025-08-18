@extends('layouts.app')

@section('title', __('Login - mcs.sa Salon'))

@section('content')
    <div class="container py-8">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">{{ __('Login') }}</h2>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn--black hover--black">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">{{ __("Don't have an account?") }}</p>
                            <a href="{{ route('register') }}" class="btn btn--tra-black hover--black mt-2">
                                {{ __('Register Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get the form and remember me checkbox
                const loginForm = document.getElementById('loginForm');
                const rememberMeCheckbox = document.getElementById('remember');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');

                // Function to get remembered credentials
                function getRememberMe() {
                    const rememberedEmail = localStorage.getItem('rememberedEmail');
                    const rememberedPassword = localStorage.getItem('rememberedPassword');

                    if (rememberedEmail && rememberedPassword) {
                        emailInput.value = rememberedEmail;
                        passwordInput.value = rememberedPassword;
                        rememberMeCheckbox.checked = true;
                    }
                }

                // Function to set remembered credentials
                function setRememberMe() {
                    if (rememberMeCheckbox.checked) {
                        localStorage.setItem('rememberedEmail', emailInput.value);
                        localStorage.setItem('rememberedPassword', passwordInput.value);
                    } else {
                        localStorage.removeItem('rememberedEmail');
                        localStorage.removeItem('rememberedPassword');
                    }
                }

                // Handle form submission
                loginForm.addEventListener('submit', function() {
                    setRememberMe();
                });

                // Load remembered credentials when page loads
                getRememberMe();
            });
        </script>
    @endpush
@endsection
