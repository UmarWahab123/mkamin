<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Mail\NewStaffRequest;
use App\Models\PointOfSale;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Store a new staff application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string',
            'address' => 'nullable|string|max:255',
            'terms_accepted' => 'required|accepted',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            // 'staff_position_id' => 'required|exists:staff_positions,id',
            'default_start_time' => 'nullable|date_format:H:i',
            'default_end_time' => 'nullable|date_format:H:i',
            'default_closed_day' => 'nullable|string',
            'default_home_visit_days' => 'nullable|array',
            'default_home_visit_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'product_and_services' => 'nullable|array',
            'product_and_services.*' => 'exists:product_and_services,id',
        ], [
            'name.required' => __('Name is required'),
            'name.string' => __('Name must be text'),
            'name.max' => __('Name cannot exceed 255 characters'),

            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
            'email.unique' => __('This email is already registered'),

            'phone_number.required' => __('Phone number is required'),
            'phone_number.string' => __('Phone number must be text'),
            'phone_number.max' => __('Phone number cannot exceed 20 characters'),

            'password.required' => __('Password is required'),
            'password.string' => __('Password must be text'),
            'password.min' => __('Password must be at least 8 characters'),
            'password.confirmed' => __('Password confirmation does not match'),

            'position.required' => __('Position is required'),
            'position.string' => __('Position must be text'),

            'address.string' => __('Address must be text'),
            'address.max' => __('Address cannot exceed 255 characters'),

            'terms_accepted.required' => __('You must accept the terms and conditions'),
            'terms_accepted.accepted' => __('You must accept the terms and conditions'),

            'resume.file' => __('Resume must be a file'),
            'resume.mimes' => __('Resume must be a PDF, DOC, or DOCX file'),
            'resume.max' => __('Resume cannot exceed 10MB'),

            'images.array' => __('Images must be an array'),
            'images.*.image' => __('Files must be images'),
            'images.*.mimes' => __('Images must be JPEG, PNG, JPG, or GIF format'),
            'images.*.max' => __('Images cannot exceed 5MB each'),

            'default_start_time.date_format' => __('Start time must be in H:i format'),
            'default_end_time.date_format' => __('End time must be in H:i format'),

            'default_closed_day.string' => __('Closed day must be text'),

            'default_home_visit_days.array' => __('Home visit days must be an array'),
            'default_home_visit_days.*.string' => __('Home visit days must be text'),
            'default_home_visit_days.*.in' => __('Home visit days must be valid days of the week'),

            'product_and_services.array' => __('Products and services must be an array'),
            'product_and_services.*.exists' => __('Selected products and services do not exist'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('Validation error'),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Convert day name to day number
            $dayMap = [
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6
            ];

            $defaultClosedDay = null;
            if ($request->default_closed_day) {
                $defaultClosedDay = $dayMap[strtolower($request->default_closed_day)] ?? null;
            }

            // Convert default_home_visit_days array from day names to day numbers
            $defaultHomeVisitDays = [];
            if ($request->has('default_home_visit_days') && is_array($request->default_home_visit_days)) {
                foreach ($request->default_home_visit_days as $dayName) {
                    if (isset($dayMap[strtolower($dayName)])) {
                        $defaultHomeVisitDays[] = $dayMap[strtolower($dayName)];
                    }
                }
            }

            // Generate verification token
            $verificationToken = Str::random(60);

            // Create user account first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verification_token' => $verificationToken,
            ]);

            // Assign staff role if available
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('staff');
            }

            // Handle resume upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('staff/resumes', 'public');
            }

            // Handle multiple image uploads
            $imagesPaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('staff/images', 'public');
                    $imagesPaths[] = $path;
                }
            }

            // Create the staff record
            $staff = Staff::create([
                'name_en' => $request->name,
                'name_ar' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'resume' => $resumePath,
                'images' => $imagesPaths,
                'is_active' => false, // Set to false until approved
                'can_edit_profile' => true,
                'point_of_sale_id' => PointOfSale::where('is_main_branch', true)->first()->id ?? 1,
                'user_id' => $user->id, // Link to the newly created user
                'position_en' => explode(' ', $request->position)[0] ?? $request->position,
                'position_ar' => explode(' ', $request->position)[0] ?? $request->position,
                'default_start_time' => $request->default_start_time,
                'default_end_time' => $request->default_end_time,
                'default_closed_day' => $defaultClosedDay,
                'default_home_visit_days' => $defaultHomeVisitDays,
            ]);

            // Attach product and services if provided
            if ($request->has('product_and_services') && is_array($request->product_and_services)) {
                $staff->productAndServices()->attach($request->product_and_services);
            }

            // Send verification email to the staff member
            $verificationEmailError = null;
            $notificationEmailError = null;
            try {
                Mail::to($user->email)->send(new EmailVerification($user, $verificationToken));
            } catch (\Exception $mailException) {
                $verificationEmailError = __('Failed to send verification email to: ') . $user->email;
                // Continue execution without crashing
            }

            $pointOfSale = PointOfSale::find($staff->point_of_sale_id);
            if ($pointOfSale && $pointOfSale->user) {
                Log::info(__('Attempting to send notification email to point of sale user: ') . $pointOfSale->user->email);
                try {
                    Mail::to($pointOfSale->user->email)->send(new NewStaffRequest($staff));
                } catch (\Exception $mailException) {
                    $notificationEmailError = __('Failed to send notification email to: ') . $pointOfSale->user->email;
                    // Continue execution without crashing
                }
            } else {
                Log::warning(__('Could not send notification email: Point of sale or user not found'));
            }

            DB::commit();
            // Log in the user
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => __('Staff application submitted successfully. Please check your email to verify your account.'),
                'data' => $staff,
                'email_errors' => [$verificationEmailError, $notificationEmailError],
                'redirect' => '/admin/staff-profile'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__('Staff application error: ') . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('Failed to submit staff application'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
