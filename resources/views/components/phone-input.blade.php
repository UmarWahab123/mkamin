@props(['fieldName' => 'phone_number', 'label' => 'Phone Number', 'required' => true, 'defaultValue' => ''])

<div class="phone-input-component">
    <!-- Label -->
    <label for="phone_input_field" class="form-label">
        {{ __($label) }}{{ $required ? '*' : '' }}
    </label>

    <!-- Phone Input Field (this will be replaced by the intl-tel-input) -->
    <input type="tel" id="phone_input_field" class="form-control">

    <!-- Error message div -->
    <div id="phone-input-error" class="text-danger mt-1" style="display: none;"></div>

    <!-- Hidden field that will contain the validated phone number -->
    <input type="hidden" name="{{ $fieldName }}" id="{{ $fieldName }}" value="{{ old($fieldName, $attributes->get($fieldName) ?? $defaultValue) }}">
</div>

@once
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }
        .iti__flag {
            background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags.png");
        }
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags@2x.png");
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the component
        const phoneInputField = document.querySelector("#phone_input_field");
        if (!phoneInputField) return;

        // Store the phone input instance for later use
        window.phoneInputComponent = window.phoneInputComponent || {};

        // Initialize intl-tel-input
        const phoneInputInstance = window.intlTelInput(phoneInputField, {
            initialCountry: "auto",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js",
            geoIpLookup: function(callback) {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("sa")); // Default to Saudi Arabia if detection fails
            },
        });

        // Store the instance for external access
        window.phoneInputComponent["{{ $fieldName }}"] = phoneInputInstance;

        // Check for existing value in the hidden field and set it
        const hiddenField = document.getElementById("{{ $fieldName }}");
        if (hiddenField && hiddenField.value) {
            setTimeout(() => {
                const phoneValue = hiddenField.value;
                if (phoneValue.startsWith('+')) {
                    phoneInputInstance.setNumber(phoneValue);
                }
                // Validate to update UI elements
                validatePhoneNumber("{{ $fieldName }}");
            }, 300); // Short delay to ensure component is fully initialized
        }

        // Validate on blur
        phoneInputField.addEventListener('blur', function() {
            validatePhoneNumber("{{ $fieldName }}");
        });

        // Expose the validation function globally
        window.validatePhoneNumber = function(fieldName) {
            const phoneInstance = window.phoneInputComponent[fieldName];
            if (!phoneInstance) return false;

            const phoneError = document.getElementById('phone-input-error');
            const phoneInput_value = document.getElementById('phone_input_field').value.trim();

            // First check if the field is empty
            if (!phoneInput_value) {
                phoneError.textContent = '{{ __("Phone number is required") }}';
                phoneError.style.display = 'block';
                return false;
            }

            // Check for invalid characters (allowing only digits, +, spaces, parentheses, and hyphens)
            const validCharsPattern = /^[0-9+\s()\-]+$/;
            if (!validCharsPattern.test(phoneInput_value)) {
                phoneError.textContent = '{{ __("Phone number should contain only digits and valid formatting characters") }}';
                phoneError.style.display = 'block';
                return false;
            }

            // Get full number for validation
            const phoneNumber = phoneInstance.getNumber();

            // Standard validation using library
            const isValid = phoneInstance.isValidNumber();

            // Check if it's a Saudi number (starting with +966) with correct length
            const saudiNumberPattern = /^\+966\s?\d/;
            const saudiNumber = saudiNumberPattern.test(phoneNumber) &&
                               phoneNumber.replace(/\D/g, '').length === 12;

            if (isValid || saudiNumber) {
                phoneError.style.display = 'none';
                // Set the value in the hidden field
                document.getElementById(fieldName).value = phoneNumber;
                return true;
            } else {
                phoneError.textContent = '{{ __("Please enter a valid phone number") }}';
                phoneError.style.display = 'block';
                return false;
            }
        };

        // Add form submit validation
        window.validatePhoneInputForSubmit = function(fieldName) {
            if (validatePhoneNumber(fieldName)) {
                return true;
            } else {
                $('#phone_input_field').focus();
                return false;
            }
        };
    });
</script>
@endpush
