<!DOCTYPE html>
<!--
    This template uses the Invoice model data passed by the controller.
-->
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">

    <title>{{ __('Invoice') }}</title>
    <!-- Saudi Riyal Currency Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/riyal_currancy.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            width: 80mm;
        }

        .container {
            padding: 0px 10px;
            width: 100%;
        }

        .header,
        .footer {
            text-align: center;
        }

        .content {
            margin-top: 2px;
        }

        .line-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .line-item span:first-child {
            text-align: start;
        }

        .line-item span:last-child {
            text-align: end;
        }

        .total {
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .page-break {
            page-break-after: always;
        }

        .qr-code {
            text-align: center;
            margin: 10px 0;
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
        }

        /* RTL specific styles */
        html[dir="rtl"] .content {
            text-align: right;
        }

        html[dir="rtl"] .line-item span:first-child {
            text-align: right;
        }

        html[dir="rtl"] .line-item span:last-child {
            text-align: left;
        }

        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
        }

        .pe-1 {
            padding-right: 3px;
        }

        /* Text alignment classes for RTL/LTR support */
        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        html[dir="rtl"] .text-start {
            text-align: right;
        }

        html[dir="rtl"] .text-end {
            text-align: left;
        }
    </style>
</head>

<body>
    @php
        $customer = json_decode($invoice->customer_detail, true);
        $customerName = $customer['name_' . app()->getLocale()];
    @endphp
    <!-- Main Invoice -->
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ returnLogoForLightBackground() }}" style="width: 80px; margin: 10px 0;">
            <p style="margin: 5px 0;">{{ __('VAT #') }} : {{ $invoice->pointOfSale->company->tax_number }}</p>
            <p style="margin: 5px 0;">{{ __('Invoice #') }} : {{ $invoice->invoice_number }}</p>
            <p style="margin: 5px 0;">{{ __('Date') }} : {{ $invoice->created_at->format('d-m-Y h:i:s A') }}</p>
            @if (isset($invoice->pointOfSale->company->address))
                <p style="margin: 5px 0;">{{ __('Address') }} : {!! nl2br(e($invoice->pointOfSale->company->address)) !!}</p>
            @endif
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
            <div class="line-item">
                <span>{{ __('Customer') }} :</span>
                <span>{{ $customerName }}</span>
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
            {{-- <h3 style="margin: 10px 0;">{{ __('Services') }}</h3> --}}
            <table style="width: 100%; border: none; background: transparent;">
                <tr style="font-weight: bold;">
                    <td style="padding: 5px 0; border: none;" class="text-start">{{ __('Services') }}</td>
                    <td style="padding: 5px 0; border: none; text-align: center;">{{ __('Qty') }}</td>
                    <td style="padding: 5px 0; border: none;" class="text-end">{{ __('Price') }}</td>
                </tr>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td style="padding: 5px 0; border: none;" class="text-start">
                            {{ $item->name }}<br>
                            {{ __($item->service_location) }} <span style="margin: 0 5px;">-</span>
                            @php
                                $staffDetail = json_decode($item->staff_detail, true);
                                echo $staffDetail ? $staffDetail['name_' . app()->getLocale()] : 'N/A';
                            @endphp

                            @if (isset($item->other_discount_value) && $item->other_discount_value > 0)
                                <br>
                                <span style="color: #00cc44; font-size: 0.9em;">
                                    {{ __('Discount') }}:
                                    @if ($item->other_discount_type === 'percentage')
                                        {{ $item->other_discount_value }}% =
                                    @endif
                                    {{ number_format($item->other_discount_amount, 2) }} <span
                                        class="icon-saudi_riyal"></span>
                                </span>
                            @endif
                        </td>
                        <td style="padding: 5px 0; border: none; text-align: center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding: 5px 0; border: none;" class="text-end">
                            {{ number_format($item->price * $item->quantity - ($item->other_discount_amount ?? 0), 2) }}
                            <span class="icon-saudi_riyal"></span>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <hr>

        <!-- Totals Section -->
        <div class="content">
            <div class="line-item">
                <span>{{ __('Subtotal') }} :</span>
                <span>{{ number_format($invoice->subtotal, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            @if ($invoice->discount_amount > 0)
                <div class="line-item">
                    <span>{{ __('Discount') }} :</span>
                    <span>-{{ number_format($invoice->discount_amount, 2) }} <span
                            class="icon-saudi_riyal"></span></span>
                </div>
            @endif

            @if ($invoice->other_total_discount_amount > 0)
                <div class="line-item">
                    <span>{{ __('Additional Discount') }} :</span>
                    <span>-{{ number_format($invoice->other_total_discount_amount, 2) }} <span
                            class="icon-saudi_riyal"></span></span>
                </div>
            @endif
            <div class="line-item">
                <span>{{ __('VAT') }} ({{ config('app.vat_rate', 15) }}%):</span>
                <span>{{ number_format($invoice->vat_amount, 2) }} <span class="icon-saudi_riyal"></span></span>
            </div>
            <div class="line-item">
                <span>{{ __('Other Taxes') }} :</span>
                <span>{{ number_format($invoice->other_taxes_amount, 2) }} <span
                        class="icon-saudi_riyal"></span></span>
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
                <span>{{ number_format($invoice->total_paid_cash + $invoice->total_paid_online, 2) }} <span
                        class="icon-saudi_riyal"></span></span>
            </div>
            @if ($invoice->total_paid_cash + $invoice->total_paid_online - $invoice->total_price > 0)
                <div class="line-item">
                    <span>{{ __('Change') }} :</span>
                    <span>{{ number_format($invoice->total_paid_cash + $invoice->total_paid_online - $invoice->total_price, 2) }}
                        <span class="icon-saudi_riyal"></span></span>
                </div>
            @endif
        </div>
        <hr>

        <!-- QR Code Section -->
        <div class="qr-code">
            <img src="{{ $qrCode }}" alt="{{ __('QR Code') }}" class="img-fluid" style="max-width: 150px;">
        </div>
        <hr>

        <!-- Footer Section -->
        <div class="footer">
            <p style="margin: 5px 0;">{{ __('Thank you for your business!') }}</p>
            <p style="margin: 5px 0;">{{ $invoice->pointOfSale->company->name }}</p>
            {{-- <p style="margin: 5px 0;">{{ __('VAT') }} : {{ $invoice->pointOfSale->company->tax_number }}</p> --}}
        </div>
    </div>

    <!-- Individual Item Invoices (one per quantity) -->
    @foreach ($invoice->items as $item)
        @foreach ($item->tickets as $ticket)
            <div class="page-break"></div>
            <div class="container">
                <!-- Header Section -->
                <div class="header">
                    <img src="{{ returnLogoForLightBackground() }}" style="width: 80px; margin: 10px 0;">
                    <p style="margin: 5px 0;">{{ $invoice->pointOfSale->company->tax_number }}</p>
                    <p style="margin: 5px 0;">{{ $ticket->code }}</p>
                    <p style="margin: 5px 0;">{{ __('Date') }} :
                        {{ $invoice->created_at->format('d-m-Y h:i:s A') }}</p>

                    @if (isset($invoice->pointOfSale->company->address))
                        <p style="margin: 5px 0;">{{ __('Address') }} : {!! nl2br(e($invoice->pointOfSale->company->address)) !!}</p>
                    @endif
                </div>
                <hr>

                <!-- Company Details -->
                <div class="content">
                    <div class="line-item">
                        <span>{{ __('Company Name') }} :</span>
                        <span>{{ $invoice->pointOfSale->company->name }}</span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Branch') }} :</span>
                        <span>{{ $invoice->pointOfSale->name }}</span>
                    </div>
                    @if ($item->staff_id)
                        <div class="line-item">
                            <span>{{ __('Staff') }} :</span>
                            <span>
                                @php
                                    $staffDetail = json_decode($item->staff_detail, true);
                                    echo $staffDetail ? $staffDetail['name_' . app()->getLocale()] : 'N/A';
                                @endphp
                            </span>
                        </div>
                    @endif
                    <div class="line-item">
                        <span>{{ __('Customer') }} :</span>
                        <span>{{ $customerName }}</span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Appointment') }} :</span>
                        <span>{{ $item->appointment_date }}
                            {{ $item->start_time ? date('h:i A', strtotime($item->start_time)) : '' }}</span>
                    </div>
                    @if ($ticket->ticket_detail)
                        <div class="line-item">
                            <span>{{ __('Timing') }} :</span>
                            <span>
                                @php
                                    $ticketDetail = json_decode($ticket->ticket_detail, true);
                                    $startTime = isset($ticketDetail['start_time']) ? date('h:i A', strtotime($ticketDetail['start_time'])) : '';
                                    $endTime = isset($ticketDetail['end_time']) ? date('h:i A', strtotime($ticketDetail['end_time'])) : '';
                                @endphp
                                @if($startTime && $endTime)
                                    {{ $startTime }} - {{ $endTime }}
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="line-item">
                        <span>{{ __('Location') }} :</span>
                        <span>{{ __($item->service_location) }}</span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Service') }} :</span>
                        <span>{{ $item->name }}</span>
                    </div>
                </div>
                <hr>

                <!-- Item Details -->
                {{-- <div class="content">
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
                        <span>{{ __('VAT') }} :</span>
                        <span>{{ number_format($item->vat_amount / $item->quantity, 2) }} <span
                                class="icon-saudi_riyal"></span></span>
                    </div>
                    <div class="line-item">
                        <span>{{ __('Other Taxes') }} :</span>
                        <span>{{ number_format($item->other_taxes_amount / $item->quantity, 2) }} <span
                                class="icon-saudi_riyal"></span></span>
                    </div>
                    <div class="line-item total">
                        <span>{{ __('Total') }} :</span>
                        <span>{{ number_format($item->price + $item->vat_amount / $item->quantity + $item->other_taxes_amount / $item->quantity, 2) }}
                            <span class="icon-saudi_riyal"></span></span>
                    </div>
                </div>
                <hr> --}}

                <!-- QR Code Section -->
                <div class="qr-code">
                    {{ generateQrCode($ticket->code) }}
                </div>
                <hr>

                <!-- Footer Section -->
                <div class="footer">
                    <p style="margin: 5px 0;">{{ __('Thank you for your business!') }}</p>
                    <p style="margin: 5px 0;">{{ $invoice->pointOfSale->company->name }}</p>
                    {{-- <p style="margin: 5px 0;">{{ __('VAT') }} : {{ $invoice->pointOfSale->company->tax_number }} </p> --}}
                </div>
            </div>
        @endforeach
    @endforeach
</body>

</html>
