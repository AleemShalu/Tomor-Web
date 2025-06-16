<?php

namespace App\Http\Controllers\Web\Owner\Auth;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    public function signInWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackToGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
            $findUser = User::where('auth_id', $user->id)->orWhere('email', $user->email)->first();

            return $findUser ? $this->handleExistingUser($findUser, $user) : $this->createAndLoginNewUser($user);

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function handleExistingUser($findUser, $user)
    {
        Auth::login($findUser);

        if ($findUser->auth_id === null) {
            $findUser->update([
                'auth_id' => $user->id,
                'auth_type' => 'google',
            ]);
            return Hash::check('tomor@google2023', $findUser->password)
                ? redirect()->route('password.set')->with(['user_id' => $findUser->id])
                : redirect('/dashboard');
        } else {
            return Hash::check('tomor@google2023', $findUser->password)
                ? redirect()->route('password.set')->with(['user_id' => $findUser->id])
                : redirect('/dashboard');
        }
    }

    private function createAndLoginNewUser($googleUser)
    {
        $newUser = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'auth_id' => $googleUser->id,
            'user_type' => 'owner',
            'auth_type' => 'google',
            'password' => Hash::make('tomor@google2023'),
        ]);

        // Download the profile photo and store it
        $profilePhotoPath = $this->downloadAndStoreProfilePhoto($googleUser->avatar_original, $newUser->id);

        // Update the user with the stored profile photo path
        $newUser->update(['profile_photo_path' => $profilePhotoPath]);

        $newUser->assignRole(Role::findByName('owner'));

        Auth::login($newUser);

        return redirect()->route('password.set')->with(['user_id' => $newUser->id]);
    }

    private function downloadAndStoreProfilePhoto($url, $userId)
    {
        $contents = file_get_contents($url);
        $filename = "profile_photo_{$userId}";

        // Store the profile photo in the storage
        Storage::put("public/profile-photos/{$filename}", $contents);

        // Return the stored path
        return "profile-photos/{$filename}";
    }
}
