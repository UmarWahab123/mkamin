<div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm">
    <!-- Saudi Riyal Currency Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/riyal_currancy.css') }}">

    <!-- Custom CSS -->
    <style>
        /* Container and layout styles */
        .custom-container {
            width: 100%;
            max-width: 1920px;
            margin: 0 auto;
        }

        /* Flex layouts */
        .main-layout {
            display: flex;
            flex-direction: column;
        }

        .main-content {
            width: 100%;
        }

        .content-wrapper {
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .section-heading {
            font-size: 1.125rem;
            font-weight: 500;
            padding-top: .3rem !important;
        }

        /* Form layouts */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-width: 1px;
            border-radius: 0.375rem;
            outline: none;
        }

        .form-input:focus {
            outline: none;
            ring-width: 1px;
        }

        /* Row and column layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.5rem;
            margin-left: -0.5rem;
        }

        .col-md-6 {
            width: 100%;
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }

        /* Table styles */
        .table-container {
            border-width: 1px;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .table-scrollable {
            overflow-y: auto;
            max-height: 400px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            position: sticky;
            top: 0;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: left;
            background-color: #f3f4f6;
            padding: 0.5rem 1rem;
            z-index: 1;
        }

        .table-cell {
            padding: 1rem;
        }

        .quantity-controls {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .quantity-btn {
            border-width: 1px;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
        }

        .quantity-input {
            width: 2.5rem;
            text-align: center;
            margin: 0 0.25rem;
            border-width: 1px;
            border-radius: 0.25rem;
            padding: 0.25rem;
        }

        /* Summary section */
        .summary-container {
            border-radius: 0.5rem;
            padding: 1rem;
            position: sticky;
            top: 0;
        }

        .summary-section {
            border-bottom-width: 1px;
            padding: 0.5rem 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.25rem 0;
        }

        .button-container {
            margin-top: 1.5rem;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
            min-height: 2.25rem;
        }

        /* Notification styles */
        .notification {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 50;
            padding: 1rem;
            border-radius: 0.5rem;
            border-width: 1px;
        }

        /* Responsive layout */
        @media (min-width: 768px) {
            .main-layout {
                flex-direction: row;
                gap: 1.5rem;
            }

            .main-content {
                width: 75%;
            }

            .sidebar {
                width: 25%;
            }

            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .payment-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .col-md-6 {
                width: 50%;
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Time Badge Styles */
        .time-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            margin: 0.125rem;
        }

        .time-badge-start {
            background-color: #E9F5FE;
            color: #0369A1;
            border: 1px solid #BAE6FD;
        }

        .time-badge-end {
            background-color: #F0FDF4;
            color: #166534;
            border: 1px solid #BBF7D0;
        }

        .dark .time-badge-start {
            background-color: rgba(3, 105, 161, 0.2);
            color: #7DD3FC;
            border-color: rgba(186, 230, 253, 0.3);
        }

        .dark .time-badge-end {
            background-color: rgba(22, 101, 52, 0.2);
            color: #86EFAC;
            border-color: rgba(187, 247, 208, 0.3);
        }

        /* Booked Time Badge Styles */
        .booked-time-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.15rem 0.35rem;
            border-radius: 0.25rem;
            font-size: 0.65rem;
            font-weight: 500;
            background-color: #FEE2E2;
            color: #DC2626;
            border: 1px solid #FECACA;
            margin: 0.15rem;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .booked-time-badge {
            background-color: rgba(220, 38, 38, 0.15);
            color: #EF4444;
            border-color: rgba(254, 202, 202, 0.2);
        }

        /* Green Time Badge Styles */
        .green-time-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.15rem 0.35rem;
            border-radius: 0.25rem;
            font-size: 0.65rem;
            font-weight: 500;
            background-color: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
            margin: 0.15rem;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .green-time-badge {
            background-color: rgba(5, 150, 105, 0.15);
            color: #10B981;
            border-color: rgba(167, 243, 208, 0.2);
        }

        /* Discount input component */
        .discount-input-container {
            display: flex;
            position: relative;
            width: 100%;
        }

        .discount-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-right: none;
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .discount-toggle-btn:hover:not(:disabled) {
            background-color: #e5e7eb;
        }

        .discount-toggle-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .discount-input-field {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            background-color: #ffffff;
            color: #111827;
            border: 1px solid #d1d5db;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .discount-input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        .discount-input-field:disabled {
            background-color: #f3f4f6;
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom Search Input Styles */
        .search-container {
            position: relative;
            display: inline-block;
        }

        .search-icon-wrapper {
            position: absolute;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            pointer-events: none;
            padding: 0 8px;
        }

        /* LTR style */
        .search-icon-wrapper {
            left: 0;
        }

        /* RTL style */
        [dir="rtl"] .search-icon-wrapper {
            right: 0;
            left: auto;
        }

        .search-input {
            padding: 0.5rem;
            padding-left: 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #111827;
            background-color: #ffffff;
            width: 100%;
            min-width: 200px;
        }

        /* RTL input padding */
        [dir="rtl"] .search-input {
            padding-left: 0.5rem;
            padding-right: 2.5rem;
        }

        .dark .search-input {
            background-color: #1f2937;
            color: #ffffff;
            border-color: #4b5563;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        .search-input:disabled {
            background-color: #f3f4f6;
            opacity: 0.6;
            cursor: not-allowed;
        }

        .dark .search-input:disabled {
            background-color: #374151;
        }

        /* For sites with dark class on body or parent element */
        .dark .discount-toggle-btn {
            color: #e5e7eb;
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .discount-toggle-btn:hover:not(:disabled) {
            background-color: #4b5563;
        }

        .dark .discount-input-field {
            background-color: #1f2937;
            color: #ffffff;
            border-color: #4b5563;
        }

        .dark .discount-input-field:disabled {
            background-color: #374151;
        }
    </style>

    <div class="custom-container">
        <div class="main-layout">
            <!-- Left Column - Reservation Information -->
            <div class="main-content">
                <div class="content-wrapper p-0 bg-white dark:bg-gray-900">
                    <h3 class="section-heading text-gray-900 dark:text-white">
                        {{ __('Reservation information') }}</h3>
                    <div class="form-grid">
                        <!-- Point of Sale Selection (only for non-POS users) -->
                        @if (!$isPosUser)
                            <div class="form-group">
                                <label for="point-of-sale-select" class="form-label text-gray-700 dark:text-gray-300">
                                    {{ __('Select Point of Sale') }}
                                </label>
                                <select id="point-of-sale-select" wire:model.live="pointOfSaleId"
                                    class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                                    @if ($reservationConfirmed) disabled @endif>
                                    <option value="">{{ __('-- Select Point of Sale --') }}</option>
                                    @foreach ($pointOfSales as $pos)
                                        <option value="{{ $pos['id'] }}">
                                            {{ app()->getLocale() === 'ar' ? $pos['name_ar'] : $pos['name_en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Hidden input for POS users -->
                            <input type="hidden" wire:model="pointOfSaleId">
                        @endif

                        <div class="form-group">
                            <label for="location-type" class="form-label text-gray-700 dark:text-gray-300">
                                {{ __('Location type') }}
                            </label>
                            <select id="location-type" wire:model.live="locationType"
                                class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                                @if ($reservationConfirmed) disabled @endif>
                                @foreach ($locationTypeOptions as $type)
                                    <option value="{{ $type }}">
                                        {{ $type === 'salon' ? __('At Salon') : __('At Home') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Selector Component -->
                        <div class="form-group">
                            @livewire('date-time-selector-for-quick-reservation', ['pointOfSaleId' => $pointOfSaleId])
                        </div>
                    </div>

                    <!-- Display a message if no point of sale is selected -->
                    @if (!$isPosUser && !$pointOfSaleId)
                        <div class="message-alert bg-yellow-50 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200"
                            style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 0.375rem;">
                            {{ __('Please select a point of sale to view available services.') }}
                        </div>
                    @endif

                    <!-- Service Selection (only show if point of sale is selected) -->
                    @if ($pointOfSaleId)
                        <div class="flex flex-wrap items-center justify-between" style="padding-top: 1rem;">
                            <h3 class="section-heading text-gray-900 dark:text-white">
                                {{ __('Products and Services') }}</h3>

                            <!-- Search Bar -->
                            <div class="search-container mt-1 sm:mt-0">
                                <div class="search-icon-wrapper">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" wire:model.live="searchQuery" class="search-input"
                                    placeholder="{{ __('Search services...') }}"
                                    @if ($reservationConfirmed) disabled @endif>
                            </div>
                        </div>

                        <!-- Service Categories -->
                        <div class="category-tabs"
                            style="margin-bottom: 1rem; border-bottom: 1px solid; border-color: inherit;">
                            @if (count($serviceCategories) > 0)
                                <ul style="display: flex; flex-wrap: wrap; margin-bottom: -1px;">
                                    @foreach ($serviceCategories as $category)
                                        <li
                                            style="{{ app()->getLocale() === 'ar' ? 'margin-left: 0.5rem;' : 'margin-right: 0.5rem;' }}">
                                            <button
                                                wire:click="loadProductsAndServicesByCategory('{{ $category['id'] }}')"
                                                class="{{ (string) $activeServiceCategoryId === (string) $category['id'] ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                                style="display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500;"
                                                @if ($reservationConfirmed) disabled @endif>
                                                {{ $category['name'] }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-gray-500 dark:text-gray-400" style="padding: 0.5rem 0;">
                                    {{ __('No service categories available.') }}
                                </div>
                            @endif
                        </div>

                        <!-- Services Table with Scrollable Container -->
                        <div class="table-container border-gray-200 dark:border-gray-700">
                            <div class="table-scrollable">
                                <table class="data-table">
                                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-300">
                                        <tr>
                                            <th class="table-header text-left">
                                                {{ __('Service name') }}</th>
                                            <th class="table-header text-right" style="min-width: 130px;">
                                                {{ __('Price') }}</th>
                                            <th class="table-header text-center">
                                                {{ __('Duration') }}</th>
                                            <th class="table-header text-center">
                                                {{ __('Quantity') }}</th>
                                            <th class="table-header text-right" style="min-width: 130px;">
                                                {{ __('Total') }}</th>
                                            <th class="table-header text-center">
                                                {{ __('Select Staff') }}</th>
                                            <th class="table-header text-center">
                                                {{ __('Select Time') }}</th>
                                            <th class="table-header text-center" style="min-width: 140px;">
                                                {{ __('Other Discount') }}</th>
                                            <th class="table-header text-center">
                                                {{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($services) > 0)
                                            @foreach ($services as $index => $service)
                                                <tr class="bg-white dark:bg-gray-900"
                                                    style="border-bottom: 1px solid; border-color: inherit;">
                                                    <td class="table-cell">
                                                        <div style="display: flex; align-items: center;">
                                                            @if ($service['image'])
                                                                <img src="{{ asset('storage/' . $service['image']) }}"
                                                                    alt="{{ $service['name'] }}"
                                                                    style="width: 3rem; height: 3rem; border-radius: 30%; object-fit: cover; {{ app()->getLocale() === 'ar' ? 'margin-left: 0.75rem;' : 'margin-right: 0.75rem;' }}">
                                                            @else
                                                                <div style="width: 3rem; height: 3rem; border-radius: 30%; display: flex; align-items: center; justify-content: center; {{ app()->getLocale() === 'ar' ? 'margin-left: 0.75rem;' : 'margin-right: 0.75rem;' }}"
                                                                    class="bg-gray-200 dark:bg-gray-700">
                                                                    <svg class="text-gray-400"
                                                                        style="width: 1.5rem; height: 1.5rem;"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                                {{ $service['name'] }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="table-cell text-right text-sm text-gray-900 dark:text-gray-200">
                                                        {{ $service['sale_price'] }} <span
                                                            class="icon-saudi_riyal"></span>
                                                    </td>
                                                    <td
                                                        class="table-cell text-center text-sm text-gray-900 dark:text-gray-200">
                                                        {{ $service['duration'] }} {{ __('min') }}
                                                    </td>
                                                    <td class="table-cell">
                                                        <div class="quantity-controls">
                                                            <button
                                                                wire:click="decrementServiceQuantity({{ $service['id'] }})"
                                                                class="quantity-btn text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                                @if ($reservationConfirmed) disabled @endif>
                                                                <span>−</span>
                                                            </button>
                                                            <input type="number"
                                                                value="{{ $service['selected_quantity'] }}"
                                                                wire:input="updateServiceQuantityDirect({{ $service['id'] }}, $event.target.value)"
                                                                min="0"
                                                                class="quantity-input text-gray-900 dark:text-gray-200 border-gray-300 dark:border-gray-600"
                                                                @if ($reservationConfirmed) disabled @endif>
                                                            <button
                                                                wire:click="incrementServiceQuantity({{ $service['id'] }})"
                                                                class="quantity-btn text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                                                                @if ($reservationConfirmed) disabled @endif>
                                                                <span>+</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="table-cell text-right text-sm text-gray-900 dark:text-gray-200">
                                                        {{ $service['selected_quantity'] * $service['sale_price'] }}
                                                        <span class="icon-saudi_riyal"></span>
                                                    </td>
                                                    <td class="table-cell text-center">
                                                        <select wire:model.live="selectedStaff.{{ $service['id'] }}"
                                                            wire:change="selectStaff({{ $service['id'] }}, $event.target.value)"
                                                            class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm"
                                                            style="width: auto; min-width: 120px;"
                                                            @if ($reservationConfirmed || $service['selected_quantity'] <= 0) disabled @endif>
                                                            <option value="">{{ __('-- Select Staff --') }}
                                                            </option>
                                                            @php
                                                                $serviceStaff = $this->getAvailableStaffForService(
                                                                    $service['id'],
                                                                );
                                                            @endphp
                                                            @if (count($serviceStaff) > 0)
                                                                @foreach ($serviceStaff as $staff)
                                                                    <option value="{{ $staff['id'] }}">
                                                                        {{ $staff['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            @else
                                                                <option value="" disabled>
                                                                    {{ __('No staff available') }}</option>
                                                            @endif
                                                        </select>

                                                        @if ($service['selected_quantity'] > 0 && isset($selectedStaff[$service['id']]) && $selectedStaff[$service['id']])
                                                            @php
                                                                $staffId = $selectedStaff[$service['id']];
                                                                $workingHours = $staffWorkingHours[$staffId] ?? null;
                                                            @endphp

                                                            @if ($workingHours)
                                                                <div
                                                                    class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                                                    <div
                                                                        class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                                                        {{ __('Working Hours:') }}</div>
                                                                    <div class="flex flex-wrap">
                                                                        <span class="green-time-badge">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-3 w-3 inline-block {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                            </svg>
                                                                            {{ $workingHours['start_time'] }} -
                                                                            {{ $workingHours['end_time'] }}
                                                                        </span>
                                                                    </div>

                                                                    @if (isset($staffBookings[$staffId]) && count($staffBookings[$staffId]) > 0)
                                                                        <div class="mt-2">
                                                                            <div
                                                                                class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                                                                {{ __('Booked Times:') }}</div>
                                                                            <div class="flex flex-wrap">
                                                                                @foreach ($staffBookings[$staffId] as $booking)
                                                                                    <span class="booked-time-badge"
                                                                                        title="{{ $booking['name'] }}">{{ $booking['time_range'] }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2 text-center">
                                                                    <span
                                                                        class="text-gray-400 dark:text-gray-500 text-sm italic">{{ __('No schedule available') }}</span>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="table-cell text-center">
                                                        @if ($service['selected_quantity'] > 0 && isset($selectedStaff[$service['id']]) && $selectedStaff[$service['id']])
                                                            @php
                                                                $staffId = $selectedStaff[$service['id']];
                                                                $workingHours = $staffWorkingHours[$staffId] ?? null;
                                                                // Log::info($availableTimes);
                                                            @endphp

                                                            @if ($workingHours && !empty($availableTimes))
                                                                <select
                                                                    wire:model.live="selectedTime.{{ $service['id'] }}"
                                                                    wire:change="selectTime({{ $service['id'] }}, $event.target.value)"
                                                                    class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm"
                                                                    style="width: auto; min-width: 120px;"
                                                                    @if ($reservationConfirmed) disabled @endif>
                                                                    <option value="">
                                                                        {{ __('-- Select Time --') }}</option>
                                                                    @foreach ($availableTimes as $time)
                                                                        <option value="{{ $time }}">
                                                                            {{ $time }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                @if (!isset($selectedTime[$service['id']]) || !$selectedTime[$service['id']])
                                                                    <div
                                                                        class="text-amber-500 dark:text-amber-400 text-xs mt-1">
                                                                        {{ __('Please select a time') }}
                                                                    </div>
                                                                @else
                                                                    @php
                                                                        $startTime = \Carbon\Carbon::parse(
                                                                            $selectedTime[$service['id']],
                                                                        );
                                                                        $endTime = $startTime
                                                                            ->copy()
                                                                            ->addMinutes(
                                                                                $service['duration'] *
                                                                                    $service['selected_quantity'],
                                                                            );
                                                                        $endTimeFormatted = $endTime->format('h:i A');
                                                                    @endphp
                                                                    <div
                                                                        class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                                                        <div
                                                                            class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                                                            {{ __('Service Time:') }}</div>
                                                                        <div class="flex flex-wrap">
                                                                            <span class="green-time-badge">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-3 w-3 inline-block {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                                {{ $selectedTime[$service['id']] }} -
                                                                                {{ $selectedEndTime[$service['id']] ?? '' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div
                                                                    class="text-gray-400 dark:text-gray-500 text-sm italic">
                                                                    {{ __('No time slots available') }}
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div
                                                                class="text-gray-400 dark:text-gray-500 text-sm italic">
                                                                {{ __('Select staff first') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="table-cell">
                                                        <div class="discount-input-container">
                                                            <button type="button"
                                                                wire:click="toggleDiscountType({{ $service['id'] }})"
                                                                class="discount-toggle-btn"
                                                                @if ($reservationConfirmed) disabled @endif>
                                                                <span
                                                                    class="discount-type-display-{{ $service['id'] }}">
                                                                    {!! isset($otherDiscountType[$service['id']]) && $otherDiscountType[$service['id']] === 'fixed'
                                                                        ? '<span class="icon-saudi_riyal"></span>'
                                                                        : '%' !!}
                                                                </span>
                                                            </button>
                                                            <input type="text"
                                                                wire:model.live="otherDiscount.{{ $service['id'] }}"
                                                                wire:keydown.enter="addToSummaryOnEnter({{ $service['id'] }})"
                                                                class="discount-input-field"
                                                                placeholder="{{ __('Discount') }}"
                                                                @if ($reservationConfirmed || $service['selected_quantity'] <= 0) disabled @endif>
                                                            <input type="hidden"
                                                                wire:model="otherDiscountType.{{ $service['id'] }}"
                                                                value="{{ isset($otherDiscountType[$service['id']]) ? $otherDiscountType[$service['id']] : 'percentage' }}">
                                                        </div>
                                                    </td>
                                                    <td class="table-cell text-center">
                                                        <button wire:click="addToSummary({{ $service['id'] }})"
                                                            class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-1 px-3 rounded text-sm"
                                                            @if ($reservationConfirmed || $service['selected_quantity'] <= 0) disabled @endif>
                                                            {{ isset($selectedServicesForSummary[$service['id']]) ? __('Update') : __('Add') }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="bg-white dark:bg-gray-900">
                                                <td colspan="9"
                                                    class="table-cell text-center text-sm text-gray-500 dark:text-gray-400">
                                                    {{ __('No services available in this category') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @error('services')
                        <div class="text-red-500 text-sm" style="margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Discount Code Section -->
                            <h3 class="section-heading text-gray-900 dark:text-white" style="padding-top: 1rem;">
                                {{ __('Discount Code') }}
                            </h3>
                            <div class="form-group col-12 flex items-center align-items-center" style="gap: 0.5rem;">
                                <div class="flex-grow">
                                    <input type="text" id="discount-code" wire:model.live="discountCode"
                                        placeholder="{{ __('Enter discount code') }}"
                                        wire:keydown.enter="checkDiscountCode"
                                        class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                                        @if ($reservationConfirmed) disabled @endif
                                        @if ($discount) readonly @endif>
                                </div>
                                @if (!$discount)
                                    <div>
                                        <button type="button" wire:click="checkDiscountCode"
                                            wire:loading.attr="disabled"
                                            class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded text-sm transition-all duration-150 ease-in-out"
                                            @if ($reservationConfirmed || trim($discountCode) === '') disabled @endif>
                                            <span wire:loading.remove
                                                wire:target="checkDiscountCode">{{ __('Check') }}</span>
                                            <span wire:loading
                                                wire:target="checkDiscountCode">{{ __('Checking...') }}</span>
                                        </button>
                                    </div>
                                @endif
                                @if ($discount)
                                    <button type="button" wire:click="removeDiscount"
                                        class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded text-sm"
                                        @if ($reservationConfirmed) disabled @endif>
                                        {{ __('Remove') }}
                                    </button>
                                @endif
                            </div>
                            <div class="col-12">
                                @error('discountCode')
                                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                                @if ($discount)
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                            {{ $discount['name_en'] }}</h3>
                                        <div class="mt-1 text-sm text-green-700 dark:text-green-300">
                                            <p>{{ $discount['description_en'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Other Total Discount Section -->
                        <div class="col-md-6">
                            <h3 class="section-heading text-gray-900 dark:text-white" style="padding-top: 1rem;">
                                {{ __('Additional Discount') }}
                            </h3>
                            <div class="form-group col-12 flex items-center align-items-center" style="gap: 0.5rem;">
                                <div class="flex-grow discount-input-container">
                                    <button type="button"
                                        wire:click="toggleOtherTotalDiscountType"
                                        class="discount-toggle-btn"
                                        @if ($reservationConfirmed) disabled @endif>
                                        <span class="other-total-discount-type-display">
                                            {!! isset($otherTotalDiscountType) && $otherTotalDiscountType === 'fixed'
                                                ? '<span class="icon-saudi_riyal"></span>'
                                                : '%' !!}
                                        </span>
                                    </button>
                                    <input type="text" id="other-total-discount" wire:model.live="otherTotalDiscount"
                                        placeholder="{{ __('Enter discount amount') }}"
                                        wire:keydown.enter="applyOtherTotalDiscount"
                                        class="discount-input-field"
                                        @if ($reservationConfirmed) disabled @endif>
                                    <input type="hidden" wire:model="otherTotalDiscountType"
                                        value="{{ isset($otherTotalDiscountType) ? $otherTotalDiscountType : 'percentage' }}">
                                </div>
                                <div>
                                    <button type="button" wire:click="applyOtherTotalDiscount"
                                        wire:loading.attr="disabled"
                                        class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded text-sm transition-all duration-150 ease-in-out"
                                        @if ($reservationConfirmed || trim($otherTotalDiscount) === '') disabled @endif>
                                        <span wire:loading.remove
                                            wire:target="applyOtherTotalDiscount">{{ $otherTotalDiscountApplied ? __('Update') : __('Add') }}</span>
                                        <span wire:loading
                                            wire:target="applyOtherTotalDiscount">{{ $otherTotalDiscountApplied ? __('Updating...') : __('Adding...') }}</span>
                                    </button>
                                </div>
                                @if ($otherTotalDiscountApplied)
                                    <button type="button" wire:click="removeOtherTotalDiscount"
                                        class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded text-sm"
                                        @if ($reservationConfirmed) disabled @endif>
                                        {{ __('Remove') }}
                                    </button>
                                @endif
                            </div>
                            <div class="col-12">
                                @error('otherTotalDiscount')
                                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                                @if ($otherTotalDiscountApplied)
                                    <div class="ml-3">
                                        <div class="mt-1 text-sm text-green-700 dark:text-green-300">
                                            <p>{{ __('Additional discount applied') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <h3 class="section-heading text-gray-900 dark:text-white" style="padding-top: 1rem;">
                        {{ __('Payment information') }}
                    </h3>

                    <!-- Payment Fields -->
                    <div class="payment-grid form-grid">
                        <div class="form-group">
                            <label for="total-paid-cash"
                                class="form-label text-gray-700 dark:text-gray-300">{{ __('Total paid cash') }}</label>
                            <input type="number" id="total-paid-cash" wire:model.blur="totalPaidCash"
                                wire:keydown.enter="$refresh" min="0" step="0.01"
                                class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                                @if ($reservationConfirmed) disabled @endif>
                            @error('totalPaidCash')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total-paid-online"
                                class="form-label text-gray-700 dark:text-gray-300">{{ __('Total paid online') }}</label>
                            <input type="number" id="total-paid-online" wire:model.blur="totalPaidOnline"
                                wire:keydown.enter="$refresh" min="0" step="0.01"
                                class="form-input bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                                @if ($reservationConfirmed) disabled @endif>
                            @error('totalPaidOnline')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="change-given"
                                class="form-label text-gray-700 dark:text-gray-300">{{ __('Change given') }}</label>
                            <input type="number" id="change-given" wire:model.blur="changeGiven" readonly
                                class="form-input bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                        </div>
                    </div>

                    @error('payment')
                        <div class="text-red-500 text-sm" style="margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                    @error('general')
                        <div class="text-red-500 text-sm" style="margin-top: 0.75rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Right Column - Reservation Summary -->
            <div class="sidebar">
                <div class="summary-container bg-white dark:bg-gray-900 shadow">
                    <h3 class="section-heading text-gray-900 dark:text-white">{{ __('Reservation summary') }}
                    </h3>

                    <div>
                        <!-- Selected Services -->
                        <div class="summary-section border-gray-200 dark:border-gray-700">
                            <!-- Scrollable Container for Selected Services -->
                            <div style="overflow-y: auto; max-height: 275px;">
                                <!-- Display Services from the summaryItems array -->
                                @if (count($summaryItems) > 0)
                                    <div style="margin-bottom: 0.5rem;">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                            style="text-transform: uppercase;">
                                            {{ __('Selected Services') }}</h4>
                                    </div>

                                    @foreach ($summaryItems as $service)
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0;">
                                            <div style="display: flex; align-items: center;">
                                                <div class="bg-primary-100 dark:bg-primary-800"
                                                    style="flex-shrink: 0; width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; {{ app()->getLocale() === 'ar' ? 'margin-left: 0.75rem;' : 'margin-right: 0.75rem;' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="text-primary-600 dark:text-primary-300"
                                                        style="height: 1rem; width: 1rem;" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                </div>
                                                <div style="flex: 1;">
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ $service['service_name'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($service['price'], 2) }}
                                                        <span class="icon-saudi_riyal"></span>
                                                        × {{ $service['quantity'] }}
                                                    </p>

                                                    <!-- Display other discount if applied -->
                                                    @if (isset($service['other_discount_value']) && $service['other_discount_value'] > 0)
                                                        <p class="text-xs text-red-500 dark:text-red-400">
                                                            {{ __('Discount') }}:
                                                            @if ($service['other_discount_type'] === 'percentage')
                                                                {{ $service['other_discount_value'] }}%
                                                            @else
                                                                {{ number_format($service['other_discount_value'], 2) }}
                                                                / 1.15 <span class="icon-saudi_riyal"></span>
                                                            @endif
                                                            =
                                                            ({{ number_format($service['other_discount_amount'], 2) }}
                                                            <span class="icon-saudi_riyal"></span>)
                                                        </p>
                                                    @endif

                                                    <!-- Display selected date, staff and time if available -->
                                                    <div class="mt-1 space-y-1">
                                                        @if ($service['appointment_date'])
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3 inline-block {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                {{ $service['appointment_date'] }}
                                                            </p>
                                                        @endif

                                                        @if ($service['staff_id'])
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3 inline-block {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                @php
                                                                    $staffName = 'Staff';
                                                                    foreach ($this->availableStaff as $staff) {
                                                                        if (
                                                                            isset($staff['id']) &&
                                                                            $staff['id'] == $service['staff_id']
                                                                        ) {
                                                                            $staffName = $staff['name'];
                                                                            break;
                                                                        }
                                                                    }
                                                                @endphp
                                                                {{ $staffName }}
                                                            </p>
                                                        @endif

                                                        @if ($service['start_time'] && $service['end_time'])
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3 inline-block {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                {{ $service['start_time'] }} -
                                                                {{ $service['end_time'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center;">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    style="{{ app()->getLocale() === 'ar' ? 'margin-left: 0.75rem;' : 'margin-right: 0.75rem;' }}">{{ number_format($service['total'], 2) }}</span>
                                                <button wire:click="removeFromSummary({{ $service['service_id'] }})"
                                                    class="text-red-400 hover:text-red-500 dark:hover:text-red-300"
                                                    style="padding: 0.25rem;"
                                                    @if ($reservationConfirmed) disabled @endif>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        style="height: 1rem; width: 1rem; {{ app()->getLocale() === 'ar' ? 'margin-left: 0.25rem;' : 'margin-right: 0.25rem;' }}"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="padding: 1rem 0; text-align: center;"
                                        class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No services selected yet') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="summary-section border-gray-200 dark:border-gray-700">
                            <div class="summary-row">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Subtotal') }}</span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($subtotal, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>

                            @if ($discountAmount > 0)
                                <div class="summary-row text-red-600 dark:text-red-400">
                                    <span class="text-sm">
                                        {{ __('Discount') }}
                                        @if ($discount)
                                            ({{ $discount['code'] }} -
                                            @if ($discount['type'] === 'percentage')
                                                {{ $discount['value'] }}%
                                            @else
                                                {{ number_format($discount['value'], 2) }} <span
                                                    class="icon-saudi_riyal"></span>
                                            @endif
                                            )
                                        @endif
                                    </span>
                                    <span class="text-sm">-{{ number_format($discountAmount, 2) }} <span
                                            class="icon-saudi_riyal"></span></span>
                                </div>

                                <div class="summary-row">
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ __('After Discount') }}</span>
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($subtotal - $discountAmount, 2) }}
                                        <span class="icon-saudi_riyal"></span></span>
                                </div>
                            @endif

                            @if ($otherTotalDiscountApplied && $otherTotalDiscountAmount > 0)
                                <div class="summary-row text-red-600 dark:text-red-400">
                                    <span class="text-sm">
                                        {{ __('Additional Discount') }}
                                        @if ($otherTotalDiscountType === 'percentage')
                                            ({{ $otherTotalDiscount }}%)
                                        @else
                                            ({{ is_numeric($otherTotalDiscount) ? number_format((float)$otherTotalDiscount, 2) : '0.00' }} /1.15 <span class="icon-saudi_riyal"></span>)
                                        @endif
                                    </span>
                                    <span class="text-sm">-{{ number_format($otherTotalDiscountAmount, 2) }} <span
                                            class="icon-saudi_riyal"></span></span>
                                </div>

                                <div class="summary-row">
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ __('After Additional Discount') }}</span>
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($subtotal - $discountAmount - $otherTotalDiscountAmount, 2) }}
                                        <span class="icon-saudi_riyal"></span></span>
                                </div>
                            @endif

                            <div class="summary-row">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('VAT') }}
                                    ({{ $vatRate }}%)</span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($vatAmount, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <!-- Other Taxes Section -->
                            <div class="summary-row">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Other Taxes') }}</span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($otherTaxesAmount, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <div class="summary-row">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Duration') }}</span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $totalDurationMinutes }}
                                    {{ __('min') }}</span>
                            </div>
                        </div>

                        <div class="summary-section border-gray-200 dark:border-gray-700">
                            <div class="summary-row">
                                <span
                                    class="text-base font-medium text-gray-900 dark:text-white">{{ __('Total') }}</span>
                                <span
                                    class="text-base font-medium text-gray-900 dark:text-white">{{ number_format($grandTotal, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>
                        </div>

                        <div class="summary-section border-gray-200 dark:border-gray-700">
                            <div class="summary-row">
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ __('Total paid cash') }}</span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($totalPaidCash, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <div class="summary-row">
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ __('Total paid online') }}</span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($totalPaidOnline, 2) }}
                                    <span class="icon-saudi_riyal"></span></span>
                            </div>

                            <div style="padding: 0.5rem 0;">
                                <div class="summary-row">
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ __('Change given') }}</span>
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($changeGiven, 2) }}
                                        <span class="icon-saudi_riyal"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="button-container">
                        @if ($reservationConfirmed)
                            <button type="button" onclick="window.location.reload()"
                                class="primary-button text-white shadow border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:bg-primary-700 dark:focus:ring-offset-primary-700">
                                {{ __('New reservation') }}
                            </button>

                            <button type="button"
                                onclick="openPrintPreview('{{ route('reservations.invoice', ['id' => $this->reservationId]) }}'); return false;"
                                class="primary-button text-primary-600 dark:text-primary-400 border-primary-600 dark:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:border-primary-700 dark:hover:border-primary-300 hover:text-primary-700 dark:hover:text-primary-300 focus:ring-primary-500 dark:focus:ring-primary-400"
                                style="margin-top: 0.75rem;">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    style="height: 1rem; width: 1rem; {{ app()->getLocale() === 'ar' ? 'margin-left: 0.25rem;' : 'margin-right: 0.25rem;' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                {{ __('Print Reservation') }}
                            </button>
                        @else
                            <button type="button" wire:click="confirmReservation"
                                class="primary-button text-white shadow border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:bg-primary-700 dark:focus:ring-offset-primary-700"
                                @if ($grandTotal <= 0 || abs($grandTotal - ($totalPaidCash + $totalPaidOnline)) > 0.01) disabled @endif>
                                <span wire:loading.remove
                                    wire:target="confirmReservation">{{ __('Confirm reservation') }}</span>
                                <span wire:loading wire:target="confirmReservation">{{ __('Processing...') }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="notification bg-green-50 dark:bg-green-900 border-green-200 dark:border-green-700">
                <div style="display: flex;">
                    <div style="flex-shrink: 0;">
                        <svg class="text-green-400" style="height: 1.25rem; width: 1.25rem;"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div style="margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0.75rem;">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="notification bg-blue-50 dark:bg-blue-900 border-blue-200 dark:border-blue-700">
                <div style="display: flex;">
                    <div style="flex-shrink: 0;">
                        <svg class="text-blue-400" style="height: 1.25rem; width: 1.25rem;"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div style="margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0.75rem;">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Print Preview Component -->
@include('components.print-preview')

<!-- Simplified script section without date-time picker functionality -->
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('updateServiceQuantity', (serviceId, quantity) => {
            @this.updateServiceQuantity(serviceId, quantity);
        });

        // Add event listener for automatic printing
        @this.on('autoPrintInvoice', (data) => {
            openPrintPreview(data[0].url);
        });

        // Listen for discount type updates from the server
        @this.on('discountTypeUpdated', ({
            serviceId,
            type
        }) => {
            const displayElement = document.querySelector(`.discount-type-display-${serviceId}`);
            if (displayElement) {
                displayElement.innerHTML = type === 'fixed' ? '<span class="icon-saudi_riyal"></span>' :
                    '%';
            }
        });
    });
</script>
