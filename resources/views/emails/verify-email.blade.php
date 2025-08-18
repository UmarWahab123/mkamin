<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 130px;
            height: auto;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $params = ['token' => $verificationToken];
            if (isset($newEmail)) {
                $params['newEmail'] = $newEmail;
            }
        @endphp
        <div class="logo">
            <img src="{{returnLogoForLightBackground()}}" alt="{{ config('app.name') }}">
        </div>

        <div class="content">
            <h2>Email Verification</h2>

            <p>Hello {{ $user->name }},</p>

            <p>Thank you for registering with {{ config('app.name') }}. Please click the button below to verify your email address.</p>
            <div style="text-align: center;">
                <a href="{{ route('verification.verify', $params) }}" class="button">Verify Email Address</a>
            </div>

            @if(isset($newEmail))
            <p>After verification, your email will be updated to: {{ $newEmail }}</p>
            @endif

            <p>If you did not create an account, no further action is required.</p>

            <p>Thanks,<br>
            {{ config('app.name') }} Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
