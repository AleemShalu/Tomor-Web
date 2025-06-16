<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Ensure the user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Check user status
        if ($user->status == 0) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['login' => 'The account has been disabled, please contact technical support']);
        }

        return $next($request);
    }
}
