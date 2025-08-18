<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }} #{{ $invoice->invoice_number }}</title>
    <!-- Saudi Riyal Currency Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/riyal_currancy.css') }}">
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            line-height: 1.3;
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .content {
            margin: 10px 0;
        }
        .line-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .total {
            font-weight: bold;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 10px;
        }
        .qr-code {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }
        .item-ticket {
            padding: 5px;
            border: 1px dashed #ccc;
            margin-bottom: 20px;
        }
        .item-header {
            text-align: center;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .rtl {
            direction: rtl;
            text-align: right;
        }

        @media print {
            body {
                font-size: 10pt;
            }
        }
    </style>
</head>

<body>
    <!-- Main Invoice -->
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ returnLogoForLightBackground() }}" style="width: 80px; margin: 10px 0;">
            <p style="margin: 5px 0;">{{ __('Invoice #') }} : {{ $invoice->invoice_number }}</p>
            <p style="margin: 5px 0;">{{ __('Date') }} : {{ $invoice->created_at->format('d-m-Y h:i:s A') }}</p>
        </div>
        <hr>

        <!-- Company Details -->
        <div class="content">
            <div class="line-item">
                <span>{{ __('Company Name') }} :</span>
                <span>{{ $invoice->pointOfSale->company->name }}</span>
            </div>
            <div class="line-item">
                <span>{{ __('Email') }} :</span>
                <span>{{ $invoice->pointOfSale->company->email }}</span>
            </div>
            <div class="line-item">
                <span>{{ __('Website') }} :</span>
                <span>{{ $invoice->pointOfSale->company->website }}</span>
            </div>
            @if ($invoice->notes)
                <div class="line-item">
                    <span>{{ __('Notes') }} :</span>
                    <span>{{ $invoice->notes }}</span>
                </div>
            @endif
        </div>
        <hr>

        <!-- Items Section -->
        <div class="content">
            <h3 style="margin: 10px 0;">{{ __('Services') }}</h3>
            @foreach ($invoice->items as $item)
                <div class="line-item">
                    <span>{{ $item->name }}</span>
                    <span>{{ $item->quantity }} x {{ number_format($item->price, 2) }} <span class="icon-saudi_riyal"></span></span>
                    <span>{{ number_format($item->price * $item->quantity, 2) }} <span class="icon-saudi_riyal"></span></span>
                </div>
            @endforeach
        </div>
        <hr>

        <!-- Totals Section -->
        <div class="content">
            <div class="line-item">
                <span>{{ __('Subtotal') }} :</span>
                <span>{{ number_format($invoice->subtotal, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            @if($invoice->discount_amount > 0)
                <div class="line-item">
                    <span>{{ __('Discount') }} :</span>
                    <span>-{{ number_format($invoice->discount_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
                </div>
            @endif
            <div class="line-item">
                <span>{{ __('VAT') }} ({{ config('app.vat_rate', 15) }}%):</span>
                <span>{{ number_format($invoice->vat_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            <div class="line-item">
                <span>{{ __('Other Taxes') }} :</span>
                <span>{{ number_format($invoice->other_taxes_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            <div class="line-item total">
                <span>{{ __('Total') }} :</span>
                <span>{{ number_format($invoice->total_price, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
        </div>
        <hr>

        <!-- Payment Details -->
        <div class="content">
            <h3 style="margin: 10px 0;">{{ __('Payment Details') }}</h3>
            <div class="line-item">
                <span>{{ __('Cash') }} :</span>
                <span>{{ number_format($invoice->total_paid_cash, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            <div class="line-item">
                <span>{{ __('Online') }} :</span>
                <span>{{ number_format($invoice->total_paid_online, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            <div class="line-item">
                <span>{{ __('Total Paid') }} :</span>
                <span>{{ number_format($invoice->getTotalPaidAttribute(), 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            @if($invoice->getChangeGivenAttribute() > 0)
                <div class="line-item">
                    <span>{{ __('Change') }} :</span>
                    <span>{{ number_format($invoice->getChangeGivenAttribute(), 2) }} <span class="icon-saudi_riyal"></span></span>
                </div>
            @endif
        </div>
        <hr>

        <!-- QR Code Section -->
        <div class="qr-code">
            <img src="{{ $qrCode }}" alt="{{ __('QR Code') }}" class="img-fluid"
            style="max-width: 150px;">
        </div>
        <hr>

        <!-- Footer Section -->
        <div class="footer">
            <p style="margin: 5px 0;">{{ __('Thank you for your business!') }}</p>
            <p style="margin: 5px 0;">{{ $invoice->pointOfSale->company->name }}</p>
            <p style="margin: 5px 0;">{{ __('VAT') }} : {{ $invoice->pointOfSale->company->tax_number }}</p>
        </div>
    </div>

    <!-- Individual Item Invoices (one per quantity) -->
    @foreach ($invoice->items as $item)
        @for ($i = 1; $i <= $item->quantity; $i++)
            <div class="page-break"></div>
            <div class="container item-ticket">
                <!-- Header Section -->
                <div class="header">
                    <img src="{{ returnLogoForLightBackground() }}" style="width: 80px; margin: 10px 0;">
                    <p style="margin: 5px 0;">{{ __('Invoice') }} #{{ $invoice->invoice_number }}</p>
                    <p style="margin: 5px 0;">{{ __('Item Receipt') }} #{{ $i }}</p>
                    <p style="margin: 5px 0;">{{ __('Date') }} : {{ $invoice->created_at->format('d-m-Y h:i:s A') }}</p>
                </div>
                <hr>

                <!-- Company Details -->
                <div class="content">
                    <div class="line-item">
                        <span>{{ __('Company Name') }} :</span>
                        <span>{{ $invoice->pointOfSale->company->name }}</span>
                    </div>
                    @if($item->staff_id)
                        <div class="line-item">
                            <span>{{ __('Staff') }} :</span>
                            <span>{{ $item->staff->name ?? 'N/A' }}</span>
                        </div>
                    @endif
                    <div class="line-item">
                        <span>{{ __('Appointment') }} :</span>
                        <span>{{ $item->appointment_date }} {{ $item->time_range }}</span>
                    </div>
                </div>
                <hr>

                <!-- Service Details -->
                <div class="content">
                    <h3 style="margin: 10px 0;">{{ __('Service Details') }}</h3>
                    <div class="line-item">
                        <span>{{ __('Service') }} :</span>
                        <span>{{ $item->name }}</span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Price') }} :</span>
                        <span>{{ number_format($item->price, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Duration') }} :</span>
                        <span>{{ $item->duration }} {{ __('minutes') }}</span>
                    </div>
                </div>
                <hr>

                <!-- Tax Details for this item -->
                <div class="content">
                    <div class="line-item">
                        <span>{{ __('VAT') }} ({{ config('app.vat_rate', 15) }}%):</span>
                        <span>{{ number_format($item->vat_amount / $item->quantity, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Other Taxes') }} :</span>
                        <span>{{ number_format($item->other_taxes_amount / $item->quantity, 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                    <div class="line-item total">
                        <span>{{ __('Total') }} :</span>
                        <span>{{ number_format(($item->price + ($item->vat_amount / $item->quantity) + ($item->other_taxes_amount / $item->quantity)), 2) }} <span class="icon-saudi_riyal"></span></span>
                    </div>
                </div>
                <hr>

                <!-- Item QR Code -->
                <div class="qr-code">
                    <img src="{{ $itemQrCodes[$item->id][$i] }}" alt="{{ __('QR Code') }}" class="img-fluid"
                    style="max-width: 150px;">
                </div>
                <hr>

                <!-- Footer Section -->
                <div class="footer">
                    <p style="margin: 5px 0;">{{ __('Thank you for your business!') }}</p>
                    <p style="margin: 5px 0;">{{ $invoice->pointOfSale->company->name }}</p>
                    <p style="margin: 5px 0;">{{ __('VAT') }} : {{ $invoice->pointOfSale->company->tax_number }}</p>
                </div>
            </div>
        @endfor
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
