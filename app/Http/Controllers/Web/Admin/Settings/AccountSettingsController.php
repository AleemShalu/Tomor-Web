<?php

namespace App\Http\Controllers\Web\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $admin = User::Find(1);
        return view('admin.settings.account', compact('admin'));
    }

    public function updateSecuritySettings(Request $request)
    {

    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()
            ->route('admin.settings.account')
            ->with('password_success', 'Password updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $admin = auth()->user();

        // Validate profile update fields
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.settings.account')
                ->withErrors($validator)
                ->withInput();
        }

        // Handle avatar upload
        if ($request->hasFile('profile_photo_path')) {
            $user_profile_photo_path_folder = 'users/' . $admin->id . '/profile-photo';
            if (!File::exists(storage_path($user_profile_photo_path_folder))) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($user_profile_photo_path_folder);
            }
            $profile_photo_path_file = $request->file('profile_photo_path');
            $profile_photo_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                $user_profile_photo_path_folder,
                $profile_photo_path_file,
                uniqid('user-profile-photo-', true) . '.' . $profile_photo_path_file->getClientOriginalExtension()
            );
            $admin->profile_photo_path = $profile_photo_path;
        }

        // Update other profile information
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->save();

        return redirect()
            ->route('admin.settings.account')
            ->with('profile_success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
