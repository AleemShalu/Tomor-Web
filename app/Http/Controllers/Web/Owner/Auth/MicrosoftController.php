<?php

namespace App\Http\Controllers\Web\Owner\Auth;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftController extends Controller
{

    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function callbackFromMicrosoft()
    {
        try {
            $user = Socialite::driver('microsoft')->user();

            $finduser = User::where('auth_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);

                return redirect('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'auth_id' => $user->id,
                    'auth_type' => 'azure',
                    'password' => encrypt('admin@123'),
                ]);

                Auth::login($newUser);

                return redirect('/dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
