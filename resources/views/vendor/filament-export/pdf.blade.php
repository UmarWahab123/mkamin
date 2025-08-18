<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ asset('assets/css/riyal_currancy.css') }}">
    <title>{{ $fileName }}</title>
    <style type="text/css" media="all">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }

        html {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border-radius: 10px 10px 10px 10px;
        }

        table td,
        th {
            border-color: #ededed;
            border-style: solid;
            border-width: 1px;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        table th {
            font-weight: bolder;
        }

        .summary {
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .summary-label {
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    {{-- <h1>{{ $fileName }}</h1> --}}
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ $date_range['range_label'] }}
            @if($date_range['from'] && $date_range['until'] && $date_range['range'] !== 'all_time')
                ({{ $date_range['from'] }} {{ __('to') }} {{ $date_range['until'] }})
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>
                        {{ $column->getLabel() }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($columns as $column)
                        <td>
                            {!! $row[$column->getName()] !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>{{ __('Summary') }}</h3>
        @if ($columns->contains(fn($column) => $column->getName() === 'subtotal'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of Subtotal') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['subtotal'], 2) }}</span>
            </div>
        @endif

        @if ($columns->contains(fn($column) => $column->getName() === 'discount_amount'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of Discount Amount') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['discount_amount'], 2) }}</span>
            </div>
        @endif

        @if ($columns->contains(fn($column) => $column->getName() === 'vat_amount'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of VAT Amount') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['vat_amount'], 2) }}</span>
            </div>
        @endif

        @if ($columns->contains(fn($column) => $column->getName() === 'other_total_discount_amount'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of Other Discount') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['other_total_discount_amount'], 2) }}</span>
            </div>
        @endif

        @if ($columns->contains(fn($column) => $column->getName() === 'total_price'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of Price') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['total_price'], 2) }}</span>
            </div>
        @endif

        @if ($columns->contains(fn($column) => $column->getName() === 'total_amount_paid'))
            <div class="summary-row">
                <span class="summary-label">{{ __('Total of Amount Paid') }}: </span>
                <span>{!! $currency !!} {{ number_format($summary['total_amount_paid'], 2) }}</span>
            </div>
        @endif
    </div>
</body>

</html>
