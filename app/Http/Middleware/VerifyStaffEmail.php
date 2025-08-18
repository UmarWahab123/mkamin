<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerifyStaffEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // If user is logged in and has staff role
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('staff')) {
            // Check if email is verified
            if (is_null($user->email_verified_at)) {
                resendVerificationEmail($request);

                // Store the intended URL in the session
                session(['url.intended' => $request->url()]);

                // Redirect to verification notice page with message
                return Redirect::route('login')->with('success', __('Please verify your email address before accessing this section.'));
            }
        }

        return $next($request);
    }
}
