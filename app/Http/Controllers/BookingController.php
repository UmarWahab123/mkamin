<?php

namespace App\Http\Controllers;

use App\Models\ProductAndService;
use App\Models\ServiceCategory;
use App\Models\BookedReservation;
use App\Models\BookedReservationItem;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Setting;
use App\Models\Staff;
use App\Services\PriceAndDiscountVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client;
use App\Models\Ticket;

class BookingController extends Controller
{
    /**
     * Display the shopping cart page
     */
    public function cart()
    {
        return view('pages.cart');
    }

    /**
     * Display the new checkout page
     */
    public function newCheckout(Request $request, PriceAndDiscountVerificationService $discountService)
    {
        // Get and decode the booking data from query parameters
        $encodedData = $request->query('data');
        $bookingData = null;

        if ($encodedData) {
            // If data exists in query param, decode it and store in session
            $bookingData = json_decode(urldecode($encodedData), true);
            session(['checkoutData' => $bookingData]);
            return redirect()->route('checkout');
        } else {
            // If no query param data, check session
            if (session()->has('checkoutData') && !empty(session('checkoutData'))) {
                $bookingData = session('checkoutData');
            } else {
                // No data in query param or session, redirect to cart
                return redirect()->route('cart')->with('error', __('No booking data found. Please add services to your cart first.'));
            }
        }

        // Validate the booking data
        if (!$bookingData || !isset($bookingData['services']) || empty($bookingData['services'])) {
            return redirect()->route('cart')->with('error', __('Invalid booking data. Please try again.'));
        }

        // Check for overlapping appointments in the booking data
        $hasOverlap = false;
        $overlapMessage = '';

        foreach ($bookingData['services'] as $service) {
            if (!empty($service['staff_id']) && !empty($service['appointment_date'])) {
                if ($this->hasAppointmentOverlap(
                    $service['staff_id'],
                    $service['appointment_date'],
                    $service['start_time'],
                    $service['end_time']
                )) {
                    $hasOverlap = true;
                    $overlapMessage = __('One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.');

                    // Return to cart with error message
                    return redirect()->route('cart')->with('error', $overlapMessage)->with('removeSchedules', $hasOverlap);
                }
            }
        }

        try {
            // Validate each service and recalculate totals
            $validatedServices = [];
            $subtotal = 0;

            foreach ($bookingData['services'] as $service) {
                // Find the service in the database
                $dbService = ProductAndService::where('id', $service['id'])->with('category')->first();

                if (!$dbService) {
                    continue; // Skip invalid services
                }

                // Validate price
                $price = floatval($dbService->price);
                $price_home = floatval($dbService->price_home);

                // Validate quantity
                $quantity = max(1, min(10, intval($service['quantity'])));

                // Determine the correct price based on service type
                $final_price = $service['service_type'] === 'home' ? $price_home : $price;

                // Add to validated services
                $validatedServices[] = [
                    'id' => $dbService->id,
                    'unique_id' => $service['unique_id'] ?? null,
                    'name' => $dbService->name,
                    'category_id' => $dbService->category->id,
                    'category_name' => $dbService->category->name,
                    'can_be_done_at_home' => $dbService->can_be_done_at_home,
                    'image' => $service['image'],
                    'price' => $price,
                    'price_home' => $price_home,
                    'quantity' => $quantity,
                    'service_type' => $service['service_type'],
                    'final_price' => $final_price,
                    'duration_minutes' => $dbService->duration_minutes ?? 60,
                    // Copy appointment details if they exist
                    'appointment_date' => $service['appointment_date'] ?? null,
                    'appointment_time' => $service['appointment_time'] ?? null,
                    'start_time' => $service['start_time'] ?? null,
                    'end_time' => $service['end_time'] ?? null,
                    'staff_id' => $service['staff_id'] ?? null,
                    'staff_name' => $service['staff_name'] ?? null,
                    'is_reschedule' => $service['is_reschedule'] ?? false,
                    'pending_reservation_id' => $service['pending_reservation_id'] ?? null,
                ];

                $subtotal += $final_price * $quantity;
            }
            if (isset($bookingData['discount_code'])) {
                $result = $discountService->calculateDiscountDetails(
                    $bookingData['discount_code'],
                    $validatedServices,
                    auth()->user()?->customer->id
                );
                // dd($result);
                $discount_code = $bookingData['discount_code'];
                $discount_details = $result['discount_details'];
                $discount_amount = $result['discount_amount'];
                $subtotal = $result['subtotal'];
                $vat = $result['vat_amount'];
                $otherTaxes = $result['other_taxes_amount'];
                $total = $result['total'];
                // Create validated booking data
                $validatedBookingData = [
                    'services' => $validatedServices,
                    'subtotal' => $subtotal,
                    'discount_code' => $discount_code,
                    'discount_details' => $discount_details,
                    'discount_amount' => $discount_amount,
                    'vat' => $vat,
                    'other_taxes' => $otherTaxes,
                    'total' => $total
                ];
            } else {
                $vat = isset($bookingData['vat']) ? floatval($bookingData['vat']) : ($subtotal * 0.15);
                $otherTaxes = isset($bookingData['other_taxes']) ? floatval($bookingData['other_taxes']) : 0;
                $total = isset($bookingData['total']) ? floatval($bookingData['total']) : ($subtotal + $vat + $otherTaxes);
                // Create validated booking data
                $validatedBookingData = [
                    'services' => $validatedServices,
                    'subtotal' => $subtotal,
                    'vat' => $vat,
                    'other_taxes' => $otherTaxes,
                    'total' => $total
                ];

            }



            // dd($validatedBookingData);
            // Store the validated data in session
            session(['checkoutData' => $validatedBookingData]);
            // dd($validatedBookingData);
            // Return the view with validated data
            return view('pages.checkout', [
                'bookingData' => $validatedBookingData
            ]);

        } catch (\Exception $e) {
            // dd($e);
            return redirect()->route('cart')->with('error', 'Error processing booking data. Please try again.');
        }
    }

    /**
     * Display the booking confirmation page
     */
    public function confirmation(Request $request)
    {
        $reservation = BookedReservation::where('id', $request->id)->with('items', 'invoice')->first();

        // Check if reservation exists
        if (!$reservation) {
            return redirect()->back()->with('error', __('Reservation not found.'));
        }

        // Check if the reservation belongs to the authenticated user's customer
        $customerFromAuth = auth()->user()->customer ?? null;
        if (!$customerFromAuth || $reservation->customer_id != $customerFromAuth->id) {
            return redirect()->back()->with('error', __('This reservation does not belong to your account.'));
        }

        // Check for overlapping appointments
        $hasOverlap = false;
        $overlapMessage = '';

        if ($reservation->status === 'pending') {
            foreach ($reservation->items as $item) {
                if ($item->staff_id && $item->appointment_date) {
                    if ($this->hasAppointmentOverlap(
                        $item->staff_id,
                        $item->appointment_date,
                        $item->start_time,
                        $item->end_time,
                        $item->id
                    )) {
                        $hasOverlap = true;
                        $overlapMessage = __('One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.');
                        break; // Break once we find an overlap
                    }
                }
            }
        }

        $default_email = Setting::get('default_email');
        $default_phone_number = Setting::get('default_phone_number');
        return view('pages.booking.confirmation', [
            'reservation' => $reservation,
            'default_email' => $default_email,
            'default_phone_number' => $default_phone_number,
            'has_overlap' => $hasOverlap,
            'overlap_message' => $overlapMessage
        ]);
    }

    /**
     * Process the new booking with scheduled appointments
     */
    public function processNewBooking(Request $request, PriceAndDiscountVerificationService $priceService)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'paymentMethod' => 'required|in:cash,card,hyperpay',
                'order_items' => 'required|json',
                'discount_code' => 'nullable|string',
            ]);

            // Decode order items
            $orderItems = json_decode($request->order_items, true);

            // Check for time overlaps
            foreach ($orderItems as $item) {
                if ($item['staff_id'] && $item['appointment_date']) {
                    if ($this->hasAppointmentOverlap(
                        $item['staff_id'],
                        $item['appointment_date'],
                        $item['start_time'],
                        $item['end_time']
                    )) {
                        return response()->json([
                            'success' => false,
                            'message' => __('One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.'),
                            'redirect' => route('cart')
                        ], 400);
                    }
                }
            }

            if (empty($orderItems)) {
                return response()->json([
                    'success' => false,
                    'message' => __('No services selected for booking.')
                ], 400);
            }

            // Map order items to the format expected by the pricing service
            $servicePricingItems = [];
            foreach ($orderItems as $item) {
                $servicePricingItems[] = [
                    'service_id' => $item['product_and_service_id'], // Map to the key expected by the service
                    'service_type' => $item['service_type'],
                    'quantity' => $item['quantity']
                ];
            }

            // Calculate pricing details using the service
            if ($request->has('discount_code') && $request->discount_code) {
                // Calculate with discount
                $pricingDetails = $priceService->calculateDiscountDetails($request->discount_code, $servicePricingItems, $request->customer_id ?? null);

                // get discount id
                $discount = Discount::where('code', $request->discount_code)->first();
                $discountId = $discount->id;
            } else {
                // Calculate without discount
                $pricingDetails = $priceService->calculateOrderTotals($servicePricingItems);
            }

            // Check if pricing calculation was successful
            if (!$pricingDetails['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $pricingDetails['message'] ?? __('Error calculating order totals')
                ], 400);
            }

            // Set default values for missing keys
            $pricingDetails['subtotal'] = $pricingDetails['subtotal'] ?? 0;
            $pricingDetails['vat_amount'] = $pricingDetails['vat_amount'] ?? 0;
            $pricingDetails['other_taxes_amount'] = $pricingDetails['other_taxes_amount'] ?? 0;
            $pricingDetails['total'] = $pricingDetails['total'] ?? 0;
            $pricingDetails['discount_amount'] = $pricingDetails['discount_amount'] ?? 0;
            // dd($pricingDetails);
            // Start a database transaction
            DB::beginTransaction();

            // Handle customer_id - if it doesn't exist in customers table, set to null
            $customerId = null;
            if ($request->customer_id) {
                // Check if customer_id exists in customers table
                $customerExists = DB::table('customers')->where('id', $request->customer_id)->exists();
                if ($customerExists) {
                    $customerId = $request->customer_id;
                }
            }

            // Handle point_of_sale_id - if not provided, get the main branch
            $pointOfSaleId = $request->point_of_sale_id;
            if (!$pointOfSaleId) {
                // Find the main branch point of sale
                $mainPointOfSale = DB::table('point_of_sales')->where('is_main_branch', 1)->first();
                if ($mainPointOfSale) {
                    $pointOfSaleId = $mainPointOfSale->id;
                } else {
                    // If no main branch found, get the first point of sale as fallback
                    $firstPointOfSale = DB::table('point_of_sales')->first();
                    if ($firstPointOfSale) {
                        $pointOfSaleId = $firstPointOfSale->id;
                    } else {
                        throw new \Exception(__('No point of sale found in the system.'));
                    }
                }
            }

            // Calculate total duration
            $totalDurationMinutes = 0;
            foreach ($orderItems as $item) {
                $totalDurationMinutes += $item['duration_minutes'] * $item['quantity'];
            }

            // Determine location type based on if any service is home type
            $locationType = 'salon';
            foreach ($orderItems as $item) {
                if ($item['service_type'] === 'home') {
                    $locationType = $locationType . '-home';
                    break;
                }
            }

            // Find the earliest start time and latest end time from order items
            $earliestStartTime = null;
            $latestEndTime = null;

            foreach ($orderItems as $item) {
                if (!empty($item['start_time'])) {
                    if (!$earliestStartTime || strtotime($item['start_time']) < strtotime($earliestStartTime)) {
                        $earliestStartTime = $item['start_time'];
                    }
                }

                if (!empty($item['end_time'])) {
                    if (!$latestEndTime || strtotime($item['end_time']) > strtotime($latestEndTime)) {
                        $latestEndTime = $item['end_time'];
                    }
                }
            }

            // If we don't have valid times, use default values
            if (!$earliestStartTime) {
                $earliestStartTime = date('Y-m-d H:i:s'); // Current time as fallback
            } else if (!strpos($earliestStartTime, ':')) {
                // If time doesn't have a format with colons, it might be just a date
                $earliestStartTime = date('Y-m-d H:i:s', strtotime($earliestStartTime));
            }

            if (!$latestEndTime) {
                // Use start time + total duration as fallback
                $latestEndTime = date('Y-m-d H:i:s', strtotime($earliestStartTime) + ($totalDurationMinutes * 60));
            } else if (!strpos($latestEndTime, ':')) {
                // If time doesn't have a format with colons, it might be just a date
                $latestEndTime = date('Y-m-d H:i:s', strtotime($latestEndTime));
            }

            // Prepare customer detail JSON
            if ($customerId) {
                $customer = Customer::find($customerId);
                $customerDetail = json_encode([
                    'id' => $customerId,
                    'name_en' => $customer->name_en,
                    'name_ar' => $customer->name_ar, // You might want to have separate fields for this
                    'email' => $customer->email,
                    'phone' => $request->phone
                ]);
            } else {
                $customerDetail = json_encode([
                    'id' => null,
                    'name_en' => 'Guest',
                    'name_ar' => 'ضيف', // You might want to have separate fields for this
                ]);
            }
            // Create the reservation
            $reservation = BookedReservation::create([
                'customer_id' => $customerId,
                'point_of_sale_id' => $pointOfSaleId,
                'reservation_date' => date('Y-m-d'), // Today's date for booking
                'start_time' => $earliestStartTime,
                'end_time' => $latestEndTime,
                'subtotal' => $pricingDetails['subtotal'],
                'vat_amount' => $pricingDetails['vat_amount'],
                'other_taxes_amount' => $pricingDetails['other_taxes_amount'],
                'total_price' => $pricingDetails['total'],
                'discount_amount' => $pricingDetails['discount_amount'],
                'discount_code' => $request->discount_code,
                'discount_id' => $discountId ?? null,
                'status' => 'pending',
                'notes' => $request->notes,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total_duration_minutes' => $totalDurationMinutes,
                'total_paid_cash' => $request->paymentMethod === 'cash' ? $pricingDetails['total'] : 0,
                'total_paid_online' => $request->paymentMethod === 'card' ? $pricingDetails['total'] : 0,
                'customer_detail' => $customerDetail,
                'payment_method' => $request->paymentMethod,
                'total_amount_paid' => 0, // Initially zero, update when payment is confirmed
                'booked_from' => 'website',
            ]);

            // Generate unique invoice number
            $invoiceNumber = generateUniqueInvoiceNumber('invoice_number');

            // Create an invoice associated with the reservation
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'point_of_sale_id' => $pointOfSaleId,
                'reservation_date' => date('Y-m-d'),
                'start_time' => $earliestStartTime,
                'end_time' => $latestEndTime,
                'subtotal' => $pricingDetails['subtotal'],
                'vat_amount' => $pricingDetails['vat_amount'],
                'other_taxes_amount' => $pricingDetails['other_taxes_amount'],
                'total_price' => $pricingDetails['total'],
                'discount_amount' => $pricingDetails['discount_amount'],
                'discount_code' => $request->discount_code,
                'discount_id' => null,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total_duration_minutes' => $totalDurationMinutes,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_paid_cash' => $request->paymentMethod === 'cash' ? $pricingDetails['total'] : 0,
                'total_paid_online' => $request->paymentMethod === 'card' ? $pricingDetails['total'] : 0,
                'customer_id' => $customerId,
                'customer_detail' => $customerDetail,
                'booked_reservation_id' => $reservation->id,
                'payment_method' => $request->paymentMethod,
                'total_amount_paid' => 0,
                'booked_from' => 'website',
            ]);

            $customer = Customer::find($customerId);
            if (!isset($customer->phone_number)) {
                $customer->phone_number = $request->phone;
            }
            if (!isset($customer->address)) {
                $customer->address = $request->address;
            }
            if (!isset($customer->latitude)) {
                $customer->latitude = $request->latitude;
            }
            if (!isset($customer->longitude)) {
                $customer->longitude = $request->longitude;
            }
            $customer->save();

            // Create reservation items and invoice items
            foreach ($orderItems as $index => $item) {
                // Validate staff_id - if it doesn't exist in staff table, set to null
                $staff = null;
                if (!empty($item['staff_id'])) {
                    $staff = Staff::find($item['staff_id']);
                    // Check if staff_id exists in staff table

                }

                // Get product details to retrieve name_en and name_ar
                $product = DB::table('product_and_services')->where('id', $item['product_and_service_id'])->first();
                $nameEn = $product ? $product->name_en ?? $item['name'] : $item['name'];
                $nameAr = $product ? $product->name_ar ?? $item['name'] : $item['name'];

                // Prepare staff detail JSON
                $staffDetail = json_encode([
                    'id' => $staff->id,
                    'name_en' => $staff->name_en,
                    'name_ar' => $staff->name_ar,
                ]);

                // Calculate item total and VAT
                $itemTotal = $item['final_price'] * $item['quantity'];
                $itemVat = ProductAndService::find($item['product_and_service_id'])->getVatAmount($item['service_type']);
                $itemOtherTaxes = ProductAndService::find($item['product_and_service_id'])->getOtherTaxesAmount($item['service_type']);

                // Create reservation item
                $reservationItem = BookedReservationItem::create([
                    'booked_reservation_id' => $reservation->id,
                    'customer_id' => $customerId,
                    'product_and_service_id' => $item['product_and_service_id'],
                    'name_en' => $nameEn,
                    'name_ar' => $nameAr,
                    'unique_id' => $item['unique_id'] ?? $item['product_and_service_id'] . '-' . ($item['service_type'] ?? 'salon'),
                    'image' => $item['image'] ?? null,
                    'service_location' => $item['service_type'] ?? 'salon',
                    'price' => $item['final_price'],
                    'duration' => $item['duration_minutes'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'status' => 'pending',
                    'vat_amount' => $itemVat,
                    'other_taxes_amount' => $itemOtherTaxes,
                    'appointment_date' => $item['appointment_date'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'staff_id' => $staff->id,
                    'staff_detail' => $staffDetail,
                    'location_type' => $item['service_type'] ?? 'salon',

                ]);

                // Create invoice item for each reservation item
                $invoiceItemNumber = generateUniqueInvoiceItemNumber($invoiceNumber);

                // Create invoice item for each reservation item
                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoiceItemNumber,
                    'product_and_service_id' => $item['product_and_service_id'],
                    'name_en' => $nameEn,
                    'name_ar' => $nameAr,
                    'unique_id' => $item['unique_id'] ?? $item['product_and_service_id'] . '-' . ($item['service_type'] ?? 'salon'),
                    'image' => $item['image'] ?? null,
                    'service_location' => $item['service_type'] ?? 'salon',
                    'price' => $item['final_price'],
                    'duration' => $item['duration_minutes'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'appointment_date' => $item['appointment_date'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'customer_id' => $customerId,
                    'staff_id' => $staff->id,
                    'staff_detail' => $staffDetail,
                    'location_type' => $item['service_type'] ?? 'salon',
                    'vat_amount' => $itemVat,
                    'other_taxes_amount' => $itemOtherTaxes
                ]);

                // Create tickets based on quantity
                // for ($i = 1; $i <= $item['quantity']; $i++) {
                //     generateTicketsForInvoiceItem($invoiceItemNumber . '' . $i, $pointOfSaleId, $invoiceItem->id);
                // }
            }

            // Handle items that are being rescheduled
            $rescheduleReservationIds = [];
            foreach ($orderItems as $item) {
                if (isset($item['is_reschedule']) && $item['is_reschedule'] === true &&
                    isset($item['pending_reservation_id']) && $item['pending_reservation_id']) {
                    $rescheduleReservationIds[] = $item['pending_reservation_id'];
                }
            }

            // Remove duplicates
            $rescheduleReservationIds = array_unique($rescheduleReservationIds);

            // Delete original reservations and related items
            foreach ($rescheduleReservationIds as $pendingReservationId) {
                try {
                    // Find the old reservation
                    $oldReservation = BookedReservation::find($pendingReservationId);

                    if ($oldReservation) {
                        // Find and delete associated invoice items
                        if ($oldReservation->invoice) {
                            InvoiceItem::where('invoice_id', $oldReservation->invoice->id)->delete();

                            // Delete invoice
                            $oldReservation->invoice->delete();
                        }

                        // Delete reservation items
                        BookedReservationItem::where('booked_reservation_id', $oldReservation->id)->delete();

                        // Delete the reservation
                        $oldReservation->delete();
                    }
                } catch (\Exception $e) {
                    // Log error but continue with the transaction
                    Log::error('Error deleting original reservation #' . $pendingReservationId . ': ' . $e->getMessage());
                }
            }

            // Commit the transaction
            DB::commit();

            // Clear cart session data
            session()->forget('checkoutData');

            // If payment method is HyperPay, handle differently
            if ($request->paymentMethod === 'hyperpay') {
                // Store the booking ID in session for later retrieval
                session(['current_booking_id' => $reservation->id]);

                // Return data needed for the payment form
                return response()->json([
                    'success' => true,
                    'payment_pending' => true,
                    'reservation_id' => $reservation->id,
                    'amount' => $pricingDetails['total'],
                    'redirect' => false // Don't redirect yet, payment needs to be processed
                ]);
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => __('Booking created successfully!'),
                'reservation_id' => $reservation->id,
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoiceNumber,
                'redirect' => route('booking.confirmation', ['id' => $reservation->id])
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('Validation failed'),
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log the error
            Log::error('Booking processing error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('An error occurred while processing your booking: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Request $request, $id)
    {
        try {
            // Find the reservation
            $reservation = BookedReservation::where('id', $id)->first();

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => __('Reservation not found')
                ], 404);
            }

            // Check if the reservation belongs to the authenticated user's customer
            $customerFromAuth = auth()->user()->customer ?? null;
            if (!$customerFromAuth || $reservation->customer_id != $customerFromAuth->id) {
                return response()->json([
                    'success' => false,
                    'message' => __('This reservation does not belong to your account')
                ], 403);
            }

            // Check if the reservation is already cancelled
            if ($reservation->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => __('This reservation is already cancelled')
                ], 400);
            }

            // Start transaction
            DB::beginTransaction();

            try {
                // Update reservation status and notes
                $reservation->status = 'cancelled';
                if ($request->has('note')) {
                    $reservation->notes = $request->note;
                }
                $reservation->save();


                // If there's an associated invoice, update its status too
                if ($reservation->invoice) {
                    $reservation->invoice->update(['status' => 'cancelled']);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => __('Booking cancelled successfully')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to cancel booking: ') . $e->getMessage()
            ], 500);
        }
    }

}
