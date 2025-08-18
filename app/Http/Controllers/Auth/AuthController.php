<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EmailVerification;
use App\Models\Customer;
use App\Models\PointOfSale;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm(Request $request)
    {
        $redirect = $request->query('redirect');
        if ($redirect) {
            session(['redirect' => $redirect]);
        }
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
            'password.required' => __('Password is required'),
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // if (Auth::user()->email_verified_at === null) {
            //     Auth::logout();
            //     return back()->withErrors([
            //         'email' => __('Please verify your email address before logging in.'),
            //     ]);
            // }

            if (session()->has('redirect') || $request->query('redirect')) {
                $redirect = session()->get('redirect') ?? $request->query('redirect');
                session()->forget('redirect');
                return redirect()->route($redirect);
            } else {
                // Check user role and redirect accordingly
                if (Auth::user()->hasRole('customer')) {
                    return redirect()->intended('/');
                } else {
                    return redirect()->intended('/admin/');
                }
            }

        }

        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ])->onlyInput('email');
    }

    // Show registration form
    public function showRegistrationForm(Request $request)
    {
        $redirect = $request->query('redirect');
        if ($redirect) {
            session(['redirect' => $redirect]);
        }
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => __('Name is required'),
            'name.max' => __('Name must not exceed 255 characters'),
            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
            'email.max' => __('Email must not exceed 255 characters'),
            'email.unique' => __('This email address is already in use'),
            'phone.required' => __('Phone number is required'),
            'phone.max' => __('Phone number must not exceed 20 characters'),
            'password.required' => __('Password is required'),
            'password.min' => __('Password must be at least 8 characters'),
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();
            $verificationToken = Str::random(60);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verification_token' => $verificationToken,
            ]);

            // Create customer
            Customer::create([
                'user_id' => $user->id,
                'name_en' => $request->name,
                'name_ar' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'point_of_sale_id' => PointOfSale::getMainBranch()->id,
            ]);

            // Assign customer role
            $customerRole = Role::where('name', 'customer')->first();
            if ($customerRole) {
                $user->assignRole($customerRole);
            }

            try {
                // Send verification email
                Mail::to($user->email)->send(new EmailVerification($user, $verificationToken));
            } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
                DB::rollBack();
                Log::error('Email verification failed: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Could not send verification email: ') . $e->getMessage());
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Email verification failed: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Could not send verification email. Please try again later.'));
            }

            // If everything is successful, commit the transaction
            DB::commit();

            event(new Registered($user));

            return redirect()->route('login')->with('success', __('Please check your email for verification link.'));

        } catch (\Exception $e) {
            // If there's an error, rollback the transaction
            DB::rollBack();

            // Log the error
            Log::error('Registration failed: ' . $e->getMessage());

            // Return with error message
            return redirect()->back()
                ->withInput()
                ->with('error', __('Registration failed: ') . $e->getMessage());
        }
    }

    // Handle registration
    public function quickRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => __('Name is required'),
            'name.max' => __('Name must not exceed 255 characters'),
            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
            'email.max' => __('Email must not exceed 255 characters'),
            'email.unique' => __('This email address is already in use'),
            'phone.required' => __('Phone number is required'),
            'phone.max' => __('Phone number must not exceed 20 characters'),
            'password.required' => __('Password is required'),
            'password.min' => __('Password must be at least 8 characters'),
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            $verificationToken = Str::random(60);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verification_token' => $verificationToken,
            ]);

            // Create customer
            Customer::create([
                'user_id' => $user->id,
                'name_en' => $request->name,
                'name_ar' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'point_of_sale_id' => PointOfSale::getMainBranch()->id,
            ]);

            // Assign customer role
            $customerRole = Role::where('name', 'customer')->first();
            if ($customerRole) {
                $user->assignRole($customerRole);
            }

            // If everything is successful, commit the transaction
            DB::commit();

            Auth::login($user);

            if (session()->has('redirect') || $request->query('redirect')) {
                $redirect = session()->get('redirect') ?? $request->query('redirect');
                session()->forget('redirect');
                return redirect()->route($redirect);
            } else {
                return redirect()->route('/')->with('success', __('Registration successful.'));
            }

        } catch (\Exception $e) {
            // If there's an error, rollback the transaction
            DB::rollBack();

            // Log the error
            Log::error('Registration failed: ' . $e->getMessage());

            // Return with error message
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('Registration failed. Please try again.')]);
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    // Show forgot password form
    public function showForgotPasswordForm(Request $request)
    {
        $redirect = $request->query('redirect');
        if ($redirect) {
            session(['redirect' => $redirect]);
        }
        return view('auth.forgot-password');
    }

    // Handle forgot password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __('Your password reset link has been sent to your email.')])
            : back()->withErrors(['email' => __($status)]);
    }

    // Show reset password form
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Handle reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => __('Reset token is required'),
            'email.required' => __('Email address is required'),
            'email.email' => __('Please enter a valid email address'),
            'password.required' => __('Password is required'),
            'password.min' => __('Password must be at least 8 characters'),
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'email_verified_at' => now()
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if (session()->has('redirect')) {
            $redirect = session()->get('redirect');
            session()->forget('redirect');
            return redirect()->route($redirect)->with('success', __('Password reset successfully.'));
        }
        else{
            return redirect()->route('login')->with('success', __('Password reset successfully.'));
        }

    }

    // Show email verification notice
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    // Handle email verification
    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $newEmail = $request->query('newEmail');

        if (!$token) {
            return redirect()->route('login')->with('error', __('Verification token is missing.'));
        }

        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', __('Invalid verification token.'));
        }

        // If newEmail is present, update the email
        if ($newEmail) {
            // Check if email is already in use by another account
            $existingUser = User::where('email', $newEmail)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return redirect()->route('user.profile')->with('error', __('The email address is already in use by another account.'));
            }

            // Update user email
            $user->email = $newEmail;

            // Also update customer email if exists
            if ($user->hasRole('customer') && $user->customer) {
                $user->customer->email = $newEmail;
                $user->customer->save();
            }
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        if (!Auth::check()) {
            Auth::login($user);
        }

        if (session()->has('redirect')) {
            $redirect = session()->get('redirect');
            session()->forget('redirect');
            return redirect()->route($redirect)->with('success', __('Email verified successfully.'));
        } else {
            return redirect()->route('home')->with('success', __('Email verified successfully.'));
        }
    }

    // Resend verification email
    public function resendEmail(Request $request)
    {
        return resendVerificationEmail($request);
    }

    // Show user profile
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = null;

        // Check if the user has the customer role
        if ($user->hasRole('customer')) {
            $customer = $user->customer;
        }

        return view('auth.profile', compact('user', 'customer'));
    }

    // Update user profile
    public function updateProfile(Request $request)
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $currentLocale = app()->getLocale();

        // Different validation rules based on user role
        if ($user->hasRole('customer') && $user->customer) {
            $rules = [
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'required|string|max:20',
                'address' => 'nullable|string|max:255',
            ];

            $messages = [
                'name_en.required' => __('English name is required'),
                'name_en.max' => __('English name must not exceed 255 characters'),
                'name_ar.required' => __('Arabic name is required'),
                'name_ar.max' => __('Arabic name must not exceed 255 characters'),
                'email.required' => __('Email address is required'),
                'email.email' => __('Please enter a valid email address'),
                'email.max' => __('Email must not exceed 255 characters'),
                'email.unique' => __('This email address is already in use'),
                'phone_number.required' => __('Phone number is required'),
                'phone_number.max' => __('Phone number must not exceed 20 characters'),
                'address.max' => __('Address must not exceed 255 characters'),
            ];
        } else {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            ];

            $messages = [
                'name.required' => __('Name is required'),
                'name.max' => __('Name must not exceed 255 characters'),
                'email.required' => __('Email address is required'),
                'email.email' => __('Please enter a valid email address'),
                'email.max' => __('Email must not exceed 255 characters'),
                'email.unique' => __('This email address is already in use'),
            ];
        }

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            // Set name based on role and current locale
            if ($user->hasRole('customer') && $user->customer) {
                // For customers, set user name based on current locale
                $user->name = $currentLocale === 'ar' ? $request->name_ar : $request->name_en;
            } else {
                $user->name = $request->name;
            }

            // If email is changed, send verification to new email but don't update yet
            if ($user->email !== $request->email) {
                $verificationToken = Str::random(60);
                $user->email_verification_token = $verificationToken;
                $user->save();

                // Send verification email to the NEW email address with newEmail parameter
                Mail::to($request->email)->send(new EmailVerification(
                    $user,
                    $verificationToken,
                    ['newEmail' => $request->email]
                ));
                session(['redirect' => 'user.profile']);
                // Notify that verification is required
                session()->flash('info', __('A verification link has been sent to your new email address. Please click the link to complete the email change.'));
            } else {
                $user->save();
            }

            // Update customer profile if exists
            if ($user->hasRole('customer') && $user->customer) {
                $customer = $user->customer;

                $customer->name_en = $request->name_en;
                $customer->name_ar = $request->name_ar;
                // Only update customer email if not changing main email
                if ($user->email === $request->email) {
                    $customer->email = $request->email;
                }
                $customer->phone_number = $request->phone_number;
                $customer->address = $request->address;

                if ($request->has('latitude') && $request->has('longitude')) {
                    $customer->latitude = $request->latitude;
                    $customer->longitude = $request->longitude;
                }

                $customer->save();
            }

            DB::commit();

            return redirect()->route('user.profile')->with('success', __('Profile updated successfully.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('Profile update failed. Please try again.')]);
        }
    }

    // Update user password
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => __('Current password is required'),
            'password.required' => __('New password is required'),
            'password.min' => __('New password must be at least 8 characters'),
            'password.confirmed' => __('New password confirmation does not match'),
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('The current password is incorrect.')]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', __('Password updated successfully.'));
    }
}
