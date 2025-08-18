<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\{Seller, TaxNumber, InvoiceDate, InvoiceTotalAmount, InvoiceTaxAmount};

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found.');
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

        // Generate QR codes for each item receipt with their invoice numbers
        $itemQrCodes = [];
        foreach ($invoice->items as $item) {
            for ($i = 1; $i <= $item->quantity; $i++) {
                $itemQrCodes[$item->id][$i] = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
                    ->generate($item->invoice_number);
            }
        }

        return view('reservations.invoice', compact('invoice', 'qrCode', 'itemQrCodes'));
    }
}
