<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductAndService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get tax information for multiple products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductTaxes(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:product_and_services,id',
            'service_types' => 'required|array',
            'service_types.*' => 'string|in:salon,home',
        ]);

        $productIds = $request->input('product_ids');
        $serviceTypes = $request->input('service_types');

        // Create an array to map product IDs to their service types
        $serviceTypeMap = [];
        foreach ($productIds as $index => $productId) {
            $serviceTypeMap[$productId] = $serviceTypes[$index] ?? 'salon';
        }

        // Get product tax information
        $products = ProductAndService::whereIn('id', $productIds)->get();

        $results = [];

        foreach ($products as $product) {
            $locationType = $serviceTypeMap[$product->id] ?? 'salon';

            $results[] = [
                'id' => $product->id,
                'vat_amount' => $product->getVatAmount($locationType),
                'tax_amount' => $product->getOtherTaxesAmount($locationType)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}
