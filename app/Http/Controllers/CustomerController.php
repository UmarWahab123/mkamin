<?php

namespace App\Http\Controllers;

use App\Models\BookedReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the customer's booked reservations.
     *
     * @return \Illuminate\View\View
     */
    public function bookings()
    {
        $user = Auth::user();

        // Get the customer associated with the authenticated user
        $customer = $user->customer;

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer profile not found.');
        }

        // Get all booked reservations for this customer with their items
        $bookings = BookedReservation::where('customer_id', $customer->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.customer.bookings', [
            'bookings' => $bookings
        ]);
    }
}
