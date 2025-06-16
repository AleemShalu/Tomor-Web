<?php

namespace App\Http\Controllers\Web\Owner\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class PasswordSetController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!Hash::check('tomor@google2023', $user->password)) {
            return redirect('/dashboard');
        }

        return view('owner.password.set');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => $this->passwordRules(),
            'contact_no' => ['required', 'unique:users,contact_no'],
        ]);

        // Update the user's password
        $user = Auth::user();
        $user->password = Hash::make($request['password']);
        $user->contact_no = $request->contact_no;
        $user->dial_code = 966;
        $user->save();

        // Redirect the user to a success page or any other desired action
        return redirect()->route('dashboard');
    }

    protected function passwordRules()
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
