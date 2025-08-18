<?php

namespace App\Http\Controllers;

use Filawidget\Services\AreaService;
use App\Helpers\WidgetHelper;
use App\Models\PointOfSale;
use App\Models\ProductAndService;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\StaffPosition;
use Illuminate\Support\Facades\Cache;
use App\Services\HomepageWidgetContentService;
use App\Services\MenupageWidgetContentService;
use App\Services\AboutpageWidgetContentService;
use App\Services\TestimonialsPageWidgetContentService;
use App\Services\ContactPageWidgetContentService;
use Illuminate\Http\Request;
use App\Models\AboutSection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use illuninate\Suport\Facades\Log;
use App\Models\HomeSection;

class PageController extends Controller
{

    public function __construct() {}

    public function oldhomePage(HomepageWidgetContentService $homepageWidgetContentService)
    {
        // Get homepage data from the service
        $viewData = $homepageWidgetContentService->getHomepageContent();

        // Get top services from each different category
        $categoriesWithTopServices = ServiceCategory::where('is_active', true)
            ->with(['productAndServices' => function($query) {
                $query->where('is_active', true)
                    ->withCount(['bookedReservationItems' => function($q) {
                        $q->whereHas('bookedReservation', function($qr) {
                            $qr->whereNotIn('status', ['cancelled', 'pending']);
                        });
                    }])
                    ->orderBy('booked_reservation_items_count', 'desc')
                    ->take(1);
            }])
            ->whereHas('productAndServices', function($query) {
                $query->where('is_active', true);
            })
            ->take(8)
            ->get();

        // Extract top service from each category
        $viewData['services'] = collect();
        foreach ($categoriesWithTopServices as $category) {
            if ($category->productAndServices->isNotEmpty()) {
                $viewData['services']->push($category->productAndServices->first());
            }
        }

        // If we don't have 8 services yet, get the next most booked services overall
        if ($viewData['services']->count() < 8) {
            $existingServiceIds = $viewData['services']->pluck('id')->toArray();
            $moreServices = ProductAndService::where('is_active', true)
                ->whereNotIn('id', $existingServiceIds)
                ->withCount(['bookedReservationItems' => function($query) {
                    $query->whereHas('bookedReservation', function($q) {
                        $q->whereNotIn('status', ['cancelled', 'pending']);
                    });
                }])
                ->orderBy('booked_reservation_items_count', 'desc')
                ->take(8 - $viewData['services']->count())
                ->get();

            $viewData['services'] = $viewData['services']->merge($moreServices);
        }
        $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));
        // Now render the view with the data
        return view('pages.home', $viewData);
    }
    public function homePage(HomepageWidgetContentService $homepageWidgetContentService)
    {
        $viewData = $homepageWidgetContentService->getHomepageContent();

            // Get top services from each different category
            $categoriesWithTopServices = ServiceCategory::where('is_active', true)
                ->with(['productAndServices' => function($query) {
                    $query->where('is_active', true)
                        ->withCount(['bookedReservationItems' => function($q) {
                            $q->whereHas('bookedReservation', function($qr) {
                                $qr->whereNotIn('status', ['cancelled', 'pending']);
                            });
                        }])
                        ->orderBy('booked_reservation_items_count', 'desc')
                        ->take(1);
                }])
                ->whereHas('productAndServices', function($query) {
                    $query->where('is_active', true);
                })
                ->take(8)
                ->get();

            // Extract top service from each category
            $viewData['services'] = collect();
            foreach ($categoriesWithTopServices as $category) {
                if ($category->productAndServices->isNotEmpty()) {
                    $viewData['services']->push($category->productAndServices->first());
                }
            }

            // If we don't have 8 services yet, get the next most booked services overall
            if ($viewData['services']->count() < 8) {
                $existingServiceIds = $viewData['services']->pluck('id')->toArray();
                $moreServices = ProductAndService::where('is_active', true)
                    ->whereNotIn('id', $existingServiceIds)
                    ->withCount(['bookedReservationItems' => function($query) {
                        $query->whereHas('bookedReservation', function($q) {
                            $q->whereNotIn('status', ['cancelled', 'pending']);
                        });
                    }])
                    ->orderBy('booked_reservation_items_count', 'desc')
                    ->take(8 - $viewData['services']->count())
                    ->get();

                $viewData['services'] = $viewData['services']->merge($moreServices);
            }
            $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));

        // ========== GET DYNAMIC SECTIONS FROM DATABASE ==========
        // Get sections from database (same pattern as about page)
        $sections = HomeSection::where('visible', 1)->orderBy('order')->get();
        
        // Process sections to ensure content is properly decoded
        $viewData['sections'] = $sections->map(function ($section) {
            // Decode content if it's stored as JSON string
            if (is_string($section->content)) {
                $section->content = json_decode($section->content, true) ?? [];
            }
            
            // Ensure content is always an array
            if (!is_array($section->content)) {
                $section->content = [];
            }
            
            return $section;
        });
    // Add this to your homePage method right before the return statement:
        $viewData['serviceCategories'] = ServiceCategory::where('is_active', true)
            ->with(['productAndServices' => function ($query) {
                $query->where('is_active', true)->orderBy('id', 'asc');
            }])     
            ->orderBy('id', 'asc')
        ->get();
        // $viewData['workingHours'] = WorkingHour::orderBy('day_order')->get();

        // Now render the view with the data
        return view('pages.home', $viewData);
    }
        public function menuPage(Request $request, MenupageWidgetContentService $menupageWidgetContentService)
    {
        $locationType = $request->type ?? 'salon';
        // Get menupage data from the service
        $viewData = $menupageWidgetContentService->getMenupageContent($locationType);

        $viewData['serviceCategories'] = ServiceCategory::with(['productAndServices' => function ($query) use ($locationType) {
            if ($locationType == 'home') {
                $query->where('can_be_done_at_home', true)->orderBy('sort_order');
            } else {
                $query->where('can_be_done_at_salon', true)->orderBy('sort_order');
            }
        }])
            ->whereHas('productAndServices', function ($query) use ($locationType) {
                if ($locationType == 'home') {
                    $query->where('can_be_done_at_home', true)->orderBy('sort_order');
                } else {
                    $query->where('can_be_done_at_salon', true)->orderBy('sort_order');
                }
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Add isAvailableForBooking attribute to each product/service
        $viewData['serviceCategories']->each(function ($category) use ($locationType) {
            $category->productAndServices->each(function ($service) use ($locationType) {
                $service->append('isAvailableForBooking');
                $service->isAvailableForBooking = $service->isAvailableForBooking($locationType);
            });
        });

        $viewData['locationType'] = $locationType;


        $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));
        // dd($viewData['qrCode']);

        // Now render the view with the data
        return view('pages.menu', $viewData);
    }

    // public function about(AboutpageWidgetContentService $aboutpageWidgetContentService)
    // {
    //     // Get about page data from the service
    //     $viewData = $aboutpageWidgetContentService->getAboutpageContent();
    //     $viewData['sections'] = AboutSection::where('visible', true)->orderBy('order')->get();
    //     // $viewData['sections'] = $sections->map(function ($section) {
    //     //     // Decode content if it's stored as JSON string
    //     //     if (is_string($section->content)) {
    //     //         $section->content = json_decode($section->content, true) ?? [];
    //     //     }
    //     //     return $section;
    //     // });
    //     $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));

    //     // Now render the view with the data
    //     return view('about', $viewData);
    // }

    public function about(AboutpageWidgetContentService $aboutpageWidgetContentService)
        {
            // Get about page data from the service
            $viewData = $aboutpageWidgetContentService->getAboutpageContent();
            
            // Get sections from database
            $sections = AboutSection::where('visible', 1)->orderBy('order')->get();
            // Process sections to ensure content is properly decoded
            $viewData['sections'] = $sections->map(function ($section) {
                // Decode content if it's stored as JSON string
                if (is_string($section->content)) {
                    $section->content = json_decode($section->content, true) ?? [];
                }
                
                // Ensure content is always an array
                if (!is_array($section->content)) {
                    $section->content = [];
                }
                
                return $section;
            });
            
            // Generate QR code
            $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));
            
            // Now render the view with the data
            return view('pages.about', $viewData);
        }

    public function booking()
    {
        return view('pages.booking');
    }

    public function contact(ContactPageWidgetContentService $contactPageWidgetContentService)
    {
        // Get contact page data from the service
        $viewData = $contactPageWidgetContentService->getContactPageContent();
        $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));
        // Now render the view with the data
        return view('pages.contact', $viewData);
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function testimonials(TestimonialsPageWidgetContentService $testimonialsPageWidgetContentService)
    {
        // Get testimonials page data from the service
        $viewData = $testimonialsPageWidgetContentService->getTestimonialsPageContent();
        $viewData['qrCode'] = QrCode::size(200)->generate(route('home'));

        // Now render the view with the data
        return view('pages.testimonials', $viewData);
    }

    public function workWithUs()
    {
        $staffPositions = StaffPosition::all();
        $productAndServices = ProductAndService::all();
        return view('pages.work-with-us', compact('staffPositions', 'productAndServices'));
    }

    public function terms()
    {
        $defaultEmail = Setting::get('default_email');
        $defaultPhoneNumber = Setting::get('default_phone_number');
        $salonLocation = Setting::get('salon_location');
        return view('pages.terms', compact('defaultEmail', 'defaultPhoneNumber', 'salonLocation'));
    }
}
