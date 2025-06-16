<?php

namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\Web\Owner\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function loginView()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // If the user is already authenticated as an admin, redirect to the home page
            return redirect()->route('admin.dashboard'); // Change 'home' to your actual home route name
        }

        return view('admin.auth.login'); // Replace 'admin.login' with your admin login view
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                // Authentication successful and user has the "admin" role, redirect to the dashboard or desired page
                return redirect()->route('admin.dashboard');
            } else {
                // User doesn't have the "admin" role, redirect back to the login page with an error message
                Auth::logout();
                return redirect()->route('login.admin')->withErrors(['login' => 'You are not authorized to access the system as an admin']);
            }
        } else {
            // Authentication failed, redirect back to the login page with an error message
            return redirect()->back()->withInput()->withErrors(['login.admin' => 'Invalid credentials']);
        }
    }

    public function logoutAdmin(Request $request)
    {
        $adminRole = Role::findByName('admin'); // Assuming the role is named "admin"

        if (Auth::check() && Auth::user()->hasRole($adminRole)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login.admin');
        }

        return redirect()->back()->withErrors(['login' => 'You are not authorized to perform this action']);
    }
}


