<?php

namespace App\Http\Controllers\Web\Owner\Auth;

use App\Http\Controllers\Web\Owner\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller

{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Assuming you have a method to check if the user has a specific role
            if ($user->hasRole('owner')) {
                if ($user->status == 0) {
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['login' => 'The account has been disabled, please contact technical support']);
                } else {
                    return redirect()->route('dashboard');
                }
            } else {
                // User doesn't have the "owner" role, redirect back to the login page with an error message
                Auth::logout();
                return redirect()->back()->withInput()->withErrors(['login' => 'You are not authorized to access the system as an owner']);
            }
        } else {
            // Authentication failed, redirect back to the login page with an error message
            return redirect()->back()->withInput()->withErrors(['login' => 'Invalid credentials']);
        }
    }
}


