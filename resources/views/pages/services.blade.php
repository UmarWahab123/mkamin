@extends('layouts.app')

@section('title', $type === 'home' ? __('Home Services - mcs.sa Salon') : __('Salon Services - mcs.sa Salon'))

@section('content')
    <!-- INNER PAGE HERO
                            ============================================= -->
    <section id="booking-page" class="inner-page-hero division">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-hero-txt color--white">
                        <h2>{{ $type === 'home' ? __('Book a Home Service') : __('Book a Salon Service') }}</h2>
                        <p>{{ $type === 'home' ? __('Select services that can be performed at your home') : __('Select services available at our salon locations') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- END INNER PAGE HERO -->

    <!-- SERVICES SECTION -->
    <div id="services-listing" class="pt-8 pb-7 services-section division">
        <div class="container">
            <div class="row">
                <!-- Left Column - Filters - DESKTOP VERSION -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="services-sidebar">
                        <h3 class="h3-md mb-4">{{ __('Categories') }}</h3>

                        <!-- Category Filters -->
                        <div class="category-filter mb-5">
                            <ul class="list-group">
                                <li class="list-group-item {{ !isset($selectedCategoryId) ? 'active' : '' }}"
                                    data-category="all">
                                    <a href="#" class="d-flex justify-content-between align-items-center">
                                        {{ $type === 'home' ? __('All Home Services') : __('All Salon Services') }}
                                        <span class="badge bg-dark rounded-pill">{{ count($services) }}</span>
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    <li class="list-group-item {{ isset($selectedCategoryId) && $selectedCategoryId == $category->id ? 'active' : '' }}"
                                        data-category="{{ $category->id }}">
                                        <a href="#" class="d-flex justify-content-between align-items-center">
                                            {{ $category->name }}
                                            <span class="badge bg-dark rounded-pill">{{ count($category->productAndServices) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Services -->
                <div class="col-lg-9">
                    <!-- Category Dropdown - MOBILE VERSION -->
                    <div class="category-dropdown-container mb-4 d-lg-none">
                        <label for="categoryDropdown" class="form-label fw-bold">{{ __('Select Category') }}</label>
                        <select class="form-select" id="categoryDropdown">
                            <option value="all" {{ !isset($selectedCategoryId) ? 'selected' : '' }}>
                                {{ $type === 'home' ? __('All Home Services') : __('All Salon Services') }} ({{ count($services) }})
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ isset($selectedCategoryId) && $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ count($category->productAndServices) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Bar -->
                    <div class="service-search-container mb-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="serviceSearch" class="form-control" placeholder="{{ $type === 'home' ? __('Search home services...') : __('Search salon services...') }}">
                            <button type="button" id="clearSearch" class="btn btn-outline-secondary d-none">×</button>
                        </div>
                    </div>

                    @if($services->isEmpty())
                    <!-- No Services Available Message -->
                    <div class="text-center py-3 no-services-container">
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <div class="mb-4">
                                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                </div>
                                <h3 class="mb-3">{{ __('No services available at this time') }}</h3>
                                <p class="lead mb-4">{{ $type === 'home' ? __('We apologize, but there are currently no home services available with staff who have upcoming time slots.') : __('We apologize, but there are currently no salon services available with staff who have upcoming time slots.') }}</p>
                                <p class="mb-4">{{ __('Please contact us for more information or to schedule a custom appointment.') }}</p>
                                <a href="{{ route('contact') }}" class="btn btn--gold hover--tra-gold">{{ __('Contact Us') }}</a>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Services Grid -->
                    <div class="row services-grid">
                        @foreach ($services as $service)
                            <div class="col-6 col-lg-4 mb-4 service-item" data-service-id="{{ $service->id }}"
                                data-category-id="{{ $service->category_id }}"
                                data-can-home="{{ $service->can_be_done_at_home ? 'true' : 'false' }}">
                                <x-service-card :service="$service" :type="$type" />
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResultsMessage" class="text-center py-5 d-none">
                        <p class="mb-0">{{ $type === 'home' ? __('No home services found matching your criteria.') : __('No salon services found matching your criteria.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END SERVICES SECTION -->

    <!-- Cart Float Component -->
    @include('components.cart-float')

@endsection

@section('styles')
    <style>
        /* Services Sidebar */
        .services-sidebar {
            position: sticky;
            top: 20px;
        }

        .list-group-item {
            border-radius: 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .list-group-item a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-item.active {
            background-color: #212529;
            border-color: #212529;
        }

        /* Search Bar */
        .service-search-container {
            position: relative;
        }

        #serviceSearch {
            padding-right: 35px;
        }

        #clearSearch {
            position: absolute;
            right: 0;
            z-index: 3;
            border: none;
            background: transparent;
            font-size: 1.2rem;
            line-height: 1;
            padding: 8px 12px;
        }

        /* Mobile Category Dropdown */
        .category-dropdown-container select {
            border: 1px solid #dee2e6;
            padding: 0.5rem;
        }

        .category-dropdown-container .form-label {
            margin-bottom: 0.5rem;
        }

        /* Service Item Hidden State */
        .service-item.hidden {
            display: none;
        }
    </style>
@endsection

@section('scripts')
    <!-- Include notification component -->
    @include('components.notification')
    <script>
        $(document).ready(function() {
            // Check if there are any visible service items
            function checkNoResults() {
                if ($('.service-item:not(.hidden)').length === 0) {
                    $('#noResultsMessage').removeClass('d-none');
                } else {
                    $('#noResultsMessage').addClass('d-none');
                }
            }

            // Check URL parameters on page load
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Check if category parameter exists and filter services accordingly
            const categoryParam = getUrlParameter('category');
            if (categoryParam) {
                const categoryItem = $(`.category-filter .list-group-item[data-category="${categoryParam}"]`);
                if (categoryItem.length) {
                    // Update active state
                    $('.category-filter .list-group-item').removeClass('active');
                    categoryItem.addClass('active');

                    // Update dropdown for mobile
                    $('#categoryDropdown').val(categoryParam);

                    // Filter services
                    $('.service-item').addClass('hidden');
                    $(`.service-item[data-category-id="${categoryParam}"]`).removeClass('hidden');

                    // Check if there are visible items
                    checkNoResults();
                }
            }

            // Handle category filter click (Desktop)
            $('.category-filter .list-group-item').click(function(e) {
                e.preventDefault();

                // Update active state
                $('.category-filter .list-group-item').removeClass('active');
                $(this).addClass('active');

                const categoryId = $(this).data('category');

                // Update mobile dropdown to match
                $('#categoryDropdown').val(categoryId);

                // Update URL with selected category without reloading the page
                if (categoryId === 'all') {
                    history.pushState({}, '', '{{ $type === "home" ? "/home-services" : "/salon-services" }}');
                } else {
                    history.pushState({}, '', `{{ $type === "home" ? "/home-services" : "/salon-services" }}?category=${categoryId}`);
                }

                // Filter services
                if (categoryId === 'all') {
                    $('.service-item').removeClass('hidden');
                } else {
                    $('.service-item').addClass('hidden');
                    $(`.service-item[data-category-id="${categoryId}"]`).removeClass('hidden');
                }

                // Check if there are visible items
                checkNoResults();

                // Clear search
                $('#serviceSearch').val('');
                $('#clearSearch').addClass('d-none');
            });

            // Handle category dropdown change (Mobile)
            $('#categoryDropdown').change(function() {
                const categoryId = $(this).val();

                // Update desktop list to match
                $('.category-filter .list-group-item').removeClass('active');
                $(`.category-filter .list-group-item[data-category="${categoryId}"]`).addClass('active');

                // Update URL with selected category without reloading the page
                if (categoryId === 'all') {
                    history.pushState({}, '', '{{ $type === "home" ? "/home-services" : "/salon-services" }}');
                } else {
                    history.pushState({}, '', `{{ $type === "home" ? "/home-services" : "/salon-services" }}?category=${categoryId}`);
                }

                // Filter services
                if (categoryId === 'all') {
                    $('.service-item').removeClass('hidden');
                } else {
                    $('.service-item').addClass('hidden');
                    $(`.service-item[data-category-id="${categoryId}"]`).removeClass('hidden');
                }

                // Check if there are visible items
                checkNoResults();

                // Clear search
                $('#serviceSearch').val('');
                $('#clearSearch').addClass('d-none');
            });

            // Service search functionality
            $('#serviceSearch').on('input', function() {
                const searchQuery = $(this).val().toLowerCase().trim();

                if (searchQuery.length > 0) {
                    $('#clearSearch').removeClass('d-none');

                    // Get the active category filter
                    const activeCategory = $('.category-filter .list-group-item.active').data('category');

                    // Hide all services first if specific category is selected
                    if (activeCategory !== 'all') {
                        $('.service-item').addClass('hidden');
                    } else {
                        $('.service-item').removeClass('hidden');
                    }

                    // Filter within the active category
                    $('.service-item').each(function() {
                        const serviceName = $(this).find('.service-name').text().toLowerCase();
                        const categoryName = $(this).find('.category-badge').text().toLowerCase();
                        const categoryId = $(this).data('category-id');

                        // If matches the search and either all categories are shown or it matches the selected category
                        if ((serviceName.includes(searchQuery) || categoryName.includes(
                                searchQuery)) &&
                            (activeCategory === 'all' || categoryId === activeCategory)) {
                            $(this).removeClass('hidden');
                        } else {
                            $(this).addClass('hidden');
                        }
                    });

                    // Check if there are visible items
                    checkNoResults();
                } else {
                    // If search is empty, show all items in the active category
                    $('#clearSearch').addClass('d-none');

                    const activeCategory = $('.category-filter .list-group-item.active').data('category');

                    if (activeCategory === 'all') {
                        $('.service-item').removeClass('hidden');
                    } else {
                        $('.service-item').addClass('hidden');
                        $(`.service-item[data-category-id="${activeCategory}"]`).removeClass('hidden');
                    }

                    $('#noResultsMessage').addClass('d-none');
                }
            });

            // Clear search button
            $('#clearSearch').click(function() {
                $('#serviceSearch').val('').trigger('input');
            });
        });
    </script>
@endsection
