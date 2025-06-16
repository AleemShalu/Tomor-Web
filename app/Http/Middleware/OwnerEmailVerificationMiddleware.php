<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerEmailVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is logged in, has the "owner" role, and if their email is not verified
        if (Auth::check() && Auth::user()->hasRole('owner') && !Auth::user()->hasVerifiedEmail()) {

            // Set the status of the user
            $user = Auth::user();
            $user->forceFill(['status' => 1])->save();

            // Redirect to the email verification page
            return redirect('email/verify');
        }
        return $next($request);
    }
}
