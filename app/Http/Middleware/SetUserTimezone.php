<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($timezone = $request->user()?->timezone) {
            date_default_timezone_set($timezone);
            config(['app.timezone' => $timezone]);
        }

        return $next($request);
    }
}
