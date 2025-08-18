<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\ProductAndService;
use App\Services\PriceAndDiscountVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * @var PriceAndDiscountVerificationService
     */
    protected $discountService;

    /**
     * DiscountController constructor.
     *
     * @param PriceAndDiscountVerificationService $discountService
     */
    public function __construct(PriceAndDiscountVerificationService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Verify a discount code and apply it to calculate new totals
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'discount_code' => 'required|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|integer|exists:product_and_services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.service_type' => 'required|string|in:home,salon',
        ]);

        try {
            // Call the discount service to calculate discount details
            $result = $this->discountService->calculateDiscountDetails(
                $validated['discount_code'],
                $validated['items'],
                $request->customer_id ?? null
            );

            // Return the appropriate response based on success status
            if (!$result['success']) {
                return response()->json($result, 400);
            }

            // Get current checkout data from session
            if (session()->has('checkoutData') && !empty(session('checkoutData'))) {
                $checkoutData = session('checkoutData');
            } else {
                // No data in query param or session, redirect to cart
                return redirect()->route('cart')->with('error', 'No booking data found. Please add services to your cart first.');
            }
            // Update checkout data with discount details
            $checkoutData['discount_code'] = $validated['discount_code'];
            $checkoutData['discount_details'] = $result['discount_details'];
            $checkoutData['discount_amount'] = $result['discount_amount'];
            $checkoutData['subtotal'] = $result['subtotal'];
            $checkoutData['vat'] = $result['vat_amount'];
            $checkoutData['other_taxes'] = $result['other_taxes_amount'];
            $checkoutData['total'] = $result['total'];

            // dd($checkoutData);
            // Store updated checkout data in session
            session(['checkoutData' => $checkoutData]);

            // Return success response
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error processing discount code: ' . $e->getMessage(), [
                'discount_code' => $request->input('discount_code'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the discount: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate order totals without applying a discount
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateTotals(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|integer|exists:product_and_services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.service_type' => 'required|string|in:home,salon',
        ]);

        try {
            // Call the discount service to calculate order totals
            $result = $this->discountService->calculateOrderTotals($validated['items']);

            // Return the appropriate response based on success status
            if (!$result['success']) {
                return response()->json($result, 400);
            }

            // Get current checkout data from session
            $checkoutData = session('checkoutData', []);

            // Remove discount related data
            unset($checkoutData['discount_code']);
            unset($checkoutData['discount_details']);
            unset($checkoutData['discount_amount']);

            // Update totals
            $checkoutData['subtotal'] = $result['subtotal'];
            $checkoutData['vat'] = $result['vat_amount'];
            $checkoutData['other_taxes'] = $result['other_taxes_amount'];
            $checkoutData['total'] = $result['total'];

            // Store updated checkout data in session
            session(['checkoutData' => $checkoutData]);

            // Return success response
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error calculating order totals: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while calculating order totals: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove discount from checkout session
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        try {
            // Get current checkout data from session
            if (!session()->has('checkoutData') || empty(session('checkoutData'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'No checkout data found in session'
                ], 400);
            }

            $checkoutData = session('checkoutData');

            // Remove discount-related data
            unset($checkoutData['discount_code']);
            unset($checkoutData['discount_details']);
            unset($checkoutData['discount_amount']);
            unset($checkoutData['after_discount']);

            // Recalculate totals without discount
            $subtotal = 0;
            foreach ($checkoutData['services'] as $service) {
                $price = $service['service_type'] === 'home' ? $service['price_home'] : $service['price'];
                $subtotal += $price * $service['quantity'];
            }

            // Calculate VAT (15%)
            $vat = $subtotal * 0.15;

            // Update totals
            $checkoutData['subtotal'] = $subtotal;
            $checkoutData['vat'] = $vat;
            $checkoutData['total'] = $subtotal + $vat;

            // Store updated checkout data in session
            session(['checkoutData' => $checkoutData]);

            return response()->json([
                'success' => true,
                'message' => 'Discount removed successfully',
                'subtotal' => $subtotal,
                'vat_amount' => $vat,
                'total' => $subtotal + $vat
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing discount: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the discount: ' . $e->getMessage()
            ], 500);
        }
    }
}
