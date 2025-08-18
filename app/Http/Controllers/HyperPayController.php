<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BookedReservation;

class HyperPayController extends Controller
{
    /**
     * Prepare checkout - Step 1
     */
    public function prepareCheckout(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|numeric',
            'payment_method' => 'required|in:visa_mastercard,mada'
        ]);
        $reservation = BookedReservation::find($request->input('reservation_id'));

        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Reservation not found']);
        } else {
            foreach ($reservation->items as $index => $item) {
                if ($item->staff_id && $item->appointment_date) {
                    if ($this->hasAppointmentOverlap(
                        $item->staff_id,
                        $item->appointment_date,
                        $item->start_time,
                        $item->end_time,
                        $item->id
                    )) {
                        return response()->json([
                            'success' => false,
                            'message' => __('One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.'),
                            'redirect' => route('booking.confirmation', ['id' => $reservation->id])
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => __('One of your selected service times has not been set. Please reschedule your appointment.'),
                        'redirect' => route('booking.confirmation', ['id' => $reservation->id])
                    ], 400);
                }
            }
            $amount = $reservation->total_price;
        }

        $reservationId = $request->input('reservation_id');
        $paymentMethod = $request->input('payment_method');

        // Determine which entity ID to use based on payment method
        $entityId = $paymentMethod === 'mada'
            ? config('services.hyperpay.entity_id_mada')
            : config('services.hyperpay.entity_id_visa_mastercard');

        // Store reservation ID in session
        session(['current_booking_id' => $reservationId]);

        // Prepare API request
        $url = config('services.hyperpay.base_url') . "checkouts";
        $data = "entityId=" . $entityId .
            "&amount=" . number_format($amount, 2, '.', '') .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&merchantTransactionId=BOOKING-" . time() . "-" . $reservationId .
            "&integrity=true";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization:Bearer ' . config('services.hyperpay.access_token')
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('HyperPay checkout error: ' . curl_error($ch));
            return response()->json(['success' => false, 'message' => curl_error($ch)]);
        }

        curl_close($ch);

        // Parse response
        $response = json_decode($responseData, true);

        // Log response for debugging - correctly formatted with array context
        Log::info('HyperPay checkout request and response', [
            'request_url' => $url,
            'payment_method' => $paymentMethod,
            'entity_id' => $entityId,
            'response' => $response ?: []
        ]);

        if (isset($response['id'])) {
            // Store data in session
            session([
                'hyperpay_checkout_id' => $response['id'],
                'hyperpay_integrity' => $response['integrity'] ?? null,
                'payment_method' => $paymentMethod
            ]);

            return response()->json([
                'success' => true,
                'checkoutId' => $response['id'],
                'integrity' => $response['integrity'] ?? null,
                'payment_method' => $paymentMethod
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['result']['description'] ?? 'Unknown error'
        ], 400);
    }

    /**
     * Display payment form - Step 2
     */
    public function showPaymentForm()
    {
        $checkoutId = session('hyperpay_checkout_id');
        $integrity = session('hyperpay_integrity');
        $paymentMethod = session('payment_method', 'visa_mastercard');

        if (!$checkoutId) {
            return redirect()->route('cart')->with('error', 'Payment session expired. Please try again.');
        }

        return view('pages.payment.hyperpay-form', [
            'checkoutId' => $checkoutId,
            'integrity' => $integrity,
            'payment_method' => $paymentMethod
        ]);
    }

    /**
     * Handle payment response - Step 3
     */
    public function handleResponse(Request $request)
    {
        // Log all incoming parameters
        Log::info('HyperPay response received', [
            'all_parameters' => $request->all(),
            'query_parameters' => $request->query(),
            'resource_path' => $request->query('resourcePath'),
            'checkout_id' => $request->query('id'),
            'session_data' => [
                'checkout_id' => session('hyperpay_checkout_id'),
                'reservation_id' => session('current_booking_id'),
                'payment_method' => session('payment_method')
            ]
        ]);

        $resourcePath = $request->query('resourcePath');

        if (!$resourcePath) {
            Log::warning('HyperPay response missing resourcePath');
            return redirect()->route('customer.bookings')->with('error', 'Invalid payment response - Missing resource path');
        }

        // Get payment status
        $baseUrl = rtrim(config('services.hyperpay.base_url'), '/');
        $resourcePath = ltrim($resourcePath, '/');

        // Remove duplicate v1 segment if present
        if (strpos($baseUrl, 'v1') !== false && strpos($resourcePath, 'v1/') === 0) {
            $resourcePath = substr($resourcePath, 3); // Remove 'v1/' from the beginning
        }

        // Get the payment method from session
        $paymentMethod = session('payment_method', 'visa_mastercard'); // Default to visa_mastercard if not set

        // Determine which entity ID to use based on payment method
        $entityId = $paymentMethod === 'mada'
            ? config('services.hyperpay.entity_id_mada')
            : config('services.hyperpay.entity_id_visa_mastercard');

        $url = $baseUrl . '/' . $resourcePath;
        $url .= "?entityId=" . $entityId;

        // Log the payment status URL
        Log::info('Checking payment status', [
            'url' => $url,
            'payment_method' => $paymentMethod,
            'entity_id' => $entityId
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization:Bearer ' . config('services.hyperpay.access_token')
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('HyperPay payment status error: ' . curl_error($ch));
            return redirect()->route('customer.bookings')->with('error', 'Error checking payment status');
        }

        // Log raw response for debugging
        Log::info('HyperPay raw payment response', [
            'raw_response' => $responseData
        ]);

        curl_close($ch);

        // Parse response
        $result = json_decode($responseData, true);
        Log::info('HyperPay payment result', $result ?: []);

        // Get the reservation ID from session
        $reservationId = session('current_booking_id');

        if (!$reservationId) {
            Log::warning('HyperPay payment session expired - No reservation ID in session');
            return redirect()->route('customer.bookings')->with('error', 'Payment session expired');
        }

        // Check payment status
        $successCodes = ['000.000.000', '000.100.110', '000.000.100', '000.100.100', '000.100.112'];
        $resultCode = $result['result']['code'] ?? 'no_code';

        Log::info('HyperPay payment status check', [
            'result_code' => $resultCode,
            'is_success' => in_array($resultCode, $successCodes),
            'reservation_id' => $reservationId
        ]);

        if (isset($result['result']['code']) && in_array($result['result']['code'], $successCodes)) {
            // Payment successful - update reservation
            $reservation = BookedReservation::find($reservationId);

            if ($reservation) {
                $reservation->update([
                    'status' => 'confirmed',
                    'payment_method' => 'hyperpay',
                    'total_paid_online' => $reservation->total_price,
                    'total_amount_paid' => $reservation->total_price,
                    'booked_from' => 'website'
                ]);

                // Update invoice if exists
                if ($reservation->invoice) {
                    $reservation->invoice->update([
                        'status' => 'confirmed',
                        'payment_method' => 'hyperpay',
                        'total_paid_online' => $reservation->total_price,
                        'total_amount_paid' => $reservation->total_price,
                        'booked_from' => 'website'
                    ]);
                }

                foreach ($reservation->invoice->items as $item) {
                    for ($i = 1; $i <= $item['quantity']; $i++) {
                        generateTicketsForInvoiceItem($item['invoice_number'] . '' . $i, $reservation->point_of_sale_id, $item->id);
                    }
                }


                if (isset($reservation->discount_id)) {
                    $reservation->discount->incrementUsed();
                }
                // Clear session data
                session()->forget(['hyperpay_checkout_id', 'hyperpay_integrity', 'current_booking_id']);

                return redirect()->route('booking.confirmation', ['id' => $reservationId])
                    ->with('success', 'Payment completed successfully!');
            }
        }

        // Payment failed
        $errorMessage = $result['result']['description'] ?? 'Unknown payment error';

        return redirect()->route('customer.bookings')
            ->with('error', "Payment failed: {$errorMessage}");
    }

    /**
     * Handle HyperPay webhook notifications (optional, for async notifications)
     */
    public function handleNotification(Request $request)
    {
        // Log notification for debugging
        Log::info('HyperPay payment notification:', $request->all());

        // Process notification (update order status, etc.)
        // This is called asynchronously by HyperPay

        return response()->json(['status' => 'OK']);
    }
}
