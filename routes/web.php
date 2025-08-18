<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProductAndServiceController;
use App\Http\Controllers\ReservationInvoiceController;
use Filawidget\Services\AreaService;
use Filawidget\Services\PageService;
use App\Helpers\WidgetHelper;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\HyperPayController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountCardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(PageController::class)->group(function () {
  Route::get('/', 'homePage')->name('home');
  Route::get('/menu', 'menuPage')->name('menu');
  Route::get('/about', 'about')->name('about');
  Route::get('/pricing', 'pricing')->name('pricing');
  Route::get('/contact', 'contact')->name('contact');
  Route::get('/faq', 'faq')->name('faq');
  Route::get('/testimonials', 'testimonials')->name('testimonials');
  Route::get('/work-with-us', 'workWithUs')->name('work-with-us');
  Route::get('/terms', 'terms')->name('terms');
});

// Route::get('/services', [ProductAndServiceController::class, 'servicesPage'])->name('services');
Route::get('/home-services', [ProductAndServiceController::class, 'homeServicesPage'])->name('home-services');
Route::get('/salon-services', [ProductAndServiceController::class, 'salonServicesPage'])->name('salon-services');
Route::get('/services/{id}', [ProductAndServiceController::class, 'serviceDetails'])->name('services.detail');
Route::get('/api/services/{id}', [ProductAndServiceController::class, 'getServiceDetails'])->name('services.details');
Route::get('/cart', [BookingController::class, 'cart'])->name('cart');
Route::get('/checkout', [BookingController::class, 'newCheckout'])->name('checkout');
// Discount code verification
Route::post('/discount/verify', [DiscountController::class, 'verify'])->name('discount.verify');
Route::post('/discount/remove', [DiscountController::class, 'remove'])->name('discount.remove');
Route::get('/booking/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking'])->middleware('auth')->name('bookings.cancel');


Route::get('/reservations/{id}/invoice', [ReservationInvoiceController::class, 'show'])->name('reservations.invoice');

Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'show'])->name('invoices.print');

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Authentication Routes
Route::middleware('unAuth')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('quickRegister', [AuthController::class, 'quickRegister'])->name('quickRegisteration');

});
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Email Verification
Route::get('email/verify', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('resend-verification-email', [AuthController::class, 'resendEmail'])->name('resendVerificationEmail');
// Logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Customer routes
Route::middleware('auth')->group(function () {
    Route::get('/customer/bookings', [CustomerController::class, 'bookings'])->name('customer.bookings');
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('user.password.update');
});

// HyperPay routes
Route::post('/hyperpay/checkout', [HyperPayController::class, 'prepareCheckout'])->name('hyperpay.checkout');
Route::get('/hyperpay/form', [HyperPayController::class, 'showPaymentForm'])->name('hyperpay.form');
Route::get('/hyperpay/response', [HyperPayController::class, 'handleResponse'])->name('hyperpay.response');
Route::post('/hyperpay/notification', [HyperPayController::class, 'handleNotification'])->name('hyperpay.notification');



// Discount Card route
Route::get('/discount-card/{discount}/{customer}', [DiscountCardController::class, 'show'])
    ->name('discount.card');

// Staff application API endpoint
Route::post('/staff/apply', [StaffController::class, 'store'])->name('staff.apply');
