<?php

namespace App\Http\Controllers;


use App\Models\AccountDeletionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AccountDeletionController extends Controller
{

    public function showRequestForm()
    {
        return view('profile.request-delete');
    }

    public function requestDelete(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
                function ($attribute, $value, $fail) {
                    // Check if the user has been soft-deleted
                    $user = User::withTrashed()->where('email', $value)->first();
                    if ($user && $user->trashed()) {
                        return $fail(__('locale.user_already_deleted'));
                    }

                    // Check if the user has already requested account deletion
                    $deletionRequest = AccountDeletionRequest::where('email', $value)->first();
                    if ($deletionRequest) {
                        // If the token is still valid, reject the request
                        if ($deletionRequest->isValid()) {
                            return $fail(__('locale.deletion_request_already_exists'));
                        }

                        // Otherwise, the token has expired, so delete the old request
                        $deletionRequest->delete();
                    }
                }
            ],
        ]);

        // Create a new deletion request
        $deletionRequest = AccountDeletionRequest::createRequest($request->email);

        // Send the email with the link to delete the account
        Mail::send('emails.delete-account', ['token' => $deletionRequest->token], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('طلب حذف حساب | Delete Your Account');
        });

        // Flash success message to session
        session()->flash('status', 'success');
        session()->flash('message', __('locale.deletion_link_sent'));

        return redirect()->back();
    }

    public function deleteAccount($token)
    {
        // Find the deletion request by token
        $deletionRequest = AccountDeletionRequest::where('token', $token)->first();

        if (!$deletionRequest || !$deletionRequest->isValid()) {
            return view('profile.account-deletion-result', [
                'status' => 'error',
                'message' => __('locale.common.errors.invalid_or_expired_token'),
            ]);
        }

        // Find the user by email
        $user = User::where('email', $deletionRequest->email)->first();

        if ($user) {
            $user->delete();

            // Delete the request entry
            $deletionRequest->delete();

            return view('profile.account-deletion-result', [
                'status' => 'success',
                'message' => __('locale.deleted_successfully_account'),
            ]);
        }

        return view('profile.account-deletion-result', [
            'status' => 'error',
            'message' => 'User not found.',
        ]);
    }
}