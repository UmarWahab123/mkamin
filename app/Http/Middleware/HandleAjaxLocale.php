<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleAjaxLocale
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
        if ($request->ajax() || $request->wantsJson()) {
            $locale = $request->header('X-LOCALE');
            if ($locale && in_array($locale, config('app.available_locales', ['en', 'ar']))) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
