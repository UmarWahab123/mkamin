<?php

use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ExampleController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\UserMessageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProductAndServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Calendar API endpoints for service booking
Route::get('/available-dates/{pointOfSaleId}', [CalendarController::class, 'getAvailableDates']);
Route::get('/available-dates/{pointOfSaleId}/{serviceId}/{serviceType}', [CalendarController::class, 'getAvailableDatesBasedOnServiceAndLocation']);
Route::get('/available-staff/{pointOfSaleId}/{date}/{serviceId}/{locationType}', [CalendarController::class, 'getAvailableStaff']);
Route::get('/staff-schedule/{staffId}/{date}/{serviceId}', [CalendarController::class, 'getStaffSchedule']);

// Get taxes for products in cart
Route::get('/products/taxes', [ProductController::class, 'getProductTaxes']);

// Cart API endpoints
Route::post('/cart/calculate-totals', [CartController::class, 'calculateTotals']);

Route::post('/booking/process', [BookingController::class, 'processNewBooking']);

// User Message API endpoint
Route::post('/contact', [UserMessageController::class, 'store']);

Route::get('/services/{id}', [ProductAndServiceController::class, 'getServiceDetails']);

