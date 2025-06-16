<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && Hash::check('tomor@google2023', $user->password) && !$request->is('password/set')) {
            return redirect()->route('password.set')->with(['user_id' => $user->id]);
        }

        return $next($request);
    }
}
