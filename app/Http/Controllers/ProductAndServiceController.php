<?php

namespace App\Http\Controllers;

use App\Models\ProductAndService;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ProductAndServiceController extends Controller
{


    /**
     * Display home services page
     */
    public function homeServicesPage()
    {
        $categories = ServiceCategory::with(['productAndServices' => function ($query) {
            $query->withAvailableForBooking('home')
                ->where('can_be_done_at_home', true)
                ->where('is_active', true)
                ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $services = ProductAndService::withAvailableForBooking('home')
            ->where('can_be_done_at_home', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $type = 'home';
        return view('pages.services', compact('categories', 'services', 'type'));
    }

    /**
     * Display salon services page
     */
    public function salonServicesPage()
    {
        $categories = ServiceCategory::with(['productAndServices' => function ($query) {
            $query->withAvailableForBooking('salon')
                ->where('can_be_done_at_salon', true)
                ->where('is_active', true)
                ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $services = ProductAndService::withAvailableForBooking('salon')
            ->where('can_be_done_at_salon', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $type = 'salon';
        return view('pages.services', compact('categories', 'services', 'type'));
    }

    /**
     * Display service details page
     */
    public function serviceDetails(Request $request, $id)
    {
        $locationType = $request->type ?? 'salon';

        $service = ProductAndService::with('category')
            ->withAvailableStaff($locationType)
            ->findOrFail($id);

        $category = $service->category;

        // Get related services from the same category
        $relatedServices = ProductAndService::withAvailableStaff($locationType)
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->take(6)
            ->get();

        // Get other services from different categories
        $otherServices = ProductAndService::withAvailableStaff($locationType)
            ->where('category_id', '!=', $service->category_id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Get all categories for the sidebar
        $categories = ServiceCategory::with(['productAndServices' => function ($query) use ($locationType) {
                $query->withAvailableForBooking($locationType)
                    ->where('can_be_done_at_' . $locationType, true)
                    ->where('is_active', true)
                    ->orderBy('sort_order');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();


        return view('pages.service-detail', compact('service', 'category', 'relatedServices', 'otherServices', 'categories', 'locationType'));
    }

    /**
     * Get service details as JSON for the modal
     */
    public function getServiceDetails($id)
    {
        $service = ProductAndService::with('category')
            ->withAvailableStaff()
            ->findOrFail($id);

        return response()->json($service);
    }

    /**
     * Display services that are actually available for booking
     *
     * @param string|null $locationType Either 'home', 'salon', or null for any location
     * @return \Illuminate\View\View
     */
    public function availableServicesPage(Request $request)
    {
        $locationType = $request->type ?? null;

        // Get all services available for booking
        $availableServices = ProductAndService::getAvailableForBooking($locationType);

        // Get IDs of available services
        $availableServiceIds = $availableServices->pluck('id')->toArray();

        // Get categories that have available services
        $categories = ServiceCategory::with(['productAndServices' => function ($query) use ($availableServiceIds) {
            $query->whereIn('id', $availableServiceIds)
                ->orderBy('sort_order');
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(function ($category) {
                return $category->productAndServices->isNotEmpty();
            });

        $services = $availableServices->sortBy('sort_order');

        return view('pages.services', compact('categories', 'services', 'locationType'));
    }
}
