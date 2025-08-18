<?php

namespace App\Http\Controllers;

use App\Models\BookedReservation;
use Illuminate\Http\Request;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\{Seller, TaxNumber, InvoiceDate, InvoiceTotalAmount, InvoiceTaxAmount};


class ReservationInvoiceController extends Controller
{
    public function show($id)
    {
        // Get the reservation data
        $reservation = BookedReservation::findOrFail($id);

        // Get the related invoice
        $invoice = $reservation->invoice;

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found for this reservation.');
        }

        $company = $invoice->pointOfSale->company;

        // Generate QR code for the main invoice
        $qrCode = GenerateQrCode::fromArray([
            new Seller($company->name), // seller name
            new TaxNumber($company->tax_number), // seller tax number
            new InvoiceDate($invoice->created_at), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($invoice->total_price), // invoice total amount
            new InvoiceTaxAmount($invoice->vat_amount ?? 0) // invoice tax amount
            // new InvoiceTaxAmount($invoice->vat_amount + $invoice->other_taxes_amount) // total tax amount (VAT + other taxes)
        ])->render();


        return view('reservations.invoice', compact('invoice', 'qrCode'));
    }
}
