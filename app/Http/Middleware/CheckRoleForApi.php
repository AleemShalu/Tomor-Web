<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            return response()->json([
                "message" => __('locale.api.errors.user_unauthenticated'),
                "code"  => "UNAUTHORIZED_API_ROLE_ERROR",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $roles = is_array($role) ? $role : explode('|', $role);

        if (! $authGuard->user()->hasAnyRole($roles)) {
            return response()->json([
                "message" => __('locale.api.errors.user_does_not_have_necessary_permissions'),
                "code"  => "FORBIDDEN_API_ROLE_ERROR",
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
