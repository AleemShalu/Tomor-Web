<?php

namespace App\Http\Controllers\Api;

use App\Enums\OtpTypeEnum;
use App\Enums\SpecialNeedsStatus;
use App\Enums\UserTypeEnum;
use App\Events\Api\ForgetPasswordRequested;
use App\Events\Api\UserRegistered;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\OtpPhoneVerification;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\User;
use App\Models\Usher;
use App\Models\UsherClient;
use App\Traits\Api\OtpVerificationTrait;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use OtpVerificationTrait;

    public function register(Request $request)
    {
        // clean data in some fields
        $request->merge([
            'is_quick_login' => filter_var($request->is_quick_login, FILTER_VALIDATE_BOOLEAN),
            'is_special_needs' => filter_var($request->is_special_needs, FILTER_VALIDATE_BOOLEAN),
            'special_needs_type_id' => isset($request->special_needs_type_id) ? intval($request->special_needs_type_id) : null,
            'is_from_usher' => filter_var($request->is_from_usher, FILTER_VALIDATE_BOOLEAN),
        ]);

        // validate request fields
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|min:1|max:100',
            'email' => 'required|string|email:rfc,dns|unique:App\Models\User,email',
            'password' => 'required|string|min:8|confirmed',
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn($query) => $query->where('dial_code', request('dial_code'))),
            ],
            'user_type' => [
                'required',
                'string',
                'max:10',
                Rule::in(['customer', 'owner']) // only owner and customer are allowed for registraion
            ],
            'is_quick_login' => 'nullable|boolean',
            'is_special_needs' => 'nullable|required_if:user_type,customer|boolean',
            'special_needs_type_id' => 'nullable|required_if:is_special_needs,true|numeric|exists:App\Models\SpecialNeedsType,id',
            'special_needs_sa_card_number' => 'nullable|required_if:is_special_needs,true|string|min:1|digits:10',
            'special_needs_attachment' => 'nullable|prohibited_unless:is_special_needs,true|file|mimes:pdf,doc,docx|max:1024',  // up to 1 MB
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'is_from_usher' => 'nullable|boolean',
            'usher_code' => [
                'nullable',
                'required_if:is_from_usher,true',
                Rule::exists('ushers', 'code_usher'),
            ],
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            // Log::alert( $validator->errors()->messages());
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        // Log::alert($request->all());
        // return response()->json([
        //     "message" => "TEST_CODE",
        // ], Response::HTTP_BAD_REQUEST);

        try {

            $registrationOtp = OtpTypeEnum::REGISTRATION->value;

            $user_type = request('user_type') ? Str::lower(request('user_type')) : null;

            // 1. create a new email verification otp code
            // $email_otp_code = OtpEmailVerification::create([
            //     'email' => Str::lower(request('email')),
            //     'code' => getRandomNumbers(4),
            // ]);

            $name = request('name') ?? (app()->getLocale() === 'ar' ? 'مستخدم' . rand(100000, 999999) : 'user' . rand(100000, 999999));

            // 2. create a new user model
            $user = User::create([
                'name' => $name,
                'email' => request('email') ? Str::lower(request('email')) : null,
                'password' => Hash::make(request('password')),
                'dial_code' => request('contact_no') ? request('dial_code') : null,
                'contact_no' => request('contact_no'),
                // 'phone_verified_at' => now(),
                'status' => 1, // activate the account upon registration
            ]);

            // 3. attach a role to the user based on the type
            if (isset($user_type)) {
                $user_role = Role::findByName($user_type);
                $user->assignRole($user_role);
            }

            // 4. handle the customer with special needs if the requirements satisfied
            if ($user->hasRole('customer') && request('is_special_needs') && request('special_needs_type_id')) {
                $special_needs_status = SpecialNeedsStatus::PENDING;

                // 4.1 create a new customer with special needs model
                $customer_with_special_needs = CustomerWithSpecialNeeds::create([
                    'customer_id' => $user->id,
                    'special_needs_type_id' => request('special_needs_type_id'),
                    'special_needs_qualified' => 0, // upon registration, customer NOT qualified by defualt.
                    'special_needs_sa_card_number' => request('special_needs_sa_card_number'),
                    // 'special_needs_description' => $user->id,
                    'special_needs_status' => $special_needs_status->getSpecialNeedsStatus(),
                ]);

                // 4.2 upload special_needs_attachment file
                if (request('special_needs_attachment')) {
                    $customer_attachments_folder = 'users/' . $user->id . '/customer/special-needs-attachments';
                    if (!File::exists(storage_path($customer_attachments_folder))) {
                        Storage::disk(getSecondaryStorageDisk())->makeDirectory($customer_attachments_folder);
                    }
                    $special_needs_attachment_file = request('special_needs_attachment');
                    $special_needs_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                        $customer_attachments_folder,
                        $special_needs_attachment_file,
                        $special_needs_attachment_file->getClientOriginalName()
                        . '.' . $special_needs_attachment_file->getClientOriginalExtension(),
                    );
                    $customer_with_special_needs->special_needs_attachment = $special_needs_attachment_path;
                    $customer_with_special_needs->save();
                }
            }

            if ($request->has('is_quick_login') && $request->input('is_quick_login') === true) {
                $user->markEmailAsVerified();
                $user->is_quick_login = true;
                $user->save();
            } else {
                // $user_data = array(
                //     'id' => $user->id,
                //     'name' => $user->name,
                //     'email' => $user->email,
                //     'code' => $email_otp_code->code,
                // );

                // 5. Send email notification for account verification
                // event(new UserRegistered((object) $user_data));

                // 5. Send SMS notification for account verification
                // $msg = __('app.sms.user_registered',
                //     ['name' => $user->name, 'otp_code' => $phone_verification_otp_code->code],
                //     request('locale'));
                // $to = request('country_code') . request('phone');
                // sendAwsSms( $msg, $to );
            }

            // 5. create a new model of the user's approval for terms and conditions
            $terms_condition = TermsCondition::latest()->first(); // get the latest terms and conditions record
            $user->terms_conditions()->attach($terms_condition->id, ['approved' => 1]);

            // 6. create a new model of the user's approval for the privacy policy
            $privacy_policy = PrivacyPolicy::latest()->first();  // get the latest privacy policy record
            $user->privacy_policies()->attach($privacy_policy->id, ['approved' => 1]);

            // 7. attach onwer user to the usher_clients using the given usher code.
            if ($user->hasRole('owner') && request('usher_code')) {
                $usher = Usher::where('code_usher', request('usher_code'))->first();
                if ($usher) {
                    $usherClient = new UsherClient();
                    $usherClient->user_id = $user->id;
                    $usherClient->usher_id = $usher->id;
                    $usherClient->code_usher_used = $usher->code_usher;
                    $usherClient->save();
                }
            }

            // 8. delete all user otp phone verification records (after successful registration)
            OtpPhoneVerification::where('dial_code', $user->dial_code)
                ->where('contact_no', $user->contact_no)
                ->where('type', $registrationOtp)
                ->delete();

            return response()->json([
                "message" => __('locale.api.auth.register.user_created_successfully')
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:register: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function requestVerifyPhoneForRegistration(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|min:1|max:100',
            'email' => 'required|string|email:rfc,dns|unique:App\Models\User,email',
            'password' => 'required|string|min:8|max:50|confirmed',
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn($query) => $query->where('dial_code', request('dial_code'))),
            ],
            'user_type' => [
                'required',
                'string',
                'max:10',
                Rule::in(['customer', 'owner']) // only owner and customer are allowed for registraion
            ],
            'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // get otp type
            $registrationOtp = OtpTypeEnum::REGISTRATION->value;

            // check otp phone verification policy
            $OtpPhonePolicyViolated = $this->applyOtpPhoneVerificationPolicy($request, $registrationOtp);
            if ($OtpPhonePolicyViolated) return $OtpPhonePolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.register.otp_sent_as_sms_or_whatsapp'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:requestVerifyPhoneForRegistration: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyPhoneForRegistration(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn($query) => $query->where('dial_code', request('dial_code'))),
            ],
            'code' => 'required|numeric|digits:4',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $registrationOtp = OtpTypeEnum::REGISTRATION->value;

            // check if otp phone is valid
            // set delete old to FALSE,
            // to prevent this phone number to be verified again and again even if registration is not completed.
            $OtpPhoneNotValid = $this->validateOtpPhoneVerification($request, $registrationOtp, FALSE);
            if ($OtpPhoneNotValid) return $OtpPhoneNotValid;


            return response()->json([
                "message" => __('locale.api.auth.register.phone_verified_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifyPhoneForRegistration: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|string',
            'password' => 'required|string',
            'user_type' => [
                'required',
                'string',
                'max:10',
                Rule::in(['customer', 'owner', 'worker'])
            ],
            'device_name' => 'required|min:1|max:255',
            'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $credentials = $request->only('email', 'password');
        $user_type = request('user_type') ? Str::lower(request('user_type')) : null;

        if (Auth::attempt($credentials)) { // if login attempt success
            try {
                $user = auth()->user(); // save auth user

                // if (!$user->hasVerifiedEmail()) { // if user account is not verified. Request a verification.
                //     // get otp code if exists
                //     $record = OtpEmailVerification::where('email', request('email'))->latest()->first();
                //     if ($record && $this->otpCodeRecentlySent($record['created_at'])) {
                //         return response()->json([
                //             "message" => __('locale.api.auth.otp_code.resend_time_limit'),
                //             "code" => "OTP_TIME_LIMIT_ERROR"
                //         ], Response::HTTP_BAD_REQUEST);
                //     }

                //     // delete all email otp codes for the user
                //     OtpEmailVerification::where('email', request('email'))->delete();

                //     // create new email otp code
                //     $email_otp_code = OtpEmailVerification::create([
                //         'email' => request('email'),
                //         'code' => getRandomNumbers(4),
                //     ]);

                //     $user_data = array(
                //         'id' => $user->id,
                //         'name' => $user->name,
                //         'email' => $user->email,
                //         'code' => $email_otp_code->code,
                //     );

                //     event(new UserRegistered((object) $user_data)); // Send notification for account verification

                //     return response()->json([
                //         "message" => __('locale.api.auth.login.email_not_verified'),
                //         "code" => "EMAIL_NOT_VERIFIED",
                //     ], Response::HTTP_UNAUTHORIZED);
                // }

                if (!$user->status) { // return if the account is deactivated.

                    return response()->json([
                        "message" => __('locale.api.auth.login.account_deactivated'),
                        "code" => "ACCOUNT_NOT_ACTIVE",
                    ], Response::HTTP_UNAUTHORIZED);

                } else if (
                    ($user_type != 'worker' && !$user->hasRole($user_type)) ||
                    ($user_type == 'worker' && !$user->hasAnyRole(['worker', 'worker_supervisor']))
                ) { // return if the role does not match the user type.

                    return response()->json([
                        "message" => __('locale.api.auth.login.account_not_authorized', ['user_type' => UserTypeEnum::label($user_type)]),
                        "code" => "USER_TYPE_NOT_AUTHORIZED",
                    ], Response::HTTP_FORBIDDEN);

                } else { // if user account is verified and activated. create a token.

                    $user['token'] = $user->createToken(request('device_name'))->plainTextToken;
                    return response()->json([
                        "user" => [
                            "id" => $user->id,
                            "name" => $user->name,
                        ],
                        "token" => $user->token,
                        "message" => __('locale.api.auth.login.logged_in_successfully'),
                    ], Response::HTTP_OK);

                }

            } catch (QueryException $e) {
                Log::error('API:AuthController:login: ' . $e->getMessage());
                return response()->json($e, Response::HTTP_BAD_REQUEST);
            }
        } else { // if login attempt fails
            // $user = User::where('email', request('email'))->first();

            // Check email and password
            // if(!$user || $user && !Hash::check(request('password'), $user->password)) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
            // }
        }
    }

    public function loginWithPhone(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            // 'password' => 'required|min:8|max:50',
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'user_type' => [
                'required',
                'string',
                'max:10',
                Rule::in(['customer', 'owner', 'worker'])
            ],
            // 'device_name' => 'required|min:1|max:255',
            'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $user_type = request('user_type') ? Str::lower(request('user_type')) : null;

        try {

            // check if user phone number is registered.
            $user = User::where('dial_code', request('dial_code'))->where('contact_no', request('contact_no'))->first();

            if (!$user) {

                return response()->json([
                    "message" => __('locale.api.auth.common.user_with_this_phone_number_not_found'),
                    "code" => "AUTHENTICATION_ERROR"
                ], Response::HTTP_UNAUTHORIZED);

            } else if (!$user->status) { // return if the account is deactivated.

                return response()->json([
                    "message" => __('locale.api.auth.login.account_deactivated'),
                    "code" => "ACCOUNT_NOT_ACTIVE",
                ], Response::HTTP_UNAUTHORIZED);

            } else if (!$user->hasRole($user_type)) { // return if the role does not match the user type.

                return response()->json([
                    "message" => __('locale.api.auth.login.account_not_authorized', ['user_type' => UserTypeEnum::label($user_type)]),
                    "code" => "USER_TYPE_NOT_AUTHORIZED",
                ], Response::HTTP_FORBIDDEN);

            }

            // check otp phone verification policy
            $loginOtp = OtpTypeEnum::LOGIN->value;
            $OtpPhonePolicyViolated = $this->applyOtpPhoneVerificationPolicy($request, $loginOtp);
            if ($OtpPhonePolicyViolated) return $OtpPhonePolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.otp_code.resent_as_sms_or_whatsapp'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:loginWithPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifiyLoginWithPhone(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            // 'password' => 'required|min:8|max:50',
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'code' => 'required|numeric|digits:4',
            'user_type' => [
                'required',
                'string',
                'max:10',
                Rule::in(['customer', 'owner', 'worker'])
            ],
            'device_name' => 'required|min:1|max:255',
            // 'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $user_type = request('user_type') ? Str::lower(request('user_type')) : null;

            // check if user phone number is registered
            $user = User::where('dial_code', request('dial_code'))->where('contact_no', request('contact_no'))->first();
            if (!$user) {
                return response()->json([
                    "message" => __('locale.api.auth.common.user_with_this_phone_number_not_found'),
                    "code" => "AUTHENTICATION_ERROR"
                ], Response::HTTP_UNAUTHORIZED);
            } else if (!$user->status) { // return if the account is deactivated.

                return response()->json([
                    "message" => __('locale.api.auth.login.account_deactivated'),
                    "code" => "ACCOUNT_NOT_ACTIVE",
                ], Response::HTTP_UNAUTHORIZED);

            } else if (!$user->hasRole($user_type)) { // return if the role does not match the user type.

                return response()->json([
                    "message" => __('locale.api.auth.login.account_not_authorized', ['user_type' => UserTypeEnum::label($user_type)]),
                    "code" => "USER_TYPE_NOT_AUTHORIZED",
                ], Response::HTTP_FORBIDDEN);

            }

            // check if otp phone is valid
            $loginOtp = OtpTypeEnum::LOGIN->value;
            $OtpPhoneNotValid = $this->validateOtpPhoneVerification($request, $loginOtp);
            if ($OtpPhoneNotValid) return $OtpPhoneNotValid;

            // if user phone number is verified with otp. create a token.
            $user['token'] = $user->createToken(request('device_name'))->plainTextToken;
            return response()->json([
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                ],
                "token" => $user->token,
                "message" => __('locale.api.auth.login_with_phone.logged_in_with_phone_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifiyLoginWithPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function forgetPasswordWithEmail(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check otp email verification policy
            $forgetPasswordOtp = OtpTypeEnum::FORGET_PASSWORD->value;
            $OtpEmailPolicyViolated = $this->applyOtpEmailVerificationPolicy($request, $forgetPasswordOtp, ForgetPasswordRequested::class);
            if ($OtpEmailPolicyViolated) return $OtpEmailPolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.forget_password.otp_sent_as_email'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:forgetPasswordWithEmail: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyForgetPasswordWithEmail(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|min:1',
            'code' => 'required|numeric|digits:4',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserEmailExists($request);
            if ($isuserExists) return $isuserExists;

            // check if otp email is valid
            $forgetPasswordOtp = OtpTypeEnum::FORGET_PASSWORD->value;
            $OtpEmailNotValid = $this->validateOtpEmailVerification($request, $forgetPasswordOtp);
            if ($OtpEmailNotValid) return $OtpEmailNotValid;

            return response()->json([
                "message" => __('locale.api.auth.verify_forget_passowrd.email_verified_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifyForgetPasswordWithEmail: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }

    }

    public function resetPasswordWithEmail(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $email = Str::lower(request('email'));

            // check if user email address is registered
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    "message" => __('passwords.user'),
                    "code" => "AUTHENTICATION_ERROR",
                ], Response::HTTP_UNAUTHORIZED);
            }

            if (request('password')) {
                $user->password = Hash::make(request('password'));
                $user->save();
            }

            return response()->json([
                "message" => __('locale.api.auth.reset_password.password_reset_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:resetPasswordWithEmail: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function forgetPasswordWithPhone(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserPhoneExists($request);
            if ($isuserExists) return $isuserExists;

            // check otp phone verification policy
            $forgetPasswordOtp = OtpTypeEnum::FORGET_PASSWORD->value;
            $OtpPhonePolicyViolated = $this->applyOtpPhoneVerificationPolicy($request, $forgetPasswordOtp);
            if ($OtpPhonePolicyViolated) return $OtpPhonePolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.forget_password.otp_sent_as_sms_or_whatsapp'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:forgetPasswordWithPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyForgetPasswordWithPhone(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'code' => 'required|numeric|digits:4',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserPhoneExists($request);
            if ($isuserExists) return $isuserExists;

            // check if otp phone is valid
            $forgetPasswordOtp = OtpTypeEnum::FORGET_PASSWORD->value;
            $OtpPhoneNotValid = $this->validateOtpPhoneVerification($request, $forgetPasswordOtp);
            if ($OtpPhoneNotValid) return $OtpPhoneNotValid;

            return response()->json([
                "message" => __('locale.api.auth.verify_forget_passowrd.phone_verified_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifyForgetPasswordWithPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPasswordWithPhone(Request $request)
    {
        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if user phone number is registered.
            $user = User::where('dial_code', request('dial_code'))
                ->where('contact_no', request('contact_no'))
                ->first();

            if (!$user) {
                return response()->json([
                    "message" => __('locale.api.auth.common.user_with_this_phone_number_not_found'),
                    "code" => "AUTHENTICATION_ERROR"
                ], Response::HTTP_UNAUTHORIZED);
            }

            if (request('password')) {
                $user->password = Hash::make(request('password'));
                $user->save();
            }

            return response()->json([
                "message" => __('locale.api.auth.reset_password.password_reset_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:resetPasswordWithPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAuthUser(Request $request)
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
                'roles'
            ])->findOrFail(auth()->user()->id);

            return response()->json([
                "user" => $user,
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:getAuthUser: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function requestVerifyAuthEmail(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if user email is already verified
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json([
                    "message" => __('locale.api.auth.verify.email_already_verified'),
                    "code" => "EMAIL_ALREADY_VERIFIED",
                ], Response::HTTP_BAD_REQUEST);
            }

            // check otp email verification policy
            $verificationOtp = OtpTypeEnum::VERIFICATION->value;
            $OtpEmailPolicyViolated = $this->applyOtpEmailVerificationPolicy($request, $verificationOtp, UserRegistered::class);
            if ($OtpEmailPolicyViolated) return $OtpEmailPolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.otp_code.resent_as_email'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:requestVerifyAuthEmail: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyAuthEmail(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|min:1',
            'code' => 'required|numeric|digits:4',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserEmailExists($request);
            if ($isuserExists) return $isuserExists;

            // find user
            $user = $request->user();

            // check if user email is already verified
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    "message" => __('locale.api.auth.verify.email_already_verified'),
                    "code" => "EMAIL_ALREADY_VERIFIED",
                ], Response::HTTP_BAD_REQUEST);
            }

            // check if otp email is valid
            $verificationOtp = OtpTypeEnum::VERIFICATION->value;
            $OtpEmailNotValid = $this->validateOtpEmailVerification($request, $verificationOtp);
            if ($OtpEmailNotValid) return $OtpEmailNotValid;

            // Mark `email` as verified
            if ($user->markEmailAsVerified()) {
                // fire user verified event
                event(new Verified($user));
                $user->forceFill(['status' => 1])->save();
                return response()->json([
                    "message" => __('locale.api.auth.verify.email_verified_successfully'),
                ], Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifyAuthEmail: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }

    }

    public function requestVerifyAuthPhone(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'device_locale' => 'nullable|min:1|max:2',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserPhoneExists($request);
            if ($isuserExists) return $isuserExists;

            if ($request->user()->hasVerifiedPhone()) {
                return response()->json([
                    "message" => __('locale.api.auth.verify.phone_already_verified'),
                    "code" => "PHONE_ALREADY_VERIFIED",
                ], Response::HTTP_BAD_REQUEST);
            }

            // check otp phone verification policy
            $verificationOtp = OtpTypeEnum::VERIFICATION->value;
            $OtpPhonePolicyViolated = $this->applyOtpPhoneVerificationPolicy($request, $verificationOtp);
            if ($OtpPhonePolicyViolated) return $OtpPhonePolicyViolated;

            return response()->json([
                "message" => __('locale.api.auth.otp_code.resent_as_sms_or_whatsapp'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:requestVerifyAuthPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyAuthPhone(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'code' => 'required|numeric|digits:4',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // check if the user exists
            $isuserExists = $this->checkIfUserPhoneExists($request);
            if ($isuserExists) return $isuserExists;

            // find user
            $user = $request->user();

            // check if user email is already verified
            if ($user->hasVerifiedPhone()) {
                return response()->json([
                    "message" => __('locale.api.auth.verify.phone_already_verified'),
                    "code" => "PHONE_ALREADY_VERIFIED",
                ], Response::HTTP_BAD_REQUEST);
            }

            // check if otp email is valid
            $verificationOtp = OtpTypeEnum::VERIFICATION->value;
            $OtpPhoneNotValid = $this->validateOtpPhoneVerification($request, $verificationOtp);
            if ($OtpPhoneNotValid) return $OtpPhoneNotValid;

            // Mark `email` as verified
            if ($user->markPhoneAsVerified()) {
                // fire user verified event
                event(new Verified($user));
                $user->forceFill(['status' => 1])->save();
                return response()->json([
                    "message" => __('locale.api.auth.verify.phone_verified_successfully'),
                ], Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            Log::error('API:AuthController:verifyAuthPhone: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function changePassword(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // find the user
            $user = User::findOrFail(auth()->user()->id);

            // Check if user current password matches the request field "curren_password"
            if (!Hash::check(request('current_password'), $user->password)) {
                return response()->json([
                    "message" => __('locale.api.auth.change_password.current_password_mismatch'),
                    "code" => "AUTHENTICATION_ERROR"
                ], Response::HTTP_UNAUTHORIZED);
            }

            if (request('password')) { // save new password
                $user->password = Hash::make(request('password'));
                $user->save();
            }

            return response()->json([
                "message" => __('locale.api.auth.change_password.password_changed_successfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:changePassword: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();
            return response()->json([
                "message" => __('locale.api.auth.logout.logged_out_succssfully'),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:AuthController:logout: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
