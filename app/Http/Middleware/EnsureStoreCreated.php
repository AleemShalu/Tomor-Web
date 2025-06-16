<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreCreated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        $userId = auth()->id();
        $hasStore = Store::where('user_id', $userId)->exists();

        if (!$hasStore && $request->path() !== 'stores/create') {
            return redirect('/stores/create')->with('status', 'Please create a store before using the system.');
        }else{
            return $next($request);
        }

    }
}
