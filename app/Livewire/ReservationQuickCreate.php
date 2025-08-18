<?php

namespace App\Livewire;

use App\Models\BookedReservation;
use App\Models\BookedReservationItem;
use Livewire\Component;
use App\Models\ProductAndService;
use App\Models\ServiceCategory;
use App\Models\PointOfSale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationQuickCreate extends Component
{
    // Point of Sale Selection
    public $pointOfSales = [];
    public $pointOfSaleId = null;
    public $isPosUser = false;

    // Reservation Information
    public $reservationDate;
    public $appointmentDate;
    public $startTime;
    public $endTime;
    public $locationTypeOptions = ['salon', 'home'];
    public $locationType = 'salon';
    public $notes = '';

    // Service Categories and Services
    public $serviceCategories = [];
    public $services = [];
    public $activeServiceCategoryId = null;
    public $serviceQuantities = [];
    public $searchQuery = ''; // Search query for filtering services

    // Summary Information - New consolidated structure
    public $summaryItems = []; // Array of service items in the summary with all their details

    // Legacy properties - maintained for backward compatibility
    public $summaryQuantities = [];
    public $availableStaff = [];
    public $selectedStaff = [];
    public $staffWorkingHours = [];
    public $selectedTime = [];
    public $selectedEndTime = [];
    public $availableTimes = [];
    public $selectedServicesForSummary = [];
    public $summaryServiceDates = []; // Store appointment dates for services in summary
    public $staffBookings = []; // Store bookings for staff members

    // Other Discount properties
    public $otherDiscount = [];
    public $otherDiscountType = [];

    // Payment Information
    public $discountCode = '';
    public $discount = null;
    public $discountAmount = 0;
    public $totalPaidCash = 0;
    public $totalPaidOnline = 0;
    public $changeGiven = 0;

    // Summary Totals
    public $subtotal = 0;
    public $vatRate = 0; // VAT rate as percentage
    public $vatAmount = 0;
    public $otherTaxesAmount = 0; // New property for other taxes
    public $grandTotal = 0;
    public $totalDurationMinutes = 0;

    // Reservation Status
    public $reservationConfirmed = false;
    public $reservationId;

    // Tab Navigation
    public $activeTab = 'services';

    // Additional Discount properties
    public $otherTotalDiscount = '';
    public $otherTotalDiscountType = 'percentage';
    public $otherTotalDiscountApplied = false;
    public $otherTotalDiscountAmount = 0;

    protected $listeners = [
        'dateSelected' => 'onDateSelected',
        'dateChanged' => 'resetStaffSelection',
        'staffAvailable' => 'onStaffAvailable',
        'staffWorkingHoursLoaded' => 'onStaffWorkingHoursLoaded',
        // Removed date-time related listeners
    ];

    public function mount()
    {
        $this->reservationDate = date('Y-m-d');
        $this->startTime = Carbon::now()->format('H:i');
        $this->endTime = $this->startTime; // Initialize endTime with startTime

        // Check if user is a point of sale
        $user = Auth::user();
        $this->isPosUser = false;

        if ($user) {
            // Check if user has a point of sale
            $pointOfSale = PointOfSale::where('user_id', $user->id)->first();
            if ($pointOfSale) {
                $this->isPosUser = true;
                $this->pointOfSaleId = $pointOfSale->id;
                // Load service categories and services
                $this->loadServiceCategories();

                // Dispatch event to notify date selector about the point of sale
                $this->dispatch('pointOfSaleChanged', $this->pointOfSaleId);
            } else {
                // Load available point of sales for selection
                $this->loadPointOfSales();
            }
        } else {
            // Load available point of sales for selection
            $this->loadPointOfSales();
        }
    }

    public function loadPointOfSales()
    {
        $this->pointOfSales = PointOfSale::where('is_active', true)
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar'])
            ->toArray();
    }

    public function updatedPointOfSaleId()
    {
        // Clear any previously selected services and reset state
        $this->services = [];
        $this->serviceQuantities = [];
        $this->selectedServicesForSummary = [];
        $this->summaryQuantities = [];
        $this->availableStaff = [];
        $this->selectedStaff = [];
        $this->staffWorkingHours = [];
        $this->calculateTotals();

        // When point of sale is selected, load categories and services
        if ($this->pointOfSaleId) {
            $this->loadServiceCategories();

            // Always dispatch pointOfSaleChanged event to reload disabled dates
            // This ensures that for non-point-of-sale users, the date selector will reload disabled dates
            $this->dispatch('pointOfSaleChanged', $this->pointOfSaleId);
        } else {
            // Reset categories and services if no point of sale selected
            $this->serviceCategories = [];
            $this->services = [];
            $this->activeServiceCategoryId = null;

            // Notify date selector that no point of sale is selected
            $this->dispatch('pointOfSaleChanged', null);
        }
    }

    public function updatedStartTime()
    {
        $this->calculateEndTime();
    }

    private function calculateEndTime()
    {
        if ($this->startTime && $this->totalDurationMinutes > 0) {
            $startTime = Carbon::parse($this->startTime);
            $endTime = $startTime->copy()->addMinutes($this->totalDurationMinutes);
            $this->endTime = $endTime->format('H:i');
        } else {
            $this->endTime = $this->startTime;
        }
    }

    public function loadServiceCategories()
    {
        if (!$this->pointOfSaleId) {
            $this->serviceCategories = [];
            return;
        }

        try {
            // Get all active service categories for the selected point of sale
            $categories = ServiceCategory::where('is_active', true)
                ->where('point_of_sale_id', $this->pointOfSaleId)
                ->orderBy('sort_order')
                ->get(['id', 'name_en', 'name_ar']);

            // Initialize categories array with "All" as the first category
            $this->serviceCategories = [
                [
                    'id' => 'all',
                    'name' => __('All'),
                ]
            ];

            foreach ($categories as $category) {
                // Use the name based on current locale
                $nameColumn = 'name_' . app()->getLocale();
                $this->serviceCategories[] = [
                    'id' => $category->id,
                    'name' => $category->$nameColumn,
                ];
            }

            // Set "All" category as active by default
            if (!empty($this->serviceCategories)) {
                $this->activeServiceCategoryId = 'all';
                $this->loadProductsAndServicesByCategory('all');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading service categories: ' . $e->getMessage());
            $this->serviceCategories = [];
        }
    }

    public function loadProductsAndServicesByCategory($categoryId)
    {
        if (!$this->pointOfSaleId) {
            $this->services = [];
            return;
        }

        try {
            // Store the previous active category ID
            $previousCategoryId = $this->activeServiceCategoryId;

            // Store selected staff and times before changing the category
            $selectedStaffBackup = $this->selectedStaff;
            $selectedTimeBackup = $this->selectedTime;
            $selectedEndTimeBackup = $this->selectedEndTime;

            $this->activeServiceCategoryId = $categoryId;

            // Start building the query
            $query = ProductAndService::where('product_and_services.is_active', true)
                ->whereHas('category', function ($query) {
                    $query->where('point_of_sale_id', $this->pointOfSaleId);
                });

            // Apply category filter if not "all"
            if ($categoryId !== 'all') {
                $query->where('product_and_services.category_id', $categoryId);
            }

            // Filter by search query if provided
            if (!empty($this->searchQuery)) {
                $searchTerm = '%' . $this->searchQuery . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('product_and_services.name_en', 'like', $searchTerm)
                        ->orWhere('product_and_services.name_ar', 'like', $searchTerm)
                        ->orWhere('product_and_services.description_en', 'like', $searchTerm)
                        ->orWhere('product_and_services.description_ar', 'like', $searchTerm);
                });
            }

            // Filter by location type
            if ($this->locationType === 'home') {
                $query->where(function ($q) {
                    $q->where('product_and_services.is_product', true)
                        ->orWhere('product_and_services.can_be_done_at_home', true);
                });
            }

            // Only include services that have staff assigned to them
            $query->whereHas('staff', function ($q) {
                $q->where('is_active', true)->whereHas('pointOfSale', function ($posQuery) {
                    $posQuery->where('point_of_sales.id', $this->pointOfSaleId);
                });

                // Apply home visit filter if needed
                if ($this->locationType === 'home') {
                    $q->whereHas('timeIntervals', function ($timeQuery) {
                        $timeQuery->where('date', $this->appointmentDate ?? $this->reservationDate)
                            ->where('is_closed', false)
                            ->where('can_visit_home', true);
                    });
                }
            });

            $services = $query->get();

            // Reset the services array using sequential indexes
            $this->services = [];
            foreach ($services as $service) {
                // Set quantity based on summary quantities (if service is in summary)
                // Or reset to 0 if not in summary - this fixes the quantity bug
                $selectedQuantity = 0;
                if (isset($this->selectedServicesForSummary[$service->id]) && isset($this->summaryQuantities[$service->id])) {
                    $selectedQuantity = $this->summaryQuantities[$service->id];
                } elseif (isset($this->serviceQuantities[$service->id])) {
                    // Only use serviceQuantities if it's for the current category view
                    // This helps prevent showing quantities for services not in the summary
                    $selectedQuantity = $this->serviceQuantities[$service->id];
                }

                $price = $this->locationType === 'home' && $service->price_home ?
                    $service->price_home :
                    $service->price;

                $sale_price = $this->locationType === 'home' && $service->sale_price_at_home ?
                    $service->sale_price_at_home :
                    $service->sale_price_at_saloon;

                $this->services[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'name_en' => $service->name_en,
                    'name_ar' => $service->name_ar,
                    'price' => $price,
                    'sale_price' => $sale_price ?? $price,
                    'duration' => $service->duration_minutes ?: 0,
                    'selected_quantity' => $selectedQuantity,
                    'total' => $price * $selectedQuantity,
                    'image' => $service->image,
                ];

                // Ensure the service is in the serviceQuantities array
                if (!isset($this->serviceQuantities[$service->id])) {
                    $this->serviceQuantities[$service->id] = $selectedQuantity;
                }
            }

            // Restore staff and time selections for services that appear in this category
            // This ensures we don't lose selections when switching categories
            foreach ($this->services as $service) {
                $serviceId = $service['id'];

                // Restore staff selection if it exists in the backup
                if (isset($selectedStaffBackup[$serviceId])) {
                    $this->selectedStaff[$serviceId] = $selectedStaffBackup[$serviceId];

                    // Re-fetch staff bookings
                    if ($this->selectedStaff[$serviceId]) {
                        $this->getStaffBookings($this->selectedStaff[$serviceId]);
                    }
                }

                // Restore time selections if they exist in the backup
                if (isset($selectedTimeBackup[$serviceId])) {
                    $this->selectedTime[$serviceId] = $selectedTimeBackup[$serviceId];
                }

                if (isset($selectedEndTimeBackup[$serviceId])) {
                    $this->selectedEndTime[$serviceId] = $selectedEndTimeBackup[$serviceId];
                }
            }

            // After loading services, regenerate available times for any selected staff
            // only for services in the summary
            foreach ($this->selectedStaff as $serviceId => $staffId) {
                if ($staffId && isset($this->selectedServicesForSummary[$serviceId])) {
                    // If staff working hours aren't loaded yet, trigger loading them
                    if (!isset($this->staffWorkingHours[$staffId])) {
                        $this->dispatch('staffSelected', $staffId, $serviceId);
                    } else {
                        // Otherwise directly regenerate available times
                        $this->generateAvailableTimes($staffId, $serviceId);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading services: ' . $e->getMessage());
            $this->services = [];
        }
    }

    public function updatedLocationType()
    {
        // Notify DateTimeSelectorForQuickReservation about location type change
        $this->dispatch('locationTypeChanged', $this->locationType);

        // Reload services for the active category with the new location type
        if ($this->activeServiceCategoryId) {
            $this->loadProductsAndServicesByCategory($this->activeServiceCategoryId);
        }

        // Recalculate totals since prices may have changed
        $this->calculateTotals();
    }

    /**
     * Add a service to the summary panel
     */
    public function addToSummary($serviceId)
    {
        // Find the service by ID
        $service = $this->getServiceById($serviceId);
        if (!$service) return;

        $serviceQuantity = $service['selected_quantity'];

        // Only add if quantity is greater than 0
        if ($serviceQuantity > 0) {
            // Check if staff is selected but time is not
            $staffId = $this->selectedStaff[$serviceId] ?? null;
            $startTime = $this->selectedTime[$serviceId] ?? null;

            // If staff is selected but no time is selected, don't add to summary
            if ($staffId && !$startTime) {
                // Add an error message that will be displayed in the UI
                $this->addError('time_required_' . $serviceId, __('Please select a time for this service.'));
                return;
            }

            // Clear any previous error for this service
            $this->resetErrorBag('time_required_' . $serviceId);

            // Legacy support - keep these updated for backward compatibility
            $this->selectedServicesForSummary[$serviceId] = true;
            $this->summaryQuantities[$serviceId] = $serviceQuantity;
            $this->summaryServiceDates[$serviceId] = $this->appointmentDate;

            // Get existing staff and time selections
            $endTime = $this->selectedEndTime[$serviceId] ?? null;

            // Check if service already exists in summary to update it
            $existingIndex = $this->findSummaryItemIndexByServiceId($serviceId);

            // Round the price and calculate total with proper rounding
            $price = round($service['price'], 2);
            $total = round($price * $serviceQuantity, 2);

            // Handle other discount if specified
            $otherDiscountValue = isset($this->otherDiscount[$serviceId]) ? floatval($this->otherDiscount[$serviceId]) : 0;
            $otherDiscountType = isset($this->otherDiscountType[$serviceId]) ? $this->otherDiscountType[$serviceId] : 'percentage';

            // Calculate the discount amount
            $otherDiscountAmount = 0;
            if ($otherDiscountValue > 0) {
                if ($otherDiscountType === 'percentage') {
                    // Cap percentage to 100%
                    $otherDiscountValue = min(100, $otherDiscountValue);
                    $otherDiscountAmount = round($total * ($otherDiscountValue / 100), 2);
                } else {
                    // Cap fixed discount to total amount
                    $otherDiscountAmount = min($total, round($otherDiscountValue / 1.15, 2));
                }

                // Apply the discount to total
                $total = max(0, $total - $otherDiscountAmount);
            }

            $summaryItem = [
                'service_id' => $serviceId,
                'service_name' => $service['name'],
                'service_name_en' => $service['name_en'],
                'service_name_ar' => $service['name_ar'],
                'price' => $price,
                'quantity' => $serviceQuantity,
                'duration' => $service['duration'],
                'total' => $total,
                'location_type' => $this->locationType,
                'appointment_date' => $this->appointmentDate,
                'staff_id' => $staffId,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'other_discount_value' => $otherDiscountValue,
                'other_discount_type' => $otherDiscountType,
                'other_discount_amount' => $otherDiscountAmount
            ];

            if ($existingIndex !== null) {
                // Update existing item
                $this->summaryItems[$existingIndex] = $summaryItem;
            } else {
                // Add new item with sequential index
                $this->summaryItems[] = $summaryItem;
            }

            // Update available time slots if staff is selected for this service
            if (isset($this->selectedStaff[$serviceId]) && $this->selectedStaff[$serviceId]) {
                $this->generateAvailableTimes($this->selectedStaff[$serviceId], $serviceId);
            }

            // Make sure the summary shows the latest selected staff, date and time
            // even if they were changed after the service was already added to the summary
            $this->calculateTotals();

            // Log the summary data after adding the service
            $this->logSummaryData($serviceId);
        }
    }

    /**
     * Remove a service from the summary panel
     */
    public function removeFromSummary($serviceId)
    {
        // Remove from consolidated summary
        $index = $this->findSummaryItemIndexByServiceId($serviceId);
        if ($index !== null) {
            // Remove the item at the specified index
            array_splice($this->summaryItems, $index, 1);
        }

        // Legacy support - keep these updated for backward compatibility
        if (isset($this->selectedServicesForSummary[$serviceId])) {
            unset($this->selectedServicesForSummary[$serviceId]);
            unset($this->summaryQuantities[$serviceId]);
            unset($this->summaryServiceDates[$serviceId]);
            $this->calculateTotals();
        }
    }

    /**
     * Find the index of a summary item by service ID
     *
     * @param int $serviceId The service ID to find
     * @return int|null The index in the summaryItems array or null if not found
     */
    private function findSummaryItemIndexByServiceId($serviceId)
    {
        foreach ($this->summaryItems as $index => $item) {
            if ($item['service_id'] == $serviceId) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Update summary item times for a specific service
     *
     * @param int $serviceId The service ID
     * @param string|null $startTime The new start time
     * @param string|null $endTime The new end time
     * @return void
     */
    private function updateSummaryItemTimes($serviceId, $startTime, $endTime)
    {
        $index = $this->findSummaryItemIndexByServiceId($serviceId);
        if ($index !== null) {
            $this->summaryItems[$index]['start_time'] = $startTime;
            $this->summaryItems[$index]['end_time'] = $endTime;
        }
    }

    /**
     * Update summary item staff for a specific service
     *
     * @param int $serviceId The service ID
     * @param int|null $staffId The staff ID
     * @return void
     */
    private function updateSummaryItemStaff($serviceId, $staffId)
    {
        $index = $this->findSummaryItemIndexByServiceId($serviceId);
        if ($index !== null) {
            $this->summaryItems[$index]['staff_id'] = $staffId;
        }
    }

    /**
     * Log the reservation summary data for debugging
     *
     * @param int|null $serviceId The ID of the service just added (optional)
     * @return void
     */
    private function logSummaryData($serviceId = null)
    {
        // Build the final summary data structure
        $summaryData = [
            'subtotal' => $this->subtotal,
            'vat_amount' => $this->vatAmount,
            'discount_amount' => $this->discountAmount,
            'grand_total' => $this->grandTotal,
            'total_duration_minutes' => $this->totalDurationMinutes,
            'added_service_id' => $serviceId,
            'summary_data' => $this->summaryItems
        ];
    }

    /**
     * Get a service from the services array by its ID
     *
     * @param int $serviceId The ID of the service to retrieve
     * @return array|null The service data or null if not found
     */
    public function getServiceById($serviceId)
    {
        foreach ($this->services as $service) {
            if ($service['id'] == $serviceId) {
                return $service;
            }
        }
        return null;
    }

    /**
     * Get the index of a service in the services array by its ID
     *
     * @param int $serviceId The ID of the service to find
     * @return int|null The array index or null if not found
     */
    private function findServiceIndexById($serviceId)
    {
        foreach ($this->services as $index => $service) {
            if ($service['id'] == $serviceId) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Find a service in the current services array by ID
     * @deprecated Use getServiceById instead
     */
    private function findServiceById($serviceId)
    {
        return $this->getServiceById($serviceId);
    }

    public function incrementServiceQuantity($serviceId)
    {
        $index = $this->findServiceIndexById($serviceId);
        if ($index === null) return;

        $this->services[$index]['selected_quantity']++;
        $this->serviceQuantities[$serviceId] = $this->services[$index]['selected_quantity'];
        $this->updateServiceTotal($serviceId);

        // Always reset time selection when quantity changes
        if (isset($this->selectedStaff[$serviceId]) && $this->selectedStaff[$serviceId]) {
            $this->generateAvailableTimes($this->selectedStaff[$serviceId], $serviceId);

            // Reset time selection regardless of validity
            $this->selectedTime[$serviceId] = null;
            $this->selectedEndTime[$serviceId] = null;

            // Update summary item times to match reset time values
            $this->updateSummaryItemTimes($serviceId, null, null);
        }
    }

    public function decrementServiceQuantity($serviceId)
    {
        $index = $this->findServiceIndexById($serviceId);
        if ($index === null) return;

        if ($this->services[$index]['selected_quantity'] > 0) {
            $this->services[$index]['selected_quantity']--;
            $this->serviceQuantities[$serviceId] = $this->services[$index]['selected_quantity'];
            $this->updateServiceTotal($serviceId);

            // Always reset time selection when quantity changes
            if (isset($this->selectedStaff[$serviceId]) && $this->selectedStaff[$serviceId]) {
                $this->generateAvailableTimes($this->selectedStaff[$serviceId], $serviceId);

                // Reset time selection regardless of validity
                $this->selectedTime[$serviceId] = null;
                $this->selectedEndTime[$serviceId] = null;

                // Update summary item times to match reset time values
                $this->updateSummaryItemTimes($serviceId, null, null);
            }
        }
    }

    public function updatedServices($value, $key)
    {
        // Extract the index from the key (format: "0.selected_quantity")
        $keyParts = explode('.', $key);

        if (count($keyParts) == 2 && $keyParts[1] == 'selected_quantity') {
            $index = $keyParts[0];

            // Get the service ID
            $serviceId = $this->services[$index]['id'];

            // Ensure quantity is non-negative
            if ($this->services[$index]['selected_quantity'] < 0) {
                $this->services[$index]['selected_quantity'] = 0;
            }

            // Update service quantities array
            $this->serviceQuantities[$serviceId] = $this->services[$index]['selected_quantity'];

            // Update service total
            $this->updateServiceTotal($serviceId);

            // Always reset time selection when quantity changes
            if (isset($this->selectedStaff[$serviceId]) && $this->selectedStaff[$serviceId]) {
                $this->generateAvailableTimes($this->selectedStaff[$serviceId], $serviceId);

                // Reset time selection regardless of validity
                $this->selectedTime[$serviceId] = null;
                $this->selectedEndTime[$serviceId] = null;

                // Update summary item times to match reset time values
                $this->updateSummaryItemTimes($serviceId, null, null);
            }
        }
    }

    private function updateServiceTotal($serviceId)
    {
        $index = $this->findServiceIndexById($serviceId);
        if ($index === null) return;

        $price = round($this->services[$index]['price'], 2);
        $quantity = $this->services[$index]['selected_quantity'];
        $this->services[$index]['total'] = round($price * $quantity, 2);
    }

    public function removeService($serviceId)
    {
        $index = $this->findServiceIndexById($serviceId);
        if ($index === null) return;

        $this->services[$index]['selected_quantity'] = 0;
        $this->serviceQuantities[$serviceId] = 0;
        $this->updateServiceTotal($serviceId);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->totalDurationMinutes = 0;

        // First calculate subtotal and duration
        foreach ($this->summaryItems as $item) {
            $this->subtotal += $item['total'];
            $this->totalDurationMinutes += $item['duration'] * $item['quantity'];
        }

        // Round the subtotal to 2 decimal places
        $this->subtotal = round($this->subtotal, 2);

        // Store the original subtotal
        $originalSubtotal = $this->subtotal;

        // Calculate discount if available
        if ($this->discount) {
            if ($this->discount['type'] === 'percentage') {
                $this->discountAmount = round($originalSubtotal * ($this->discount['value'] / 100), 2);
            } else {
                $this->discountAmount = round(min($this->discount['value'], $originalSubtotal), 2);
            }
        } else {
            $this->discountAmount = 0;
        }

        // Calculate the subtotal after discount code
        $discountedSubtotal = $originalSubtotal - $this->discountAmount;

        // Apply additional discount if it's enabled
        if ($this->otherTotalDiscountApplied) {
            // Ensure discount value is numeric
            $discountValue = !empty($this->otherTotalDiscount) && is_numeric($this->otherTotalDiscount)
                ? floatval($this->otherTotalDiscount)
                : 0;

            if ($this->otherTotalDiscountType === 'percentage') {
                $this->otherTotalDiscountAmount = round($discountedSubtotal * ($discountValue / 100), 2);
            } else {
                // For fixed amount, cap at the subtotal after code discount
                $this->otherTotalDiscountAmount = min($discountedSubtotal, round($discountValue / 1.15, 2));
            }

            // Apply the additional discount
            $discountedSubtotal -= $this->otherTotalDiscountAmount;
        } else {
            $this->otherTotalDiscountAmount = 0;
        }

        // Set fixed VAT rate of 15%
        $this->vatRate = 15;

        // Reset tax amounts
        $this->vatAmount = 0;
        $this->otherTaxesAmount = 0;

        // Apply VAT directly on the discounted subtotal (after both global and per-service "other" discounts)
        // This ensures VAT is calculated on the final amount after all discounts
        $this->vatAmount = round(($discountedSubtotal * $this->vatRate) / 100, 2);

        // Calculate any other taxes if needed
        // For now, we'll set other taxes to 0 since we're applying VAT directly
        $this->otherTaxesAmount = 0;

        // Calculate end time
        $this->calculateEndTime();

        // Calculate grand total: Discounted Subtotal + VAT + Other Taxes
        $this->grandTotal = round($discountedSubtotal + $this->vatAmount + $this->otherTaxesAmount, 2);

        // Calculate change given
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $totalPaid = (float) ($this->totalPaidCash + $this->totalPaidOnline);
        $this->changeGiven = round($totalPaid - $this->grandTotal, 2);
    }

    public function updatedTotalPaidCash()
    {
        $this->totalPaidCash = round(max(0, (float) $this->totalPaidCash), 2);
        $this->calculateChange();
    }

    public function updatedTotalPaidOnline()
    {
        $this->totalPaidOnline = round(max(0, (float) $this->totalPaidOnline), 2);
        $this->calculateChange();
    }

    public function checkDiscountCode()
    {
        if (empty(trim($this->discountCode))) {
            $this->addError('discountCode', __('Please enter a discount code.'));
            return;
        }

        try {
            // Find the discount with the given code
            $discount = \App\Models\Discount::where('code', $this->discountCode)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where(function ($query) {
                    $query->where('maximum_uses', 0)
                        ->orWhereRaw('times_used < maximum_uses');
                })
                ->where('given_to', 'any_one')
                ->first();

            // If no discount found, return error
            if (!$discount) {
                $this->addError('discountCode', __('Invalid or expired discount code.'));
                return;
            }

            // Check if the discount applies to the current point of sale
            $posHasDiscount = $discount->pointOfSales()
                ->where('point_of_sale_id', $this->pointOfSaleId)
                ->exists();

            if (!$posHasDiscount) {
                $this->addError('discountCode', __('This discount is not available for this point of sale.'));
                return;
            }

            // Check if the minimum order amount is met
            if ($discount->minimum_order_amount > $this->subtotal) {
                $this->addError('discountCode', __('The minimum order amount for this discount is :amount SAR.', ['amount' => $discount->minimum_order_amount]));
                return;
            }

            // Format discount for use in calculation
            $this->discount = [
                'id' => $discount->id,
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => (float) $discount->amount,
                'name_en' => $discount->name,
                'name_ar' => $discount->name_ar,
                'description_en' => $discount->description_en,
                'description_ar' => $discount->description_ar,
            ];

            // Calculate discounted amount
            $this->calculateTotals();
            session()->flash('message', __('Discount code applied successfully.'));

            // Clear any previous discountCode errors
            $this->resetErrorBag('discountCode');
        } catch (\Exception $e) {
            $this->addError('discountCode', __('Error checking discount code. Please try again.'));
            \Illuminate\Support\Facades\Log::error('Error checking discount code: ' . $e->getMessage());
        }
    }

    public function removeDiscount()
    {
        $this->discount = null;
        $this->discountCode = '';
        $this->discountAmount = 0;
        // Clear any discountCode error messages
        $this->resetErrorBag('discountCode');
        $this->calculateTotals();
    }

    public function confirmReservation()
    {
        // Validate required fields
        $this->validate([
            'pointOfSaleId' => 'required',
            'reservationDate' => 'required|date',
            'startTime' => 'required',
            'totalPaidCash' => 'required|numeric|min:0',
            'totalPaidOnline' => 'required|numeric|min:0',
        ]);

        // Check if any services have been added to the summary
        if (empty($this->summaryItems)) {
            $this->addError('services', __('Please add at least one service to the summary.'));
            return;
        }

        // Check if any time selections are missing for staff-assigned services
        $missingTimeSelection = false;
        foreach ($this->summaryItems as $item) {
            if ($item['staff_id'] && empty($item['start_time'])) {
                $missingTimeSelection = true;
                break;
            }
        }

        if ($missingTimeSelection) {
            $this->addError('services', __('Please select a time for each service with a selected staff.'));
            return;
        }

        // Check if total paid amount is sufficient
        $totalPaid = $this->totalPaidCash + $this->totalPaidOnline;
        if ($totalPaid < $this->grandTotal) {
            $this->addError('payment', __('Total paid amount is less than the grand total. Please collect the remaining amount.'));
            return;
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Generate a unique invoice number for the main invoice
            $mainInvoiceNumber = generateUniqueInvoiceNumber();

            // Create the main reservation record
            $reservationData = [
                'point_of_sale_id' => $this->pointOfSaleId,
                'reservation_date' => $this->appointmentDate ?? $this->reservationDate, // Use selected appointment date
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'subtotal' => $this->subtotal,
                'vat_amount' => $this->vatAmount,
                'other_taxes_amount' => $this->otherTaxesAmount, // Add other taxes amount
                'total_price' => $this->grandTotal,
                'discount_amount' => $this->discountAmount ?: 0,
                'discount_code' => $this->discount ? $this->discount['code'] : null, // Use discount code from the validated discount array
                'discount_id' => $this->discount ? $this->discount['id'] : null, // Store the discount_id for relationship
                'other_total_discount_amount' => $this->otherTotalDiscountAmount ?: 0,
                'other_total_discount_value' => $this->otherTotalDiscount ?: 0,
                'other_total_discount_type' => $this->otherTotalDiscountApplied ? $this->otherTotalDiscountType : null,
                'location_type' => $this->locationType,
                'total_duration_minutes' => $this->totalDurationMinutes,
                'status' => 'confirmed',
                'notes' => $this->notes,
                'total_paid_cash' => $this->totalPaidCash ?: 0,
                'total_paid_online' => $this->totalPaidOnline ?: 0,
                // Set customer_id to null to avoid foreign key constraint issues
                'customer_id' => null,
                // Store customer details in the customer_detail JSON field if user is authenticated
                'customer_detail' => Auth::check() ? json_encode([
                    'name_en' => "Guest at " . PointOfSale::find($this->pointOfSaleId)->name_en,
                    'name_ar' => "زائر في " . PointOfSale::find($this->pointOfSaleId)->name_ar,
                    'added_by' => Auth::user()->name,
                ]) : null,
            ];

            $reservation = BookedReservation::create($reservationData);

            // Create duplicate record in invoices table with same data plus invoice number
            $invoiceData = array_merge($reservationData, [
                'invoice_number' => $mainInvoiceNumber,
                'booked_reservation_id' => $reservation->id // Store the booked_reservation_id
            ]);
            $invoice = \App\Models\Invoice::create($invoiceData);

            // Now create BookedReservationItem records for each selected service
            foreach ($this->summaryItems as $item) {
                // Get service ID and additional information
                $serviceId = $item['service_id'];
                $staffId = $item['staff_id'];
                $selectedTime = $item['start_time'];
                $selectedEndTime = $item['end_time'];
                $appointmentDate = $item['appointment_date'];
                $locationTypeForItem = $item['location_type'] ?? $this->locationType; // Get item-specific location type or fallback to global

                // Convert time from 12-hour to 24-hour format for database
                $dbStartTime = $this->convertTimeFormat($selectedTime);
                $dbEndTime = $this->convertTimeFormat($selectedEndTime);

                // Prepare staff details in case the staff record is deleted later
                $staffDetail = null;
                if ($staffId) {
                    // Find the staff member in the available staff collection
                    $staffMember = null;
                    foreach ($this->availableStaff as $staff) {
                        if (isset($staff['id']) && $staff['id'] == $staffId) {
                            $staffMember = $this->normalizeStaffData($staff);
                            break;
                        }
                    }

                    if ($staffMember) {
                        $staffDetail = json_encode([
                            'id' => $staffMember['id'],
                            'name_en' => $staffMember['name_en'] ?? $staffMember['name'] ?? 'Unknown',
                            'name_ar' => $staffMember['name_ar'] ?? $staffMember['name'] ?? 'Unknown'
                        ]);
                    }
                }

                // Create item data
                $itemPrice = $item['price'];
                $itemPriceAfterDiscount = $itemPrice - ($item['other_discount_amount'] / $item['quantity']) ?? 0;
                $itemData = [
                    'product_and_service_id' => $serviceId,
                    'name_en' => $item['service_name_en'],
                    'name_ar' => $item['service_name_ar'],
                    'price' => $itemPrice,
                    'price_after_discount' => $itemPriceAfterDiscount,
                    'duration' => $item['duration'],
                    'quantity' => $item['quantity'],
                    'other_discount_value' => $item['other_discount_value'] ?? 0,
                    'other_discount_type' => $item['other_discount_type'] ?? 'percentage',
                    'other_discount_amount' => $item['other_discount_amount'] ?? 0,
                    'appointment_date' => $appointmentDate,
                    'start_time' => $dbStartTime, // Use converted time format
                    'end_time' => $dbEndTime,     // Use converted time format
                    'staff_id' => $staffId,
                    'staff_detail' => $staffDetail,
                    'location_type' => $locationTypeForItem, // Store the location type for this specific item
                    'service_location' => $locationTypeForItem, // Store the location type for this specific item
                ];

                $productService = ProductAndService::find($serviceId);
                // Calculate and store the VAT and other taxes for this item
                if ($productService) {
                    // Calculate VAT and other taxes based on quantity
                    $vatPercentage = $productService->getVatPercentage($locationTypeForItem);
                    $otherTaxesPercentage = $productService->getOtherTaxesPercentage($locationTypeForItem);
                    $itemSubtotal = $itemPriceAfterDiscount * $item['quantity'];
                    $itemVatAmount = $itemPriceAfterDiscount * ($vatPercentage / 100) * $item['quantity'];
                    $itemOtherTaxesAmount = $itemPriceAfterDiscount * ($otherTaxesPercentage / 100) * $item['quantity'];
                    $itemTotal = $itemSubtotal + $itemVatAmount + $itemOtherTaxesAmount;
                    $itemData['subtotal'] = $itemSubtotal;
                    $itemData['vat_amount'] = $itemVatAmount;
                    $itemData['other_taxes_amount'] = $itemOtherTaxesAmount;
                    $itemData['total'] = $itemTotal;
                } else {
                    // Default to zero if product not found
                    $itemData['subtotal'] = $itemPrice * $item['quantity'];
                    $itemData['vat_amount'] = 0;
                    $itemData['other_taxes_amount'] = 0;
                    $itemData['total'] = $item['total'];
                }

                // Create the reservation item
                $reservationItem = $reservation->items()->create($itemData);

                // Generate a unique invoice number for each invoice item
                $itemInvoiceNumber = generateUniqueInvoiceItemNumber($mainInvoiceNumber);

                // Create duplicate record in invoice_items table
                $invoiceItemData = array_merge($itemData, [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $itemInvoiceNumber // Use unique invoice number for each item
                ]);

                // Remove booked_reservation_id if it was added by the relationship
                if (isset($invoiceItemData['booked_reservation_id'])) {
                    unset($invoiceItemData['booked_reservation_id']);
                }

                // Create the invoice item
                $invoiceItem = \App\Models\InvoiceItem::create($invoiceItemData);

                // Create tickets based on quantity
                for ($i = 1; $i <= $item['quantity']; $i++) {
                    \App\Models\Ticket::create([
                        'code' => $invoiceItemData['invoice_number'] . '' . $i,
                        'invoice_item_id' => $invoiceItem->id,
                        'ticket_status_id' => 1, // Default status
                        'status_updated_at' => now(),
                        'point_of_sale_id' => $this->pointOfSaleId,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Increment discount usage if a discount was applied
            if ($this->discount && isset($this->discount['id'])) {
                try {
                    $discount = \App\Models\Discount::find($this->discount['id']);
                    if ($discount) {
                        $discount->incrementUsed();
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error updating discount usage: ' . $e->getMessage());
                    // Don't fail the whole transaction because of this
                }
            }

            if ($reservation) {
                $this->reservationConfirmed = true;
                $this->reservationId = $reservation->id;
                session()->flash('success', __('Reservation created successfully!'));

                // Automatically trigger the print function after successful reservation
                $this->dispatch('autoPrintInvoice', ['id' => $reservation->id, 'url' => route('reservations.invoice', ['id' => $reservation->id])]);
            } else {
                $this->addError('general', __('Failed to create reservation. Please try again.'));
                \Illuminate\Support\Facades\Log::error('Reservation creation failed: No reservation object returned');
            }
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            \Illuminate\Support\Facades\Log::error('Error creating reservation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Add a more descriptive error message to help with debugging
            $errorMessage = 'An error occurred while creating the reservation: ' . $e->getMessage();
            $this->addError('general', __($errorMessage));

            // Log to console without outputting HTML directly
            // This avoids the JSON parse error when Livewire tries to process the response
            if (config('app.debug')) {
                \Illuminate\Support\Facades\Log::debug('Error creating reservation: ' . $e->getMessage());
            }
        }
    }



    public function newReservation()
    {
        $this->reservationConfirmed = false;
        $this->reservationId = null;

        // Store current point of sale ID to preserve it
        $currentPosId = $this->pointOfSaleId;
        $isPosUser = $this->isPosUser;

        // Reset all form fields
        $this->reset([
            'startTime',
            'endTime',
            'locationType',
            'notes',
            'discountCode',
            'discount',
            'discountAmount',
            'otherTotalDiscount',
            'otherTotalDiscountType',
            'otherTotalDiscountApplied',
            'otherTotalDiscountAmount',
            'totalPaidCash',
            'totalPaidOnline',
            'changeGiven',
            'serviceQuantities',
            'summaryQuantities',     // Legacy - Reset summary quantities
            'summaryServiceDates',   // Legacy - Reset stored dates for summary services
            'services',
            'subtotal',
            'vatAmount',
            'grandTotal',
            'totalDurationMinutes',
            'selectedStaff',         // Legacy - Reset selected staff
            'staffWorkingHours',     // Legacy - Reset staff working hours
            'selectedTime',          // Legacy - Reset selected time
            'availableTimes',        // Reset available times
            'selectedServicesForSummary', // Legacy - Reset services in summary
            'summaryItems'           // Reset the new consolidated summary
        ]);

        // Set default values
        $today = date('Y-m-d');
        $this->reservationDate = $today;
        $this->appointmentDate = $today; // Set appointmentDate to today as well
        $this->locationType = 'salon';
        $this->startTime = Carbon::now()->format('H:i');
        $this->endTime = $this->startTime;

        // Dispatch event to notify date selector that date has been selected
        $this->dispatch('dateSelected', $today);
        // Also dispatch dateChanged to trigger loadAvailableStaff
        $this->dispatch('dateChanged');

        // Restore point of sale ID
        if ($isPosUser) {
            $this->pointOfSaleId = $currentPosId;
        }

        // Reload data if POS is selected
        if ($this->pointOfSaleId) {
            $this->loadServiceCategories();
            $this->calculateTotals();
        }
    }

    public function getAllSelectedServices()
    {
        $allSelectedServices = [];

        try {
            // Only proceed if a point of sale is selected
            if (!$this->pointOfSaleId) {
                return [];
            }

            // Get all service categories for the selected point of sale
            $categories = ServiceCategory::where('is_active', true)
                ->where('point_of_sale_id', $this->pointOfSaleId)
                ->orderBy('sort_order')
                ->get();

            // For each category, get the selected services
            foreach ($categories as $category) {
                $selectedServices = [];

                // Get services for this category
                $query = ProductAndService::where('category_id', $category->id)
                    ->where('is_active', true);

                // Filter by location type
                if ($this->locationType === 'home') {
                    $query->where(function ($q) {
                        $q->where('is_product', true)
                            ->orWhere('can_be_done_at_home', true);
                    });
                }

                $services = $query->get();

                // Check which services are selected
                foreach ($services as $service) {
                    $quantity = $this->serviceQuantities[$service->id] ?? 0;
                    $summaryQuantity = $this->summaryQuantities[$service->id] ?? 0;

                    // Only include if it has been added to the summary
                    if ($summaryQuantity > 0 && isset($this->selectedServicesForSummary[$service->id])) {
                        $price = $this->locationType === 'home' && $service->price_home ?
                            $service->price_home :
                            $service->price;

                        // Get staff information
                        $staffId = $this->selectedStaff[$service->id] ?? null;
                        $staffInfo = null;
                        if ($staffId) {
                            foreach ($this->availableStaff as $staff) {
                                if (isset($staff['id']) && $staff['id'] == $staffId) {
                                    $staffInfo = $this->normalizeStaffData($staff);
                                    break;
                                }
                            }
                        }

                        // Get time information
                        $selectedTime = $this->selectedTime[$service->id] ?? null;
                        $selectedEndTime = $this->selectedEndTime[$service->id] ?? null;

                        // Get date information - use stored date if available
                        $appointmentDate = $this->summaryServiceDates[$service->id] ?? $this->appointmentDate;

                        $selectedServices[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'price' => $price,
                            'duration' => $service->duration_minutes ?: 0,
                            'selected_quantity' => $summaryQuantity,
                            'total' => $price * $summaryQuantity,
                            'category_id' => $category->id,
                            'category_name' => $category->name,
                            'staff' => $staffInfo,
                            'time' => $selectedTime,
                            'end_time' => $selectedEndTime,
                            'appointment_date' => $appointmentDate
                        ];
                    }
                }

                // Add to the result if any services were selected in this category
                if (count($selectedServices) > 0) {
                    $allSelectedServices[$category->name] = $selectedServices;
                }
            }

            return $allSelectedServices;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting all selected services: ' . $e->getMessage());
            return [];
        }
    }

    public function updateServiceQuantity($serviceId, $quantity)
    {
        $this->serviceQuantities[$serviceId] = max(0, (int)$quantity);

        // Update service total if it's currently visible
        foreach ($this->services as $index => $service) {
            if ($service['id'] == $serviceId) {
                $this->services[$index]['selected_quantity'] = $this->serviceQuantities[$serviceId];
                $this->updateServiceTotal($serviceId);
                break;
            }
        }

        // Update the quantity in summaryItems if this service is in the summary
        $index = $this->findSummaryItemIndexByServiceId($serviceId);
        if ($index !== null) {
            $quantity = max(0, (int)$quantity);
            $this->summaryItems[$index]['quantity'] = $quantity;
            // Ensure price is properly rounded
            $price = round($this->summaryItems[$index]['price'], 2);
            $this->summaryItems[$index]['total'] = round($price * $quantity, 2);

            // Update legacy summary quantities for backward compatibility
            $this->summaryQuantities[$serviceId] = $quantity;
        }

        // Always reset time selection when quantity changes
        if (isset($this->selectedStaff[$serviceId]) && $this->selectedStaff[$serviceId]) {
            $this->generateAvailableTimes($this->selectedStaff[$serviceId], $serviceId);

            // Reset time selection regardless of validity
            $this->selectedTime[$serviceId] = null;
            $this->selectedEndTime[$serviceId] = null;

            // Update summary item times to match reset time values
            $this->updateSummaryItemTimes($serviceId, null, null);
        }

        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.reservation-quick-create');
    }

    // Method to allow child components to get the point of sale ID
    public function getPointOfSaleId()
    {
        return $this->pointOfSaleId;
    }

    public function onDateSelected($date)
    {
        // Store the previous appointment date to check if it changed
        $previousDate = $this->appointmentDate;

        // Set the new appointment date
        $this->appointmentDate = $date;

        // If the date changed and there are services in the summary,
        // we'll keep the previous date for them unless explicitly updated
        if ($previousDate != $date && !empty($this->selectedServicesForSummary)) {
            // The summary will keep displaying the previous date for already added services
            // The new date will be used when adding new services or updating existing ones
        }

        // Reload services for the active category with the new date
        if ($this->activeServiceCategoryId) {
            $this->loadProductsAndServicesByCategory($this->activeServiceCategoryId);
        }

        // Recalculate totals since prices or availability may have changed
        $this->calculateTotals();
    }

    /**
     * Helper function to ensure staff data is consistently handled as an array
     *
     * @param mixed $staffData Staff data that could be object or array
     * @return array Normalized staff data as array
     */
    private function normalizeStaffData($staffData)
    {
        if (is_array($staffData)) {
            return [
                'id' => $staffData['id'] ?? null,
                'name' => $staffData['name'] ?? $staffData['name_en'] ?? 'Unknown',
                'name_en' => $staffData['name_en'] ?? $staffData['name'] ?? 'Unknown'
            ];
        } else if (is_object($staffData)) {
            return [
                'id' => $staffData->id ?? null,
                'name' => $staffData->name ?? $staffData->name_en ?? 'Unknown',
                'name_en' => $staffData->name_en ?? $staffData->name ?? 'Unknown'
            ];
        }

        return [
            'id' => null,
            'name' => 'Unknown',
            'name_en' => 'Unknown'
        ];
    }

    public function onStaffAvailable($staff)
    {
        // Convert staff objects to arrays to ensure consistent handling
        $this->availableStaff = [];
        foreach (collect($staff) as $staffMember) {
            $normalizedStaff = $this->normalizeStaffData($staffMember);
            $this->availableStaff[$normalizedStaff['id']] = $normalizedStaff;
        }
    }

    /**
     * Get staff members who can provide a specific service
     *
     * @param int $serviceId The service ID to filter staff by
     * @return array Filtered staff array
     */
    public function getAvailableStaffForService($serviceId)
    {
        $filteredStaff = [];

        if (empty($this->availableStaff)) {
            return $filteredStaff;
        }

        // Get the service to check which staff can provide it
        $service = ProductAndService::find($serviceId);
        if (!$service) {
            return $filteredStaff;
        }

        // Get IDs of staff who can provide this service
        $staffIds = $service->staff()
            ->whereHas('pointOfSale', function ($query) {
                $query->where('point_of_sales.id', $this->pointOfSaleId);
            })
            ->where('is_active', true)
            ->pluck('staff.id')
            ->toArray();

        // Filter the available staff to only include those who can provide this service
        foreach ($this->availableStaff as $staffId => $staffMember) {
            if (in_array($staffId, $staffIds)) {
                $filteredStaff[$staffId] = $staffMember;
            }
        }

        return $filteredStaff;
    }

    public function selectStaff($serviceId, $staffId)
    {

        $index = $this->findServiceIndexById($serviceId);
        if ($index === null) return;

        // Verify that this staff can provide this service
        $service = ProductAndService::find($serviceId);
        if ($service) {
            $canProvideService = $service->staff()
                ->where('staff.id', $staffId)
                ->exists();

            if (!$canProvideService) {
                // Staff cannot provide this service, don't allow selection
                return;
            }
        }

        // Legacy support
        if (!isset($this->selectedStaff[$serviceId])) {
            $this->selectedStaff[$serviceId] = [];
        }
        $this->selectedStaff[$serviceId] = $staffId;

        // Only reset time selection if this service is not in the summary
        if (!isset($this->selectedServicesForSummary[$serviceId])) {
            $this->selectedTime[$serviceId] = null;
            $this->selectedEndTime[$serviceId] = null;
        }

        // Update the consolidated summary if this service is in it
        $this->updateSummaryItemStaff($serviceId, $staffId);

        // Reset times if they were set previously and not in summary
        if (!isset($this->selectedServicesForSummary[$serviceId])) {
            $this->updateSummaryItemTimes($serviceId, null, null);
        }

        // Get bookings for this staff member on the selected date
        $this->getStaffBookings($staffId);

        // Ensure working hours are loaded for this staff member
        if (!isset($this->staffWorkingHours[$staffId])) {

            $this->dispatch('staffSelected', $staffId, $serviceId);
        } else {

            $this->generateAvailableTimes($staffId, $serviceId);
        }

        // Store quantity for this service - important when switching categories
        if (!isset($this->serviceQuantities[$serviceId]) || $this->serviceQuantities[$serviceId] <= 0) {
            $this->serviceQuantities[$serviceId] = 1;
            // Update the displayed quantity if this service is currently visible
            if ($index !== null) {
                $this->services[$index]['selected_quantity'] = 1;
                $this->updateServiceTotal($serviceId);
            }
        }
    }

    public function onStaffWorkingHoursLoaded($workingHours)
    {

        if (isset($workingHours['staff_id'])) {
            $staffId = $workingHours['staff_id'];
            $serviceId = $workingHours['service_id'] ?? null;

            $this->staffWorkingHours[$staffId] = $workingHours;

            if ($serviceId) {
                // Pass the service ID explicitly to ensure it's not lost
                $this->generateAvailableTimes($staffId, $serviceId);
            } else {
                // If we don't have a service ID, try to find one from selectedStaff
                foreach ($this->selectedStaff as $svcId => $stfId) {
                    if ($stfId == $staffId) {
                        Log::info('Found service ID for staff from selectedStaff', [
                            'staff_id' => $staffId,
                            'service_id' => $svcId
                        ]);
                        $this->generateAvailableTimes($staffId, $svcId);
                        return; // Exit after finding the first match
                    }
                }

                // If no service ID found, log and call without it
                Log::warning('No service ID found for staff', [
                    'staff_id' => $staffId
                ]);
                $this->generateAvailableTimes($staffId);
            }
        } else {
            Log::warning('Received working hours without staff_id', [
                'workingHours' => $workingHours
            ]);
        }
    }

    public function resetStaffSelection()
    {
        // Only reset staff and time selections for services NOT in the summary
        foreach ($this->selectedStaff as $serviceId => $staffId) {
            if (!isset($this->selectedServicesForSummary[$serviceId])) {
                unset($this->selectedStaff[$serviceId]);
                unset($this->selectedTime[$serviceId]);
                unset($this->selectedEndTime[$serviceId]);
            }
        }

        // Reset working hours and available times
        $this->staffWorkingHours = [];
        $this->availableTimes = [];

        // For services in the summary, regenerate their available times
        foreach ($this->selectedServicesForSummary as $serviceId => $value) {
            if (isset($this->selectedStaff[$serviceId])) {
                $staffId = $this->selectedStaff[$serviceId];
                // Dispatch event to load working hours for this staff
                $this->dispatch('staffSelected', $staffId, $serviceId);
            }
        }
    }

    public function selectTime($serviceId, $time)
    {
        if (!$time) {
            // If no time is selected, reset both start and end times
            $this->selectedTime[$serviceId] = null;
            $this->selectedEndTime[$serviceId] = null;

            // Update the consolidated summary as well
            $this->updateSummaryItemTimes($serviceId, null, null);

            return;
        }

        $service = $this->getServiceById($serviceId);
        if (!$service) return;

        $duration = $service['duration'];
        $quantity = $service['selected_quantity'];
        $totalDuration = $duration * $quantity;

        // Set the selected start time
        $this->selectedTime[$serviceId] = $time;

        // Calculate and store the end time
        $startTime = Carbon::parse($time);
        $endTime = $startTime->copy()->addMinutes($totalDuration);
        $endTimeFormatted = $endTime->format('h:i A');
        $this->selectedEndTime[$serviceId] = $endTimeFormatted;

        // Update the consolidated summary as well
        $this->updateSummaryItemTimes($serviceId, $time, $endTimeFormatted);
    }

    protected function generateAvailableTimes($staffId, $serviceId = null)
    {

        if (!isset($this->staffWorkingHours[$staffId])) {
            Log::warning('No staff working hours found', [
                'staff_id' => $staffId,
                'service_id' => $serviceId,
                'appointment_date' => $this->appointmentDate
            ]);
            $this->availableTimes = [];
            return;
        }

        // If no specific serviceId is provided, try to find one
        if ($serviceId === null) {
            // Find all services using this staff member
            $serviceIds = [];
            foreach ($this->selectedStaff as $svcId => $stfId) {
                if ($stfId == $staffId) {
                    $serviceIds[] = $svcId;
                    Log::info('Found service for staff', [
                        'staff_id' => $staffId,
                        'service_id' => $svcId
                    ]);
                }
            }

            // If no service is using this staff, return empty times
            if (empty($serviceIds)) {
                Log::warning('No services found using this staff', [
                    'staff_id' => $staffId
                ]);
                $this->availableTimes = [];
                return;
            }

            // Use the first service ID found
            $serviceId = $serviceIds[0];
        }

        // Find the service details (duration, quantity)
        $serviceDuration = 0;
        $serviceQuantity = 0;
        $serviceName = 'Unknown Service';

        // First try to find the service in the currently visible services
        $serviceFound = false;
        foreach ($this->services as $service) {
            if ($service['id'] == $serviceId) {
                $serviceDuration = $service['duration'];
                $serviceQuantity = $service['selected_quantity'];
                $serviceName = $service['name'] ?? $service['name_en'] ?? 'Unknown Service';
                $serviceFound = true;
                break;
            }
        }

        // If the service is not in the current view (different category),
        // try to get its details from the database
        if (!$serviceFound) {
            try {
                $service = ProductAndService::find($serviceId);
                if ($service) {
                    $serviceDuration = $service->duration_minutes ?: 0;
                    $serviceQuantity = $this->serviceQuantities[$serviceId] ?? 0;
                    $serviceName = $service->name ?? $service->name_en ?? 'Unknown Service';
                    Log::info('Found service from database', [
                        'service_id' => $serviceId,
                        'service_name' => $serviceName,
                        'duration' => $serviceDuration,
                        'quantity' => $serviceQuantity
                    ]);
                } else {
                    Log::warning('Service not found in database', [
                        'service_id' => $serviceId
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error fetching service details: ' . $e->getMessage(), [
                    'service_id' => $serviceId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Calculate total duration needed
        $totalDurationNeeded = $serviceDuration * $serviceQuantity;

        // If no duration or quantity, return empty times
        if ($totalDurationNeeded <= 0) {
            Log::warning('Invalid duration or quantity', [
                'service_id' => $serviceId,
                'service_name' => $serviceName,
                'staff_id' => $staffId,
                'duration' => $serviceDuration,
                'quantity' => $serviceQuantity,
                'total_duration' => $totalDurationNeeded
            ]);
            $this->availableTimes = [];
            return;
        }

        $workingHours = $this->staffWorkingHours[$staffId];
        $startTime = Carbon::parse($workingHours['start_time']);
        $endTime = Carbon::parse($workingHours['end_time']);

        // Check if selected date is today
        $isToday = Carbon::parse($this->appointmentDate)->isToday();

        // If it's today, set start time to current time if current time is after staff start time
        if ($isToday) {
            $currentTime = Carbon::now();
            // Round up to the next 15-minute interval
            $minutes = $currentTime->minute;
            $roundedMinutes = ceil($minutes / 15) * 15;
            $currentTime->setMinute($roundedMinutes)->setSecond(0);

            // If current time is after working start time, use current time instead
            if ($currentTime->gt($startTime)) {
                $startTime = $currentTime;
                \Illuminate\Support\Facades\Log::info('Using current time as start time', [
                    'current_time' => $currentTime->format('h:i A')
                ]);
            }
        }

        // Calculate the latest possible start time
        // This is the end time minus the total duration needed
        $latestStartTime = $endTime->copy()->subMinutes($totalDurationNeeded);

        // If the latest start time is before the start time, no slots are available
        if ($latestStartTime->lt($startTime)) {
            \Illuminate\Support\Facades\Log::warning('No available time slots - service duration too long for remaining time', [
                'service_id' => $serviceId,
                'service_name' => $serviceName,
                'staff_id' => $staffId,
                'start_time' => $startTime->format('h:i A'),
                'end_time' => $endTime->format('h:i A'),
                'total_duration_minutes' => $totalDurationNeeded,
                'latest_start_time' => $latestStartTime->format('h:i A')
            ]);
            $this->availableTimes = [];
            return;
        }

        // Generate time slots at 15-minute intervals up to the latest possible start time
        $availableTimes = [];
        $currentTime = $startTime->copy();

        while ($currentTime->lte($latestStartTime)) {
            // Calculate the end time for this potential slot
            $slotEndTime = $currentTime->copy()->addMinutes($totalDurationNeeded);

            // Only include this slot if its end time doesn't exceed the staff's end time
            if ($slotEndTime <= $endTime) {
                $availableTimes[] = $currentTime->format('h:i A');
            }

            $currentTime->addMinutes(15);
        }

        // Get existing bookings for this staff member on this day
        $existingBookings = BookedReservationItem::whereHas('bookedReservation', function ($query) {
            $query->where('status', '!=', 'cancelled');
        })
            ->where('staff_id', $staffId)
            ->where('appointment_date', $this->appointmentDate)
            ->select('start_time', 'end_time', 'duration')
            ->get();

        // Store staff bookings for display in the UI
        $this->getStaffBookings($staffId);

        // If there are existing bookings, filter out the conflicting time slots
        if ($existingBookings->count() > 0) {
            \Illuminate\Support\Facades\Log::info('Found existing bookings', [
                'staff_id' => $staffId,
                'date' => $this->appointmentDate,
                'booking_count' => $existingBookings->count()
            ]);

            $originalCount = count($availableTimes);
            $filteredTimes = [];
            $conflictCount = 0;

            foreach ($availableTimes as $timeSlot) {
                $slotStartTime = Carbon::parse($timeSlot);
                $slotEndTime = $slotStartTime->copy()->addMinutes($totalDurationNeeded);

                $isConflict = false;

                foreach ($existingBookings as $booking) {
                    $bookingStartTime = Carbon::parse($booking->start_time);
                    $bookingEndTime = Carbon::parse($booking->end_time);

                    // Check if this time slot overlaps with any existing booking
                    if (
                        ($slotStartTime >= $bookingStartTime && $slotStartTime < $bookingEndTime) ||
                        ($slotEndTime > $bookingStartTime && $slotEndTime <= $bookingEndTime) ||
                        ($slotStartTime <= $bookingStartTime && $slotEndTime >= $bookingEndTime)
                    ) {
                        $isConflict = true;
                        $conflictCount++;
                        break;
                    }
                }

                if (!$isConflict) {
                    $filteredTimes[] = $timeSlot;
                }
            }

            $availableTimes = $filteredTimes;

            // Log if all time slots were filtered out due to conflicts
            if (count($availableTimes) === 0 && $originalCount > 0) {
                \Illuminate\Support\Facades\Log::warning('All time slots filtered due to booking conflicts', [
                    'staff_id' => $staffId,
                    'service_id' => $serviceId,
                    'service_name' => $serviceName,
                    'original_slots' => $originalCount,
                    'conflicts' => $conflictCount
                ]);
            }
        }

        // Get staff name from availableStaff array
        $staffName = 'Unknown Staff';
        if (isset($this->availableStaff[$staffId])) {
            $staffName = $this->availableStaff[$staffId]['name'] ?? $this->availableStaff[$staffId]['name_en'] ?? 'Unknown Staff';
        }

        $this->availableTimes = $availableTimes;
    }

    /**
     * Helper function to convert time from 12-hour format (like "12:00 PM") to 24-hour format (like "12:00:00")
     * for database compatibility
     *
     * @param string|null $timeString Time in 12-hour format (e.g., "12:00 PM")
     * @return string|null Time in 24-hour format (e.g., "12:00:00") or null if input is null
     */
    private function convertTimeFormat($timeString)
    {
        if (empty($timeString)) {
            return null;
        }

        try {
            // Parse the time string using Carbon
            $time = Carbon::parse($timeString);
            // Return in 24-hour format with seconds
            return $time->format('H:i:s');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error converting time format: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all bookings for a specific staff member on the selected date
     *
     * @param int $staffId The ID of the staff member
     * @return void
     */
    public function getStaffBookings($staffId)
    {
        try {
            // Get bookings for this staff member on the selected date
            $bookings = BookedReservationItem::whereHas('bookedReservation', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
                ->where('staff_id', $staffId)
                ->where('appointment_date', $this->appointmentDate)
                ->orderBy('start_time')
                ->get(['id', 'start_time', 'end_time', 'name_en', 'name_ar']);

            $formattedBookings = [];
            foreach ($bookings as $booking) {
                // Format times for display
                $startTime = Carbon::parse($booking->start_time)->format('h:i A');
                $endTime = Carbon::parse($booking->end_time)->format('h:i A');

                $formattedBookings[] = [
                    'id' => $booking->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'name' => $booking->name, // This will use the accessor method
                    'time_range' => "{$startTime} - {$endTime}"
                ];
            }

            $this->staffBookings[$staffId] = $formattedBookings;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting staff bookings: ' . $e->getMessage());
            $this->staffBookings[$staffId] = [];
        }
    }

    /**
     * Set the cash payment amount to match the grand total
     *
     * @return void
     */
    public function setCashToTotal()
    {
        $this->totalPaidCash = $this->grandTotal;
        $this->calculateChangeGiven();
    }

    /**
     * Update service quantity directly by service ID
     *
     * @param int $serviceId The ID of the service
     * @param int $quantity The new quantity
     * @return void
     */
    public function updateServiceQuantityDirect($serviceId, $quantity)
    {
        // Log::info("Direct update service $serviceId quantity to $quantity");
        $quantity = max(0, intval($quantity));
        $this->updateServiceQuantity($serviceId, $quantity);
    }

    /**
     * Toggle between percentage and fixed discount types for a service
     */
    public function toggleDiscountType($serviceId)
    {
        // Initialize to percentage if not set
        if (!isset($this->otherDiscountType[$serviceId])) {
            $this->otherDiscountType[$serviceId] = 'percentage';
        }

        // Toggle between percentage and fixed
        $this->otherDiscountType[$serviceId] = ($this->otherDiscountType[$serviceId] === 'percentage') ? 'fixed' : 'percentage';

        // Emit event to update UI
        $this->dispatch('discountTypeUpdated', [
            'serviceId' => $serviceId,
            'type' => $this->otherDiscountType[$serviceId]
        ]);
    }

    /**
     * Determine if time selection is required for a service
     * Time is required if staff is selected but time is not.
     *
     * @param int $serviceId The service ID to check
     * @return bool True if time selection is required
     */
    public function isTimeSelectionRequired($serviceId)
    {
        $staffId = $this->selectedStaff[$serviceId] ?? null;
        $startTime = $this->selectedTime[$serviceId] ?? null;

        // If staff is selected but no time is selected, time selection is required
        return ($staffId && !$startTime);
    }

    public function updatedSearchQuery()
    {
        // Reload services with the current category and new search query
        if ($this->activeServiceCategoryId) {
            $this->loadProductsAndServicesByCategory($this->activeServiceCategoryId);
        }
    }

    /**
     * Toggle between percentage and fixed discount type for the additional discount
     */
    public function toggleOtherTotalDiscountType()
    {
        // Toggle between percentage and fixed
        $this->otherTotalDiscountType = ($this->otherTotalDiscountType === 'percentage') ? 'fixed' : 'percentage';

        // If a discount is already applied, recalculate it with the new type
        if ($this->otherTotalDiscountApplied) {
            $this->applyOtherTotalDiscount();
        }

        // Emit event to update UI
        $this->dispatch('discountTypeUpdated', [
            'serviceId' => 'total',
            'type' => $this->otherTotalDiscountType
        ]);
    }

    /**
     * Apply the additional discount to the total
     */
    public function applyOtherTotalDiscount()
    {
        try {
            // If empty, not a valid numeric value, or zero - remove the discount instead
            if (empty(trim($this->otherTotalDiscount)) || !is_numeric($this->otherTotalDiscount) || floatval($this->otherTotalDiscount) <= 0) {
                // If it was an attempt to apply a zero or invalid discount, remove any existing discount
                $this->removeOtherTotalDiscount();

                if (empty(trim($this->otherTotalDiscount)) || !is_numeric($this->otherTotalDiscount)) {
                    $this->addError('otherTotalDiscount', __('Please enter a valid discount amount.'));
                } else if (floatval($this->otherTotalDiscount) <= 0) {
                    $this->addError('otherTotalDiscount', __('Discount amount must be greater than zero.'));
                }
                return;
            }

            $discountValue = floatval($this->otherTotalDiscount);

            // Cap percentage discount to 100%
            if ($this->otherTotalDiscountType === 'percentage' && $discountValue > 100) {
                $this->otherTotalDiscount = '100';
                $discountValue = 100;
            }

            // Calculate the discount amount
            $afterCodeDiscount = $this->subtotal - $this->discountAmount;

            if ($this->otherTotalDiscountType === 'percentage') {
                $this->otherTotalDiscountAmount = round($afterCodeDiscount * ($discountValue / 100), 2);
            } else {
                // For fixed amount, cap at the subtotal after code discount
                $this->otherTotalDiscountAmount = min($afterCodeDiscount, round($discountValue / 1.15, 2));
            }

            // Mark as applied
            $this->otherTotalDiscountApplied = true;

            // Recalculate totals with the new discount
            $this->calculateTotals();

            // Clear any errors
            $this->resetErrorBag('otherTotalDiscount');

            // Show success message
            $message = $this->otherTotalDiscountApplied ? __('Additional discount updated successfully.') : __('Additional discount applied successfully.');
            session()->flash('message', $message);
        } catch (\Exception $e) {
            Log::error('Error applying total discount: ' . $e->getMessage());
            // Handle number formatting error specifically
            if (strpos($e->getMessage(), 'number_format(): Argument #1 ($num) must be of type int|float') !== false) {
                $this->addError('otherTotalDiscount', __('Please enter a valid number for the discount amount.'));
                $this->removeOtherTotalDiscount();
            } else {
                // Log other unexpected errors
                \Illuminate\Support\Facades\Log::error('Error applying total discount: ' . $e->getMessage());
                $this->addError('otherTotalDiscount', __('An error occurred while applying the discount. Please try again.'));
                $this->removeOtherTotalDiscount();
            }
        }
    }

    /**
     * Remove the additional discount
     */
    public function removeOtherTotalDiscount()
    {
        $this->otherTotalDiscountApplied = false;
        $this->otherTotalDiscountAmount = 0;
        $this->otherTotalDiscount = "0"; // Reset to "0" as a string since it's bound to an input field
        $this->otherTotalDiscountType = 'percentage'; // Reset to default type
        $this->calculateTotals();
        session()->flash('message', __('Additional discount removed.'));
    }

    /**
     * Handle Enter key press in the service discount field
     *
     * @param int $serviceId The service ID
     */
    public function addToSummaryOnEnter($serviceId)
    {
        // Check if the service has quantity > 0 before trying to add to summary
        $service = $this->getServiceById($serviceId);
        if ($service && $service['selected_quantity'] > 0 && !$this->reservationConfirmed) {
            $this->addToSummary($serviceId);
        }
    }
}
