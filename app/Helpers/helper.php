<?php

use App\Models\Company;
use App\Models\PointOfSale;
use App\Models\ReservationSetting;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\TimeInterval;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailVerification;
use App\Models\HeaderSettings;
use App\Models\Ticket;

if (!function_exists('createStaffTimeIntervals')) {
    /**
     * Create time intervals for a staff member
     * Only updates/creates intervals for dates that are from tomorrow onwards
     * Also deletes any time intervals beyond the specified number of days
     *
     * @param int $staffId The ID of the staff member
     * @param int $numberOfDays Number of days to create intervals for (default: 10)
     * @return void
     */
    function createStaffTimeIntervals(int $staffId, int $numberOfDays = 10): void
    {
        $numberOfDays = Setting::get('advance_booking_days_limit', $numberOfDays);
        try {
            $staff = Staff::find($staffId);
            if (!$staff) {
                Log::error("Staff with ID {$staffId} not found");
                return;
            }

            // Get default values from settings
            $defaultClosedDay = $staff->default_closed_day;
            $defaultStartTime = $staff->default_start_time;
            $defaultEndTime = $staff->default_end_time;
            $defaultHomeVisitDays = $staff->default_home_visit_days ?? [];

            // Create time intervals starting from today
            $today = now()->startOfDay();

            // Calculate tomorrow for update restriction (instead of 7 days)
            $futureDate = $today->copy()->addDay();

            for ($i = 0; $i < $numberOfDays; $i++) {
                try {
                    $date = $today->copy()->addDays($i);
                    $dayOfWeek = $date->dayOfWeek;

                    // Check if it's the closed day based on settings
                    $isClosed = ($dayOfWeek == (int)$defaultClosedDay);

                    // Check if home visits are allowed on this day
                    $canVisitHome = in_array((string)$dayOfWeek, $defaultHomeVisitDays);

                    // Check if a TimeInterval already exists for this date and staff
                    $existingInterval = TimeInterval::where('date', $date->toDateString())
                        ->where('timeable_id', $staffId)
                        ->where('timeable_type', Staff::class)
                        ->first();

                    // Create time interval data
                    $timeIntervalData = [
                        'date' => $date->toDateString(),
                        'day_of_week' => (string) $dayOfWeek,
                        'start_time' => $defaultStartTime,
                        'end_time' => $defaultEndTime,
                        'is_closed' => $isClosed,
                        'can_visit_home' => $canVisitHome,
                        'timeable_id' => $staffId,
                        'timeable_type' => Staff::class,
                    ];

                    // Only update existing records if the date is tomorrow or later
                    if ($existingInterval) {
                        // Only update if the date is at least tomorrow
                        // if ($date->greaterThanOrEqualTo($futureDate)) {
                        //     $existingInterval->update($timeIntervalData);
                        // }
                    } else {
                        // Always create new intervals, even for today
                        TimeInterval::create($timeIntervalData);
                        Log::info("Created new time interval for date {$date->toDateString()}");
                    }
                } catch (\Exception $e) {
                    // Log the error or handle it appropriately
                    Log::error('Error creating time interval: ' . $e->getMessage());
                }
            }

            // Delete any time intervals beyond the specified number of days
            $lastAllowedDate = $today->copy()->addDays($numberOfDays - 1);
            try {
                $deletedCount = TimeInterval::where('timeable_id', $staffId)
                    ->where('timeable_type', Staff::class)
                    ->where('date', '>', $lastAllowedDate->toDateString())
                    ->delete();

                if ($deletedCount > 0) {
                    Log::info("Deleted {$deletedCount} time intervals beyond {$numberOfDays} days for Staff ID {$staffId}");
                }
            } catch (\Exception $e) {
                Log::error('Error deleting time intervals for staff: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Error in createStaffTimeIntervals: ' . $e->getMessage());
        }
    }
}

if (!function_exists('createPosTimeIntervals')) {
    /**
     * Create time intervals for a point of sale
     * Only updates existing intervals for dates that are from tomorrow onwards
     * Also deletes any settings beyond the specified number of days
     *
     * @param int $pointOfSaleId The ID of the point of sale
     * @param int $numberOfDays Number of days to create intervals for (default: 10)
     * @return void
     */
    function createPosTimeIntervals(int $pointOfSaleId, int $numberOfDays = 10): void
    {
        try {
            $numberOfDays = Setting::get('advance_booking_days_limit', $numberOfDays);
            // Get default values from settings
            $defaultClosedDay = Setting::get('default_closed_day', '5'); // Default to Friday (5)
            $defaultStartTime = Setting::get('default_start_time', '09:00');
            $defaultEndTime = Setting::get('default_end_time', '17:00');

            // Find the point of sale
            $pointOfSale = PointOfSale::find($pointOfSaleId);
            if (!$pointOfSale) {
                Log::error("Point of sale with ID {$pointOfSaleId} not found");
                return;
            }

            // Create time intervals starting from today
            $today = now()->startOfDay();

            // Calculate tomorrow for update restriction (instead of 7 days)
            $futureDate = $today->copy()->addDay();

            for ($i = 0; $i < $numberOfDays; $i++) {
                try {
                    $date = $today->copy()->addDays($i);
                    $dayOfWeek = $date->dayOfWeek;

                    // Check if it's the closed day based on settings
                    $isClosed = ($dayOfWeek == (int)$defaultClosedDay);

                    // Check if a setting already exists for this date and POS
                    $existingSetting = ReservationSetting::where('date', $date->toDateString())
                        ->where('point_of_sale_id', $pointOfSaleId)
                        ->first();

                    // Create reservation setting data
                    $reservationSettingData = [
                        'point_of_sale_id' => $pointOfSaleId,
                        'date' => $date->toDateString(),
                        'day_of_week' => (string) $dayOfWeek,
                        'opening_time' => $defaultStartTime,
                        'closing_time' => $defaultEndTime,
                        'workers_count' => 3,
                        'is_closed' => $isClosed,
                    ];

                    // Only update existing records if the date is tomorrow or later
                    if ($existingSetting) {
                        // Only update if the date is at least tomorrow
                        // if ($date->greaterThanOrEqualTo($futureDate)) {
                        //     $existingSetting->update($reservationSettingData);
                        // }
                    } else {
                        // Always create new intervals, even for today
                        ReservationSetting::create($reservationSettingData);
                    }
                } catch (\Exception $e) {
                    // Log the error or handle it appropriately
                    Log::error('Error creating reservation setting: ' . $e->getMessage());
                }
            }

            // Delete any settings beyond the specified number of days
            $lastAllowedDate = $today->copy()->addDays($numberOfDays - 1);
            try {
                $deletedCount = ReservationSetting::where('point_of_sale_id', $pointOfSaleId)
                    ->where('date', '>', $lastAllowedDate->toDateString())
                    ->delete();

                if ($deletedCount > 0) {
                    Log::info("Deleted {$deletedCount} reservation settings beyond {$numberOfDays} days for Point of Sale ID {$pointOfSaleId}");
                }
            } catch (\Exception $e) {
                Log::error('Error deleting reservation settings: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            Log::error('Error in createPosTimeIntervals: ' . $e->getMessage());
        }
    }
}

if (!function_exists('returnLogoForLightBackground')) {
    /**
     * Returns the logo path for light backgrounds
     * Checks for the first active company and returns its logo if it exists,
     * otherwise returns the default logo
     *
     * @return string The logo path
     */
    function returnLogoForLightBackground(): string
    {
        $defaultLogo = asset('/assets/images/mcs-logo-3.png');

        try {
            $company = Company::where('is_active', true)->first();

            if ($company && $company->logo) {
                return asset('storage/' . $company->logo);
            }
        } catch (\Exception $e) {
            Log::error('Error getting company logo: ' . $e->getMessage());
        }

        return $defaultLogo;
    }
}

if (!function_exists('returnLogoForDarkBackground')) {
    /**
     * Returns the logo path for light backgrounds
     * Checks for the first active company and returns its logo if it exists,
     * otherwise returns the default logo
     *
     * @return string The logo path
     */
    function returnLogoForDarkBackground(): string
    {
        $defaultLogo = asset('/assets/images/mcs-logo-5.png');

        try {
            $company = Company::where('is_active', true)->first();

            if ($company && $company->logo_dark) {
                return asset('storage/' . $company->logo_dark);
            }
        } catch (\Exception $e) {
            Log::error('Error getting company logo: ' . $e->getMessage());
        }

        return $defaultLogo;
    }

    function returnDesktopLogoFromDb(): string
    {
        $defaultLogo = asset('/assets/images/mcs-logo-5.png');

        try {
            $header = HeaderSettings::first();

            if ($header && $header->desktop_logo) {
                return asset('storage/' . $header->desktop_logo);
            }
        } catch (\Exception $e) {
            Log::error('Error getting company desktop logo: ' . $e->getMessage());
        }

        return $defaultLogo;
    }

    function returnMobileLogoFromDb(): string
    {
        $defaultLogo = asset('/assets/images/mcs-logo-5.png');

        try {
            $header = HeaderSettings::first();

            if ($header && $header->mobile_logo) {
                return asset('storage/' . $header->mobile_logo);
            }
        } catch (\Exception $e) {
            Log::error('Error getting mobile logo: ' . $e->getMessage());
        }

        return $defaultLogo;
    }


    function resendVerificationEmail(Request $request)
    {
        try {
            if (isset($request->redirect)) {
                session(['redirect' => $request->redirect]);
            }
            if (Auth::user()) {
                $user = Auth::user();
            } else {
                if (isset($request->email)) {
                    $user = User::where('email', $request->email)->first();
                } elseif (isset($request->user_id)) {
                    $user = User::find($request->user_id);
                } else {
                    return back()->with('error', __('User not found.'));
                }
            }

            if (!$user) {
                return back()->with('error', __('User not found.'));
            }

            $verificationToken = Str::random(60);
            $user->email_verification_token = $verificationToken;
            $user->save();

            // Send verification email to the NEW email address with newEmail parameter
            Mail::to($user->email)->send(new EmailVerification(
                $user,
                $verificationToken,
            ));

            return back()->with('message', __('Verification link sent!'));
        } catch (\Exception $e) {
            Log::error('Error sending verification email: ' . $e->getMessage());
            return back()->with('error', __('Failed to send verification email. Please try again later.'));
        }
    }
}

if (!function_exists('getDaysOfWeek')) {
    /**
     * Returns an array of days of the week with their indexes
     * The array uses numeric keys (0-6) corresponding to days of the week
     * and values are the translated day names
     *
     * @return array The days of the week
     */
    function getDaysOfWeek(): array
    {
        return [
            '0' => __('Sunday'),
            '1' => __('Monday'),
            '2' => __('Tuesday'),
            '3' => __('Wednesday'),
            '4' => __('Thursday'),
            '5' => __('Friday'),
            '6' => __('Saturday'),
        ];
    }
}

if (!function_exists('getDayName')) {
    /**
     * Returns the translated name of a day given its numeric index
     *
     * @param int|string $dayOfWeek The day of week index (0-6)
     * @return string The translated day name
     */
    function getDayName($dayOfWeek): string
    {
        return match ((int)$dayOfWeek) {
            0 => __('Sunday'),
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            default => '',
        };
    }
}


if (!function_exists('generateTicketsForInvoiceItem')) {
    function generateTicketsForInvoiceItem($ticketNumber, $pointOfSaleId, $invoiceItemId)
    {
        // Get the invoice item details
        $invoiceItem = \App\Models\InvoiceItem::findOrFail($invoiceItemId);

        // Get the last digit from ticket number
        $currentCount = intval(substr($ticketNumber, -1));

        // Calculate time slot duration (in minutes)
        $startTime = \Carbon\Carbon::parse($invoiceItem->start_time);
        $endTime = \Carbon\Carbon::parse($invoiceItem->end_time);
        $totalDurationMinutes = $endTime->diffInMinutes($startTime);
        $slotDurationMinutes = $totalDurationMinutes / $invoiceItem->quantity;

        // Calculate this ticket's time slot
        $ticketStartTime = $startTime->copy()->addMinutes(($currentCount - 1) * $slotDurationMinutes);
        $ticketEndTime = $ticketStartTime->copy()->addMinutes($slotDurationMinutes);

        // Create ticket details as JSON
        $ticketDetail = json_encode([
            'appointment_date' => $invoiceItem->appointment_date,
            'start_time' => $ticketStartTime->format('H:i'),
            'end_time' => $ticketEndTime->format('H:i'),
            'slot_number' => $currentCount,
            'total_slots' => $invoiceItem->quantity
        ]);

        Ticket::create([
            'code' => $ticketNumber,
            'invoice_item_id' => $invoiceItemId,
            'ticket_status_id' => 1, // Default status
            'status_updated_at' => now(),
            'point_of_sale_id' => $pointOfSaleId,
            'ticket_detail' => $ticketDetail
        ]);
    }
}

if (!function_exists('generateUniqueInvoiceItemNumber')) {
    function generateUniqueInvoiceItemNumber($invoiceNumber)
    {
        $prefix = 'TICKET-';
        $unique = false;
        $ticketNumber = '';
        $itemCount = 1;

        while (!$unique) {
            // Combine with prefix
            $ticketNumber = $prefix . date('ymd') . '-' . str_replace('INV-', '', $invoiceNumber) . '' . $itemCount;

            // Check if this ticket number already exists
            $exists = \App\Models\InvoiceItem::where('invoice_number', $ticketNumber)->exists();

            if (!$exists) {
                $unique = true;
            }
            $itemCount++;
        }

        return $ticketNumber;
    }
}

if (!function_exists('generateUniqueInvoiceNumber')) {
    function generateUniqueInvoiceNumber($bookedFrom = 'point_of_sale')
    {
        $prefix = 'INV-';
        if ($bookedFrom == 'point_of_sale') {
            $from = 'POS-';
        } else {
            $from = 'WEB-';
        }

        // Get the highest invoice number across all invoices
        $latestInvoice = \App\Models\Invoice::all()
            ->map(function ($invoice) {
                // Extract the number after the first 8 characters (after 'INV-POS-' or 'INV-WEB-')
                $number = substr($invoice->invoice_number, 8);
                return (int)$number;
            })
            ->max();

        // If no invoices exist or couldn't extract number, start from 1
        $nextNumber = $latestInvoice ? $latestInvoice + 1 : 1;

        // Format the number with leading zeros to maintain consistent length
        $invoiceNumber = $prefix . $from . $nextNumber;

        return $invoiceNumber;
    }
}


if (!function_exists('generateQrCode')) {
    function generateQrCode($code)
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
            ->generate($code);
    }
}
    