<?php

namespace App\Traits\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OtpEmailVerification;
use App\Models\OtpPhoneVerification;
use App\Events\Api\PhoneOtpRequested;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

trait OtpVerificationTrait
{
    private function otpCodeExpired($timestamp)
    {
        // otp code time limit before elapsing (in minutes)
        $otp_code_throttle = env('FRONT_OTP_CODE_EXPIRATION_MINUTES', 5);
        if ($otp_code_throttle <= 0) {
            return false;
        }
        return Carbon::parse($timestamp)->addMinutes($otp_code_throttle)->isPast();
    }

    private function otpCodeRecentlySent($timestamp)
    {
        // resened otp code time limit (in seconds)
        $otp_code_throttle = env('FRONT_OTP_CODE_RESEND_LIMIT_SECONDS', 30);
        if ($otp_code_throttle <= 0) {
            return false;
        }
        return Carbon::parse($timestamp)->addSeconds($otp_code_throttle)->isFuture();
    }

    private function otpSendingBlocked($timestamp)
    {
        // block otp sending time limit (in minutes)
        $otp_code_throttle = env('FRONT_OTP_CODE_BLOCK_LIMIT_MINUTES', 30);
        if ($otp_code_throttle <= 0) {
            return false;
        }
        return Carbon::parse($timestamp)->addMinutes($otp_code_throttle)->isFuture();
    }

    private function checkIfUserPhoneExists(Request $request)
    {
        // check if user phone number is registered.
        $user = User::where('dial_code', request('dial_code'))
            ->where('contact_no', request('contact_no'))
            ->first();

        if (!$user) {
            return response()->json([
                "message" => __('locale.api.auth.common.user_with_this_phone_number_not_found'),
                "code" => "AUTHENTICATION_ERROR",
            ], Response::HTTP_UNAUTHORIZED,);
        }
    }

    private function checkIfUserEmailExists(Request $request)
    {
        $email = Str::lower(request('email'));

        // check if user email address is registered
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                "message" => __('passwords.user'),
                "code" => "AUTHENTICATION_ERROR",
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    private function applyOtpPhoneVerificationPolicy(Request $request, ?String $type)
    {
        // check if old otp phone record exists
        $otp_phone_record = OtpPhoneVerification::where('dial_code', request('dial_code'))
            ->where('contact_no', request('contact_no'))
            ->where('type', $type)
            ->orderBy('updated_at')
            ->first();

        if ($otp_phone_record) {
            // return if the otp code is recently sent but do not count this attempt.
            if ($this->otpCodeRecentlySent($otp_phone_record['updated_at'])) {
                return response()->json([
                    "message" => __('locale.api.auth.otp_code.resend_time_limit'),
                    "code" => "OTP_TIME_LIMIT_ERROR"
                ], Response::HTTP_BAD_REQUEST);
            }

            $otp_max_no_of_attempts = env('FRONT_OTP_CODE_MAX_ATTEMPTS', 5);
            if ($otp_phone_record->attempts >= $otp_max_no_of_attempts) {
                // block OTP sending when the user exceeds the maximum number of attempts within a specified time limit
                if ($this->otpSendingBlocked($otp_phone_record['created_at'])) {
                    return response()->json([
                        "message" => __('locale.api.auth.otp_code.sending_block_time_limit'),
                        "code" => "OTP_BLOCK_TIME_LIMIT_ERROR"
                    ], Response::HTTP_BAD_REQUEST);
                } else { // delete old phone otp records to reset the maximum number of attempts
                    OtpPhoneVerification::where('dial_code', request('dial_code'))
                        ->where('contact_no', request('contact_no'))
                        ->where('type', $type)
                        ->delete();
                }
            }
        }

        // create new phone otp code
        $otp_phone_record = OtpPhoneVerification::updateOrCreate(
            ['dial_code' => request('dial_code'), 'contact_no' => request('contact_no'), 'type' => $type,],
            ['code' => getRandomNumbers(4),],
        );

        // increase attempts by 1.
        $otp_phone_record->increment('attempts');

        // prepare user data for sending notification
        $user_data = array(
            // 'id' => $user->id,
            'dial_code' => $otp_phone_record->dial_code,
            'contact_no' => $otp_phone_record->contact_no,
            'code' => $otp_phone_record->code,
            'device_locale' => request('device_locale'),
        );

        // send message for phone otp verification
        event(new PhoneOtpRequested((object) $user_data));

        // connect to ultramessage service with token and instance id.
        // $ultramsg_token = env('ULTRAMSG_TOKEN');
        // $ultramsg_instance_id = env('ULTRAMSG_INSTANCE_ID');
        // $body = __('locale.api.auth.otp_code.otp_message',
        //     ['code' => $otp_phone_record->code], request('device_locale', config('app.locale', 'en')));
        // $client = new WhatsAppApi($ultramsg_token, $ultramsg_instance_id);
        // $phone = $otp_phone_record->dial_code . $otp_phone_record->contact_no;
        // $client->sendChatMessage($phone, $body);
    }

    private function applyOtpEmailVerificationPolicy(Request $request, ?String $type, $notification)
    {
        $email = Str::lower(request('email'));

        // check if user email address is registered
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                "message" => __('passwords.user'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // get otp code if exists
        $otp_email_record = OtpEmailVerification::where('email', $email)
            ->where('type', $type)
            ->orderBy('updated_at')
            ->first();

        if ($otp_email_record) {

            // return if the otp code is recently sent but do not count this attempt.
            if ($this->otpCodeRecentlySent($otp_email_record['updated_at'])) {
                return response()->json([
                    "message" => __('locale.api.auth.otp_code.resend_time_limit'),
                    "code" => "OTP_TIME_LIMIT_ERROR"
                ], Response::HTTP_BAD_REQUEST);
            }

            // Log::alert($otp_email_record['updated_at']);

            $otp_max_no_of_attempts = env('FRONT_OTP_CODE_MAX_ATTEMPTS', 5);
            if ($otp_email_record->attempts >= $otp_max_no_of_attempts) {
                // block OTP sending when the user exceeds the maximum number of attempts within a specified time limit
                if ($this->otpSendingBlocked($otp_email_record['created_at'])) {
                    return response()->json([
                        "message" => __('locale.api.auth.otp_code.sending_block_time_limit'),
                        "code" => "OTP_BLOCK_TIME_LIMIT_ERROR"
                    ], Response::HTTP_BAD_REQUEST);
                } else { // delete old forget password email otp records to reset the maximum number of attempts
                    OtpEmailVerification::where('email', $email)->where('type', $type)->delete();
                }
            }
        }

        // create new forget_passowrd otp code
        $otp_email_record = OtpEmailVerification::updateOrCreate(
            ['email' => $email,  'type' => $type],
            ['code' => getRandomNumbers(4)],
        );

        // increase attempts by 1.
        $otp_email_record->increment('attempts');

        $user_data = array(
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'code' => $otp_email_record->code,
            'device_locale' => request('device_locale'),
        );

        // send notification for otp email verification
        event(new $notification((object) $user_data));

        // send SMS notification for forget_passowrd verification
        // $msg = __('app.sms.user_registered',
        //     ['name' => $user->name, 'otp_code' => $otp_email_record->code],
        //     request('locale'));
        // $to = request('country_code') . request('phone');
        // sendAwsSms( $msg, $to );
    }

    private function validateOtpPhoneVerification(Request $request, ?String $type, $deleteOld = TRUE)
    {
        // check if the `otp_code` is expired
        $record = OtpPhoneVerification::where('dial_code', request('dial_code'))
            ->where('contact_no', request('contact_no'))
            ->where('type', $type)
            ->orderBy('updated_at')
            ->first();

        $skipOtpCode = env('SKIP_OTP_CODE', '1223');

        if ($record && $this->otpCodeExpired($record['updated_at'])) {
            return response()->json([
                "message" => __('locale.api.auth.otp_code.time_limit_elapsed'),
                "code" => "OTP_ELAPSED_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // check `otp_code` value
        if (!$record || (request('code') != $record->code && request('code') != $skipOtpCode)) {
            return response()->json([
                "message" => __('locale.api.auth.otp_code.invalid'),
                "code" => "OTP_CODE_VALUE_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($deleteOld) {
            // delete old phone otp records
            OtpPhoneVerification::where('dial_code', request('dial_code'))
                ->where('contact_no', request('contact_no'))
                ->where('type', $type)
                // ->whereNot('id', $record->id)
                ->delete();
        }
    }

    private function validateOtpEmailVerification(Request $request, ?String $type, $deleteOld = TRUE)
    {
        $email = Str::lower(request('email'));

        $record = OtpEmailVerification::where('email', $email)
            ->where('type', $type)
            ->orderBy('updated_at')
            ->first();

        $skipOtpCode = env('SKIP_OTP_CODE', '1223');

        if ($record && $this->otpCodeExpired($record['updated_at'])) {
            return response()->json([
                "message" => __('locale.api.auth.otp_code.time_limit_elapsed'),
                "code" => "OTP_ELAPSED_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // check `otp_code` value
        if (!$record || (request('code') != $record->code && request('code') != $skipOtpCode)) {
            return response()->json([
                "message" => __('locale.api.auth.otp_code.invalid'),
                "code" => "OTP_CODE_VALUE_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($deleteOld) {
            // delete all email otp codes for the user after success
            OtpEmailVerification::where('email', $email)->where('type', $type)->delete();
        }
    }
}
