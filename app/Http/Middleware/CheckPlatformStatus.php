<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatformStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
    {
        $settings = Setting::first();

        if ($request->expectsJson()) { // retrun json for api.
            if (!$settings->app_status) {
                return response()->json([
                    "message" => __('locale.api.errors.app_under_maintenance'),
                ], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        } else {
            if (!$settings->web_status) {
                return response()->view('errors.maintenance');  // show a maintenance page for web.
            }
        }

        return $next($request);
    }
}
