<?php

namespace App\Http\Controllers\Api;

use App\Enums\SpecialNeedsStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {

            $user = User::with([
                'customer_with_special_needs' => ['special_needs_type'],
                'roles',
            ])->findOrFail(auth()->user()->id);

            return response()->json([
                "user" => UserResource::make($user),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:UserProfileController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userProfileValidationRules = new UserProfileRequest();
        $validator = Validator::make($request->all(), $userProfileValidationRules->rules($request->user()->id));

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // 1. find user model
            $user = User::findOrFail(auth()->user()->id);

            // 2. update user profile info
            $user->name = request('name');
            $user->username = request('username');
            $user->email = request('email') ? Str::lower(request('email', null)) : null;
            $user->dial_code = request('contact_no') ? request('dial_code') : null;
            $user->contact_no = request('contact_no', null);
            // $user->gender = request('gender');
            // if (request('date_of_birth')) {
            //     $user->date_of_birth = Carbon::parse(request('date_of_birth'))->format('Y-m-d');
            // }

            // 3. set user email to NOT verified if email has been changed.
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // 3. set user phone to NOT verified if dial_code / contanct_no has been changed.
            if ($user->isDirty('dial_code') || $user->isDirty('contact_no')) {
                $user->phone_verified_at = null;
            }

            // 4. save user profile info
            $user->save();

            // 5. upload profile_photo_path file if exist
            // if (request('profile_photo_path')) {
            //     $user_profile_photo_path_folder = 'users/' . $user->id . '/profile-photo';
            //     if (!File::exists(storage_path($user_profile_photo_path_folder))) {
            //         Storage::disk(getSecondaryStorageDisk())->makeDirectory($user_profile_photo_path_folder);
            //     }
            //     $profile_photo_path_file = request('profile_photo_path');
            //     $profile_photo_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
            //         $user_profile_photo_path_folder,
            //         $profile_photo_path_file,
            //         uniqid('user-profile-photo-', true) . '.' . $profile_photo_path_file->getClientOriginalExtension(),
            //     );
            //     $user->profile_photo_path = $profile_photo_path;
            //     $user->save();
            // }

            $user = User::with([
                'customer_with_special_needs' => ['special_needs_type'],
                'roles',
            ])->findOrFail($user->id);

            return response()->json([
                "user" => UserResource::make($user),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'User']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:UserProfileController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }

    public function updateUserProfilePhoto(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'old_profile_photo_path' => 'nullable|string',
            'profile_photo_path' => 'nullable|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // 1. find user model
            $user = User::findOrFail(auth()->user()->id);

            // 2. find current user profile_photo_path and update it / upload new
            $profile_photo_path = $user->profile_photo_path;
            if (request('profile_photo_path')) {  // check for new profile_photo_path
                $user_profile_photo_path_folder = 'users/' . $user->id . '/profile-photo';
                if (!File::exists(storage_path($user_profile_photo_path_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($user_profile_photo_path_folder);
                }
                if (request('old_profile_photo_path') && $user->profile_photo_path) { // delete old profile_photo_path
                    if (Storage::disk(getSecondaryStorageDisk())->exists($user->profile_photo_path)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($user->profile_photo_path);
                    }
                }
                $profile_photo_path_file = request('profile_photo_path');
                $profile_photo_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $user_profile_photo_path_folder,
                    $profile_photo_path_file,
                    uniqid('user-profile-photo-', true) . '.' . $profile_photo_path_file->getClientOriginalExtension(),
                );
            } else if (!request('old_profile_photo_path') && $user->profile_photo_path) { // delete old profile_photo_path
                if (Storage::disk(getSecondaryStorageDisk())->exists($user->profile_photo_path)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($user->profile_photo_path);
                }
                $profile_photo_path = null;
            }

            // 3. update user profile_photo_path
            $user->profile_photo_path = $profile_photo_path;
            $user->save();

            $user = User::with([
                'customer_with_special_needs' => ['special_needs_type'],
                'roles',
            ])->findOrFail($user->id);

            return response()->json([
                "user" => UserResource::make($user),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'User Profile Photo']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:updateUserProfilePhoto: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateSpecialNeedsProfile(Request $request)
    {
        // Log::alert($request->all());
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // clean data in some fields
        $request->merge([
            'special_needs_type_id' => isset($request->special_needs_type_id) ? intval($request->special_needs_type_id) : null,
        ]);

        // validate request fields
        $validator = Validator::make($request->all(), [
            'old_special_needs_attachment' => 'nullable|string',
            'special_needs_type_id' => 'required|numeric|exists:App\Models\SpecialNeedsType,id',
            'special_needs_sa_card_number' => 'required|string|min:1|digits:10',
            'special_needs_attachment' => 'required_if:old_special_needs_attachment,null|file|mimes:pdf,doc,docx|max:1024',  // up to 1 MB
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // 1. find customer with special needs profile
            $user = User::with(['customer_with_special_needs'])->findOrFail(auth()->user()->id);
            // $customer_with_special_needs = $user->customer_with_special_needs;

            // 2. get special_needs_status `pending` status
            $special_needs_status = SpecialNeedsStatus::PENDING;

            // 3. update or create customer with special needs profile
            $customer_with_special_needs = CustomerWithSpecialNeeds::updateOrCreate(
                [
                    'customer_id' => $user->id,
                ],
                [
                    'special_needs_type_id' => request('special_needs_type_id'),
                    'special_needs_sa_card_number' => request('special_needs_sa_card_number'),
                ],
            );

            // 4. Find current special_needs_attachment file / upload new
            $special_needs_attachment_path = $customer_with_special_needs->special_needs_attachment;
            if (request('special_needs_attachment')) {
                $customer_attachments_folder = 'users/' . $user->id . '/customer/special-needs-attachments';
                if (!File::exists(storage_path($customer_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($customer_attachments_folder);
                }
                if (request('old_special_needs_attachment') && $customer_with_special_needs->special_needs_attachment) {
                    // delete old special_needs_attachment
                    if (Storage::disk(getSecondaryStorageDisk())->exists($customer_with_special_needs->special_needs_attachment)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($customer_with_special_needs->special_needs_attachment);
                    }
                }
                $special_needs_attachment_file = request('special_needs_attachment');
                $special_needs_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $customer_attachments_folder,
                    $special_needs_attachment_file,
                    $special_needs_attachment_file->getClientOriginalName()
                    . '.' . $special_needs_attachment_file->getClientOriginalExtension(),
                );
                $customer_with_special_needs->special_needs_attachment = $special_needs_attachment_path;
            } else if (!request('old_special_needs_attachment') && $customer_with_special_needs->special_needs_attachment) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($customer_with_special_needs->special_needs_attachment)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($customer_with_special_needs->special_needs_attachment);
                }
                $special_needs_attachment_path = null;
                $customer_with_special_needs->special_needs_attachment = $special_needs_attachment_path;
            }

            // 5. set customer special_needs_qualified to 0 if card info has been changed
            if ($customer_with_special_needs->wasChanged('special_needs_sa_card_number')
                || $customer_with_special_needs->isDirty('special_needs_attachment')) {
                $customer_with_special_needs->special_needs_qualified = 0;
                $customer_with_special_needs->special_needs_status = $special_needs_status->getSpecialNeedsStatus();
            }

            // 6. save customer with special needs profile
            $customer_with_special_needs->save();

            $user = User::with([
                'customer_with_special_needs' => ['special_needs_type'],
                'roles',
            ])->findOrFail($user->id);

            return response()->json([
                "user" => UserResource::make($user),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Customer With Special Needs']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:UserProfileController:updateSpecialNeedsProfile: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroySpecialNeedsProfile(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {

            // 1. find customer with special needs profile model
            $record = CustomerWithSpecialNeeds::where('customer_id', auth()->user()->id)->firstOrFail();

            // 2. delete all model related files from storage disk
            if ($record->special_needs_attachment and Storage::disk(getSecondaryStorageDisk())->exists($record->special_needs_attachment)) {
                Storage::disk(getSecondaryStorageDisk())->delete($record->special_needs_attachment);
            }

            // 3. destroy customer with special needs profile model from DB
            $record->delete();

            return response()->json([
                "customer_with_special_needs" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Customer With Special Needs']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BranchController:deleteSpecialNeedsProfile: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            // 1. Find the authenticated user
            $user = User::findOrFail(auth()->user()->id);

            // 2. Soft delete the user (this will set the `deleted_at` column)
            $user->delete();

            return response()->json([
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'User']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:UserProfileController:destroy: ' . $e->getMessage());
            return response()->json([
                "message" => __('locale.api.alert.model_delete_failed', ['model' => 'User']),
                "code" => "DELETE_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
