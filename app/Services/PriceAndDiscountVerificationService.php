<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\ProductAndService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PriceAndDiscountVerificationService
{
    /**
     * Calculate discount details for a given discount code and items
     *
     * @param string $discountCode
     * @param array $items Array of items with service_id, quantity, and service_type
     * @return array Returns calculation details or error information with success status
     */
    public function calculateDiscountDetails(string $discountCode, array $items, $customerId = null)
    {
        try {
            // Find the discount by code
            $discount = Discount::where('code', $discountCode)->first();

            // If no discount found, return error
            if (!$discount) {
                return [
                    'success' => false,
                    'message' => 'Invalid discount code'
                ];
            }

            // Check if discount is active
            if (!$discount->is_active) {
                return [
                    'success' => false,
                    'message' => 'This discount code is no longer active'
                ];
            }

            // Validate discount date range
            $now = Carbon::now();
            $startDate = Carbon::parse($discount->start_date)->startOfDay();
            $endDate = Carbon::parse($discount->end_date)->endOfDay();

            if (!$now->between($startDate, $endDate)) {
                return [
                    'success' => false,
                    'message' => 'Discount code has expired or not yet active'
                ];
            }

            // Validate maximum uses
            if ($discount->maximum_uses > 0 && $discount->times_used >= $discount->maximum_uses) {
                return [
                    'success' => false,
                    'message' => 'Discount code has reached maximum usage limit'
                ];
            }

            // Check if discount is for specific customers only
            if ($discount->given_to === 'fixed_customers') {
                // If customer is not logged in, return error message
                if ($customerId === null) {
                    return [
                        'success' => false,
                        'message' => 'This discount is for specific customers. Please login first to check if you are eligible.'
                    ];
                }

                // Fetch the customer model
                $customer = \App\Models\Customer::find($customerId);
                if (!$customer || !$discount->isValidForCustomer($customer)) {
                    return [
                        'success' => false,
                        'message' => 'You are not eligible for this discount.'
                    ];
                }
            }

            // Calculate subtotal
            $subtotal = 0;
            $invalidServiceIds = [];
            $services = [];
            $itemTotals = [];

            // First calculate subtotal and individual item totals
            foreach ($items as $index => $item) {
                $serviceType = $item['service_type'];
                $service = ProductAndService::find($item['service_id'] ?? $item['id']);

                if (!$service) {
                    $invalidServiceIds[] = $item['service_id'] ?? $item['id'];
                    continue;
                }

                $services[$index] = $service;
                $price = $this->getServicePrice($service, $serviceType);

                if ($price <= 0) {
                    $invalidServiceIds[] = $item['service_id'] ?? $item['id'];
                    continue;
                }

                $itemTotal = $price * $item['quantity'];
                $itemTotals[$index] = $itemTotal;
                $subtotal += $itemTotal;
            }

            // Check if any invalid services were found
            if (!empty($invalidServiceIds)) {
                Log::warning('Invalid services encountered when applying discount', [
                    'discount_code' => $discountCode,
                    'invalid_service_ids' => $invalidServiceIds
                ]);
                return [
                    'success' => false,
                    'message' => 'One or more items in your order are invalid'
                ];
            }

            // Check minimum order amount
            if ($subtotal < $discount->minimum_order_amount) {
                return [
                    'success' => false,
                    'message' => 'Order total does not meet the minimum amount required for this discount'
                ];
            }

            // Calculate discount amount and apply to each item
            $discountAmount = 0;
            $discountedItemTotals = [];

            if ($discount->type === 'percentage') {
                // Apply percentage discount to each item
                foreach ($itemTotals as $index => $itemTotal) {
                    $itemDiscount = $itemTotal * ($discount->amount / 100);
                    $discountedItemTotals[$index] = $itemTotal - $itemDiscount;
                    $discountAmount += $itemDiscount;
                }
            } else {
                // Apply fixed amount discount proportionally to each item
                $totalDiscount = min($discount->amount, $subtotal);
                foreach ($itemTotals as $index => $itemTotal) {
                    $itemDiscount = ($itemTotal / $subtotal) * $totalDiscount;
                    $discountedItemTotals[$index] = $itemTotal - $itemDiscount;
                    $discountAmount += $itemDiscount;
                }
            }

            // Round discount amount to 2 decimal places
            $discountAmount = round($discountAmount, 2);

            // Calculate after discount subtotal
            $afterDiscount = $subtotal - $discountAmount;

            // Now calculate VAT and other taxes for each item based on discounted prices
            $vatAmount = 0;
            $otherTaxesAmount = 0;

            foreach ($items as $index => $item) {
                if (!isset($discountedItemTotals[$index])) continue;

                $service = $services[$index];
                $serviceType = $item['service_type'];
                $quantity = $item['quantity'];

                // Calculate the discounted price per unit
                $discountedPricePerUnit = $discountedItemTotals[$index] / $quantity;

                // Create a temporary copy of the service to apply discounted price
                $tempService = clone $service;
                if ($serviceType === 'home') {
                    $tempService->price_home = $discountedPricePerUnit;
                } else {
                    $tempService->price = $discountedPricePerUnit;
                }

                // Calculate VAT on the discounted price
                $itemVatAmount = $tempService->getVatAmount($serviceType) * $quantity;
                $vatAmount += $itemVatAmount;

                // Calculate other taxes on the discounted price
                $itemOtherTaxAmount = $tempService->getOtherTaxesAmount($serviceType) * $quantity;
                $otherTaxesAmount += $itemOtherTaxAmount;
            }

            // Round tax amounts to 2 decimal places
            $vatAmount = round($vatAmount, 2);
            $otherTaxesAmount = round($otherTaxesAmount, 2);

            // Calculate final total
            $total = $afterDiscount + $vatAmount + $otherTaxesAmount;

            // Return calculated values with success flag
            return [
                'success' => true,
                'message' => 'Discount applied successfully',
                'discount_details' => [
                    'code' => $discount->code,
                    'type' => $discount->type,
                    'amount' => $discount->amount,
                    'name' => $discount->name,
                    'description' => $discount->description
                ],
                'subtotal' => round($subtotal, 2),
                'discount_amount' => $discountAmount,
                'after_discount' => round($afterDiscount, 2),
                'vat_amount' => $vatAmount,
                'other_taxes_amount' => $otherTaxesAmount,
                'total' => round($total, 2)
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating discount details: ' . $e->getMessage(), [
                'discount_code' => $discountCode,
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'An error occurred while processing the discount: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get service price based on service ID and type
     *
     * @param ProductAndService $service
     * @param string $serviceType
     * @return float
     */
    private function getServicePrice($service, $serviceType)
    {
        try {
            if (!$service) {
                return 0.00; // Return zero if service not found
            }

            // Return the appropriate price based on service type
            if ($serviceType === 'home') {
                return (float) $service->price_home;
            } else {
                return (float) $service->price;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching service price: ' . $e->getMessage());
            return 0.00; // Return zero if there's an error
        }
    }


    /**
     * Calculate order totals without applying a discount
     *
     * @param array $items Array of items with service_id, quantity, and service_type
     * @return array Order totals including subtotal, VAT, other taxes and total
     */
    public function calculateOrderTotals(array $items)
    {
        try {
            // Calculate subtotal
            $subtotal = 0;
            $invalidServiceIds = [];
            $services = [];
            $itemTotals = [];

            // First calculate subtotal and individual item totals
            foreach ($items as $index => $item) {
                $serviceType = $item['service_type'];
                $service = ProductAndService::find($item['service_id'] ?? $item['id']);

                if (!$service) {
                    $invalidServiceIds[] = $item['service_id'] ?? $item['id'];
                    continue;
                }

                $services[$index] = $service;
                $price = $this->getServicePrice($service, $serviceType);

                if ($price <= 0) {
                    $invalidServiceIds[] = $item['service_id'] ?? $item['id'];
                    continue;
                }

                $itemTotal = $price * $item['quantity'];
                $itemTotals[$index] = $itemTotal;
                $subtotal += $itemTotal;
            }

            // Check if any invalid services were found
            if (!empty($invalidServiceIds)) {
                Log::warning('Invalid services encountered when calculating order totals', [
                    'invalid_service_ids' => $invalidServiceIds
                ]);
                return [
                    'success' => false,
                    'message' => 'One or more items in your order are invalid'
                ];
            }

            // Calculate VAT and other taxes for each item
            $vatAmount = 0;
            $otherTaxesAmount = 0;

            foreach ($items as $index => $item) {
                if (!isset($services[$index])) continue;

                $service = $services[$index];
                $serviceType = $item['service_type'];
                $quantity = $item['quantity'];

                // Calculate VAT
                $itemVatAmount = $service->getVatAmount($serviceType) * $quantity;
                $vatAmount += $itemVatAmount;

                // Calculate other taxes
                $itemOtherTaxAmount = $service->getOtherTaxesAmount($serviceType) * $quantity;
                $otherTaxesAmount += $itemOtherTaxAmount;
            }

            // Round amounts to 2 decimal places
            $subtotal = round($subtotal, 2);
            $vatAmount = round($vatAmount, 2);
            $otherTaxesAmount = round($otherTaxesAmount, 2);

            // Calculate final total
            $total = $subtotal + $vatAmount + $otherTaxesAmount;

            // Return calculated values
            return [
                'success' => true,
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'other_taxes_amount' => $otherTaxesAmount,
                'total' => round($total, 2)
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating order totals: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'An error occurred while calculating order totals: ' . $e->getMessage()
            ];
        }
    }
}
