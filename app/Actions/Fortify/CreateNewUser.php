<?php

namespace App\Actions\Fortify;

use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\User;
use App\Models\Usher;
use App\Models\UsherClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return \App\Models\User
     */

    public function create(array $input)
    {
        $sender = User::find(1);

        $validator = Validator::make($input, [
//            'g-recaptcha-response' => ['required', 'captcha'], // Add this line for reCAPTCHA validation
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'contact_no' => ['required', 'string', 'unique:users'],
            'dial_code' => ['required', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'usher_code' => [
                'nullable', // Allows null values
                'required_if:isFromUsher,true',
                Rule::exists('ushers', 'code_usher')
            ],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'user_type' => 'owner',
            'email' => $input['email'],
            'contact_no' => $input['contact_no'],
            'dial_code' => $input['dial_code'],
            'last_seen' => Carbon::now(),
            'password' => Hash::make($input['password']),
        ]);

        // create a new model of the user's approval for terms and conditions
        $terms_condition = TermsCondition::latest()->first(); // get the latest terms and conditions record
        $user->terms_conditions()->attach($terms_condition->id, ['approved' => 1]);

        // create a new model of the user's approval for the privacy policy
        $privacy_policy = PrivacyPolicy::latest()->first();  // get the latest privacy policy record
        $user->privacy_policies()->attach($privacy_policy->id, ['approved' => 1]);

        // Assign the "owner" role to the user
        $ownerRole = Role::findByName('owner');

        $user->assignRole($ownerRole);
        if (request('usher_code')) {
            $usher = Usher::where('code_usher', request('usher_code'))->first();

            if ($usher) {
                $usherClient = new UsherClient();

                $usherClient->user_id = $user->id;
                $usherClient->usher_id = $usher->id;
                $usherClient->code_usher_used = request('usher_code');
                $usherClient->created_at = Carbon::now();
                $usherClient->updated_at = Carbon::now();
                $usherClient->save();
            }
        }

//
//        // Define the notification type and channels
//        $notificationType = 2; // Replace with your desired type
//        $notificationChannels = ['mail', 'database', 'app', 'web']; // Specify the desired notification channels
//
//        // Send the custom notification
//        // Define the custom message
//        $message = Lang::get('Welcome to our platform!'); // Customize this message as needed
//
//        // Send the custom notification with the message
//        $user->notify(new SendMessage($user, $notificationType, $notificationChannels, $message, $message));
        return $user;
    }
}
