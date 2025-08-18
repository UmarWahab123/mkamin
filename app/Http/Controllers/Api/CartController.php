<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductAndService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Calculate totals for items in the cart including taxes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateTotals(Request $request)
    {
        // Validate the request
        $request->validate([
            'items' => 'required|array',
            'items.*.service_id' => 'required|integer|exists:product_and_services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.service_type' => 'required|string|in:salon,home',
        ]);

        $items = $request->input('items');
        $subtotal = 0;
        $vatAmount = 0;
        $otherTaxesAmount = 0;

        // Fetch all products in one query for efficiency
        $productIds = array_column($items, 'service_id');
        $products = ProductAndService::with('taxes')->whereIn('id', $productIds)->get();

        // Create a product lookup map
        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product->id] = $product;
        }

        // Calculate totals for each item directly from the items array
        foreach ($items as $item) {
            $serviceId = $item['service_id'];
            $quantity = $item['quantity'];
            $serviceType = $item['service_type'];

            if (isset($productMap[$serviceId])) {
                $product = $productMap[$serviceId];

                // Get the appropriate price based on service type
                $price = ($serviceType === 'home' && $product->price_home)
                    ? floatval($product->price_home)
                    : floatval($product->price);

                // Add to subtotal
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                // Calculate VAT
                $itemVat = $product->getVatAmount($serviceType) * $quantity;
                $vatAmount += $itemVat;

                // Calculate other taxes
                $itemOtherTaxes = $product->getOtherTaxesAmount($serviceType) * $quantity;
                $otherTaxesAmount += $itemOtherTaxes;
            }
        }

        // Calculate total
        $total = $subtotal + $vatAmount + $otherTaxesAmount;

        // Return the calculated totals
        return response()->json([
            'success' => true,
            'subtotal' => round($subtotal, 2),
            'vat_amount' => round($vatAmount, 2),
            'other_taxes_amount' => round($otherTaxesAmount, 2),
            'total' => round($total, 2)
        ]);
    }
}
