<?php

namespace App\Http\Middleware;

use App\Enums\LocaleEnum;
use App\Enums\TimezoneEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleAndTimezoneForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // get the language header value
        $requestLocale = $request->header('Accept-Language');
        if (isset($requestLocale) && in_array($requestLocale, LocaleEnum::values())) {
            app()->setLocale($requestLocale);
        }

        // get the timezone header value
        $requestTimezone = $request->header('Accept-Timezone');
        if (isset($requestTimezone) && in_array($requestTimezone, TimezoneEnum::values())) {
            $request['timezone'] = $requestTimezone;
        } else {
            $request['timezone'] = config('app.timezone');
        }

        return $next($request);
    }
}
