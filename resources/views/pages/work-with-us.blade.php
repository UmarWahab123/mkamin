@extends('layouts.app')

@section('title', __('Work With Us - mcs.sa Salon'))

@section('content')

@section('styles')
    <style>
        .remove-day {
            cursor: pointer;
        }

        .remove-product {
            cursor: pointer;
        }

        .form-card {
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            background: white;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-control {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .form-control:hover {
            border-color: #b8b8b8;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #b8b8b8;
            border-color: #b8b8b8;

        }

        .btn--gold {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn--gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn--gold:active {
            transform: translateY(0);
        }

        .btn--gold::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn--gold:hover::after {
            width: 300px;
            height: 300px;
        }
    </style>
@endsection

<section class="pt-8 pb-8 contacts-section division">
    <div class="container mt-3">
        <div class="row justify-content-center">

            <!-- CONTACT FORM -->
            <div class="col-md-10 col-lg-8">
                <div class="form-card">

                    <!-- Section ID -->
                    <span class="section-id text-center">{{ __('Apply Now') }}</span>

                    <!-- Title -->
                    <h3 class="h3-md mb-4 text-center">{{ __('Join Our Team Today') }}</h3>

                    <!-- Form -->
                    <form name="staffApplicationForm" class="row application-form" id="staffApplicationForm">
                        @csrf

                        <!-- Name English -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}*</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>


                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}*</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            @include('components.phone-input', [
                                'fieldName' => 'phone_number',
                                'label' => 'Phone Number',
                                'required' => true,
                            ])
                        </div>

                        <!-- Position -->
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">{{ __('Position') }}*</label>
                            <input type="text" id="position" name="position" class="form-control">
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}*</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}*</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">{{ __('Address') }}</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>

                        <!-- Location Map Component -->
                        <div class="col-md-12">
                            @include('components.location-map', [
                                'id' => 'staff',
                                'buttonClass' => 'btn--gold',
                                'addressFieldId' => 'address',
                            ])
                        </div>

                        <!-- Resume Upload -->
                        <div class="col-md-12 mb-3">
                            <label for="resume" class="form-label">{{ __('Resume') }}</label>
                            <input type="file" class="form-control" id="resume" name="resume"
                                accept=".pdf,.doc,.docx">
                            <div class="form-text">{{ __('Upload your resume (PDF, DOC, DOCX formats, max 10MB)') }}
                            </div>
                        </div>

                        <!-- Images Upload -->
                        <div class="col-md-12 mb-3">
                            <label for="images" class="form-label">{{ __('Images') }}</label>
                            <input type="file" class="form-control" id="images" name="images[]" accept="image/*"
                                multiple>
                            <div class="form-text">{{ __('Upload your photos (max 5MB each)') }}</div>
                            <div id="image-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>

                        <script>
                            // Track selected files
                            let selectedFiles = [];

                            document.getElementById('images').addEventListener('change', function(event) {
                                const preview = document.getElementById('image-preview');

                                // Don't clear the preview here anymore
                                // Store the newly selected files
                                if (this.files && this.files.length > 0) {
                                    // Convert FileList to array and append to our tracked files
                                    const newFiles = Array.from(this.files);
                                    selectedFiles = [...selectedFiles, ...newFiles]; // Merge with existing selected files

                                    // Create previews for all selected files
                                    renderImagePreviews();
                                }
                            });

                            // Function to render image previews based on selectedFiles array
                            function renderImagePreviews() {
                                const preview = document.getElementById('image-preview');
                                preview.innerHTML = '';

                                selectedFiles.forEach((file, index) => {
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        const div = document.createElement('div');
                                        div.style.width = '100px';
                                        div.style.height = '100px';
                                        div.style.overflow = 'hidden';
                                        div.style.position = 'relative';
                                        div.style.borderRadius = '4px';
                                        div.style.margin = '5px';
                                        div.style.border = '1px solid #eee';
                                        div.dataset.index = index;

                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.style.width = '100%';
                                        img.style.height = '100%';
                                        img.style.objectFit = 'cover';

                                        // Add remove button
                                        const removeBtn = document.createElement('div');
                                        removeBtn.innerHTML = '&times;';
                                        removeBtn.style.position = 'absolute';
                                        removeBtn.style.top = '2px';
                                        removeBtn.style.right = '2px';
                                        removeBtn.style.backgroundColor = 'rgba(0,0,0,0.5)';
                                        removeBtn.style.color = 'white';
                                        removeBtn.style.width = '20px';
                                        removeBtn.style.height = '20px';
                                        removeBtn.style.borderRadius = '50%';
                                        removeBtn.style.textAlign = 'center';
                                        removeBtn.style.lineHeight = '18px';
                                        removeBtn.style.cursor = 'pointer';
                                        removeBtn.style.zIndex = '10';

                                        removeBtn.addEventListener('click', function() {
                                            // Remove the file from our tracked files array
                                            selectedFiles.splice(index, 1);
                                            // Re-render the previews
                                            renderImagePreviews();
                                        });

                                        div.appendChild(img);
                                        div.appendChild(removeBtn);
                                        preview.appendChild(div);
                                    };

                                    reader.readAsDataURL(file);
                                });
                            }
                        </script>

                        {{-- <div class="col-md-12 mb-3">
                            <select class="form-select" name="staff_position_id" required>
                                <option selected disabled value="">{{ __('Select Position') }}*</option>
                                @foreach ($staffPositions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="text-start default_start_time">{{ __('Default Start Time') }}</label>
                            <input type="time" id="default_start_time" name="default_start_time"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="text-start default_end_time">{{ __('Default End Time') }}</label>
                            <input type="time" id="default_end_time" name="default_end_time" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="text-start default_closed_day">{{ __('Default Closed Day') }}</label>
                            <select class="form-select" id="default_closed_day" name="default_closed_day">
                                <option value="">{{ __('Select Closed Day') }}</option>
                                <option value="sunday">{{ __('Sunday') }}</option>
                                <option value="monday">{{ __('Monday') }}</option>
                                <option value="tuesday">{{ __('Tuesday') }}</option>
                                <option value="wednesday">{{ __('Wednesday') }}</option>
                                <option value="thursday">{{ __('Thursday') }}</option>
                                <option value="friday">{{ __('Friday') }}</option>
                                <option value="saturday">{{ __('Saturday') }}</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="home_visit_day_select">{{ __('Default Home Visit Days') }}</label>
                            <select class="form-select mb-2" id="home_visit_day_select">
                                <option selected disabled value="">{{ __('Select Day') }}</option>
                                <option value="monday">{{ __('Monday') }}</option>
                                <option value="tuesday">{{ __('Tuesday') }}</option>
                                <option value="wednesday">{{ __('Wednesday') }}</option>
                                <option value="thursday">{{ __('Thursday') }}</option>
                                <option value="friday">{{ __('Friday') }}</option>
                                <option value="saturday">{{ __('Saturday') }}</option>
                                <option value="sunday">{{ __('Sunday') }}</option>
                            </select>
                            <div id="selected_days_container" class="d-flex flex-wrap mt-2"></div>
                            <div id="hidden_inputs_container"></div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="product_service_select">{{ __('Products and Services') }}</label>
                            <select class="form-select mb-2" id="product_service_select">
                                <option selected disabled value="">{{ __('Select Product/Service') }}</option>
                                @foreach ($productAndServices as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div id="selected_products_container" class="d-flex flex-wrap mt-2"></div>
                            <div id="hidden_products_container"></div>
                        </div> --}}

                        <!-- Terms and Conditions Checkbox -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms_accepted"
                                    name="terms_accepted">
                                <label class="form-check-label" for="terms_accepted">
                                    {{ __('I accept the') }} <a href="{{ route('terms') }}"
                                        class="text-decoration-underline"
                                        target="_blank">{{ __('Terms and Conditions') }}</a>*
                                </label>
                            </div>
                        </div>

                        <!-- Form Button -->
                        <div class="col-md-12 mt-4 text-center">
                            <button type="submit"
                                class="btn btn-md btn--gold btn-hover-gold">{{ __('Submit Application') }}</button>
                        </div>

                        <!-- Form Message (hidden, will be shown via JS) -->
                        <div class="col-md-12 application-form-msg text-center d-none">
                            <div class="sending-msg"><span class="loading"></span></div>
                        </div>

                    </form>

                </div>
            </div>
            <!-- END CONTACT FORM -->

        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>

@endsection

@section('scripts')
@include('components.notification')

<script>
    $(document).ready(function() {
        // Home visit days selector
        $('#home_visit_day_select').change(function() {
            var selectedDay = $(this).val();
            var selectedDayText = $(this).find("option:selected").text();

            if (selectedDay) {
                // Check if day already selected
                if ($('#day_' + selectedDay).length === 0) {
                    // Add visual tag
                    $('#selected_days_container').append(
                        '<div id="day_' + selectedDay +
                        '" class="badge bg-primary me-2 mb-2 p-2">' +
                        selectedDayText +
                        '<span class="ms-2 remove-day" data-day="' + selectedDay +
                        '">&times;</span>' +
                        '</div>'
                    );

                    // Add hidden input for form submission
                    $('#hidden_inputs_container').append(
                        '<input type="hidden" name="default_home_visit_days[]" value="' +
                        selectedDay + '" id="input_' + selectedDay + '">'
                    );
                }

                // Reset select to default
                $(this).val('');
            }
        });

        // Handle removal of days with delegated event handler
        $('#selected_days_container').on('click', '.remove-day', function() {
            var day = $(this).data('day');
            $('#day_' + day).remove();
            $('#input_' + day).remove();
        });

        // Products and Services selector
        $('#product_service_select').change(function() {
            var selectedProduct = $(this).val();
            var selectedProductText = $(this).find("option:selected").text();

            if (selectedProduct) {
                // Check if product already selected
                if ($('#product_' + selectedProduct).length === 0) {
                    // Add visual tag
                    $('#selected_products_container').append(
                        '<div id="product_' + selectedProduct +
                        '" class="badge bg-success me-2 mb-2 p-2">' +
                        selectedProductText +
                        '<span class="ms-2 remove-product" data-product="' + selectedProduct +
                        '">&times;</span>' +
                        '</div>'
                    );

                    // Add hidden input for form submission
                    $('#hidden_products_container').append(
                        '<input type="hidden" name="product_and_services[]" value="' +
                        selectedProduct + '" id="input_product_' + selectedProduct + '">'
                    );
                }

                // Reset select to default
                $(this).val('');
            }
        });

        // Handle removal of products with delegated event handler
        $('#selected_products_container').on('click', '.remove-product', function() {
            var product = $(this).data('product');
            $('#product_' + product).remove();
            $('#input_product_' + product).remove();
        });

        // Form submission handling
        $('form[name="staffApplicationForm"]').on('submit', function(e) {
            e.preventDefault();

            // Validate phone number using the component's validation function
            if (!window.validatePhoneInputForSubmit('phone_number')) {
                return false;
            }

            // Check if terms and conditions are accepted
            if (!$('#terms_accepted').is(':checked')) {
                displayNotification('error',
                    '{{ __('You must accept the Terms and Conditions to continue.') }}',
                    'topRight', 5);
                $('#terms_accepted').focus();
                return false;
            }

            // Show loading indicator
            $('.sending-msg').html('<span class="loading">Submitting application...</span>').parent()
                .removeClass('d-none');

            // Create a new FormData object from the form
            var formData = new FormData(this);

            // Remove the original images array that would contain all files
            formData.delete('images[]');

            // Add only our tracked files (the ones not removed by the user)
            if (selectedFiles.length > 0) {
                selectedFiles.forEach((file, index) => {
                    formData.append('images[]', file);
                });
            }

            // Send AJAX request
            $.ajax({
                url: '{{ route('staff.apply') }}',
                type: 'POST',
                data: formData,
                processData: false, // Important for FormData
                contentType: false, // Important for FormData
                success: function(response) {
                    // Clear the form
                    $('form[name="staffApplicationForm"]')[0].reset();
                    $('#selected_days_container').empty();
                    $('#hidden_inputs_container').empty();
                    $('#selected_products_container').empty();
                    $('#hidden_products_container').empty();
                    $('#image-preview').empty();

                    // Clear our tracked files array
                    selectedFiles = [];

                    // Hide the loading indicator
                    $('.application-form-msg').addClass('d-none');

                    // Show success notification
                    displayNotification(
                        'success',
                        '{{ __('Your application has been submitted successfully. Please check your email to verify your account. We will contact you after verification.') }}',
                        'topRight',
                        10
                    );

                    if (response.email_errors && response.email_errors.length) {
                        response.email_errors.forEach(error => {
                            console.log(error);

                            if (error) {
                                displayNotification('info', error, 'topRight', 10);
                            }
                        });
                    }

                    // Redirect to admin dashboard if redirect URL is provided
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseJSON);

                    // Hide the loading indicator
                    $('.application-form-msg').addClass('d-none');

                    var errorMessage =
                        '{{ __('An error occurred while submitting your application. Please try again.') }}';

                    // If we have validation errors, show them
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = '<ul class="mb-0 text-start">';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += '<li>' + value + '</li>';
                        });
                        errorMessage += '</ul>';
                    }

                    // Show error notification
                    displayNotification('error', errorMessage, 'topRight', 10);
                }
            });
        });
    });
</script>

@endsection
