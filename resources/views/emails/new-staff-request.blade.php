<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('New Staff Request') }}</title>
    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'Cairo', 'Segoe UI', Arial, sans-serif" : "'Segoe UI', Arial, sans-serif" }};
            line-height: 1.6;
            color: #2d3748;
            margin: 0;
            padding: 20px;
            background-color: #f7fafc;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #edf2f7;
        }

        .logo img {
            max-width: 180px;
            height: auto;
        }

        .content {
            margin-bottom: 30px;
            background-color: #f8fafc;
            padding: 25px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .content h2 {
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .content p {
            margin: 12px 0;
            color: #4a5568;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .content strong {
            color: #2d3748;
            font-weight: 600;
            display: inline-block;
            min-width: 80px;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.2s;
            box-shadow: 0 2px 4px rgba(66, 153, 225, 0.2);
        }

        .button:hover {
            background-color: #3182ce;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #718096;
            padding-top: 20px;
            border-top: 2px solid #edf2f7;
        }

        .info-row {
            display: flex;
            align-items: center;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #edf2f7;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #2d3748;
            min-width: 100px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .info-value {
            color: #4a5568;
            flex: 1;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .highlight {
            background-color: #ebf8ff;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 4px solid #4299e1;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
    </style>
    @if (app()->getLocale() == 'ar')
        <script>
            .info - label::after {
                content: ':';
                margin - right: 8 px;
                margin - left: 0;
            }
        </script>
    @else
        <script>
            .info - label::after {
                content: ':';
                margin - left: 8 px;
                margin - right: 0;
            }
        </script>
    @endif
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}">
        </div>

        <div class="content">
            <h2>{{ __('New Staff Request') }}</h2>

            <div class="highlight">
                <p style="margin: 0; color: #2c5282;">
                    {{ __('A new staff member has requested to join your team. Please review their details below:') }}
                </p>
            </div>

            <div class="info-row">
                <span class="info-label">{{ __('Name') }}</span>
                <span class="info-value">{{ $staff->name_en }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">{{ __('Email') }}</span>
                <span class="info-value">{{ $staff->email }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">{{ __('Phone') }}</span>
                <span class="info-value">{{ $staff->phone_number }}</span>
            </div>

            @if ($staff->address)
                <div class="info-row">
                    <span class="info-label">{{ __('Address') }}</span>
                    <span class="info-value">{{ $staff->address }}</span>
                </div>
            @endif

            @if ($staff->staffPosition)
                <div class="info-row">
                    <span class="info-label">{{ __('Position') }}</span>
                    <span class="info-value">{{ $staff->staffPosition->name }}</span>
                </div>
            @endif
        </div>

        <div class="button-container">
            <a href="{{ url('/admin/staff/' . $staff->id . '/edit') }}" class="button">
                {{ __('Review Staff Request') }}
            </a>
        </div>

        <div class="footer">
            <p>{{ __('This is an automated message. Please do not reply to this email.') }}</p>
            <p style="margin-top: 10px; font-size: 12px; color: #a0aec0;">© {{ date('Y') }}
                {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</body>

</html>
