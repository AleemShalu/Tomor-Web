<?php

namespace App\Http\Controllers\Web\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpPhoneVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use UltraMsg\WhatsAppApi;

class OTPController extends Controller
{
    public function sendMessageOTP(Request $request)
    {
        $this->validate($request, [
            'dial_code' => 'required|string',
            'contact_no' => 'required|string',
        ]);

        $dialCode = $request->input('dial_code');
        $contactNo = $request->input('contact_no');
        $currentTime = Carbon::now();

        // Send the message using Ultramsg API
        $ultramsg_token = env('ULTRAMSG_TOKEN');
        $instance_id = env('ULTRAMSG_INSTANCE_ID');


        $verificationRecord = OtpPhoneVerification::where('dial_code', $dialCode)
            ->where('contact_no', $contactNo)
            ->first();

        if ($verificationRecord) {
            $nextAttemptTime = $verificationRecord->created_at->addHour();
            $minutesToWait = $nextAttemptTime->diffInMinutes($currentTime);

            if ($verificationRecord->attempts >= 3) {
                if ($currentTime >= $nextAttemptTime) {
                    $verificationRecord->delete(); // Delete the current verification record

                    // Create a new OTP verification record
                    $otp_new = mt_rand(1000, 9999);

                    $expirationTime = now()->addMinutes(1); // OTP is valid for 1 minute

                    $verificationRecord = OtpPhoneVerification::create([
                        'dial_code' => $dialCode,
                        'contact_no' => $contactNo,
                        'otp' => $otp_new,
                        'expires_at' => $expirationTime,
                        'attempts' => 1,
                    ]);

                    // Customize the message body to include the OTP and expiration info
                    $body = "Your OTP is \n *$otp_new* \nvalid for 1 minute.";

                    $client = new WhatsAppApi($ultramsg_token, $instance_id);

                    $to = $dialCode . $contactNo;

                    // Use $body as the message content
                    $api = $client->sendChatMessage($to, $body);

                    return response()->json($api);
                } else {
                    $verificationRecord->increment('attempts');

                    return response()->json([
                        'message' => 'OTP sending is temporarily blocked.',
                        'attempts' => $verificationRecord->attempts,
                        'minutes_to_wait' => $minutesToWait,
                    ], 400);
                }
            } else {
                $verificationRecord->increment('attempts');

                $otp = mt_rand(1000, 9999);

                // Customize the message body to include the OTP and expiration info
                $body = "Your OTP is \n *$otp* \nvalid for 1 minute.";

                $client = new WhatsAppApi($ultramsg_token, $instance_id);

                $to = $dialCode . $contactNo;

                // Use $body as the message content
                $api = $client->sendChatMessage($to, $body);

                $verificationRecord->update([
                    'expires_at' => now()->addMinutes(1),
                    'otp' => $otp,
                ]);

                return response()->json($api);
            }
        } else {
            $otp = mt_rand(1000, 9999);

            $expirationTime = now()->addMinutes(1); // OTP is valid for 1 minute

            $verificationRecord = OtpPhoneVerification::create([
                'dial_code' => $dialCode,
                'contact_no' => $contactNo,
                'otp' => $otp,
                'expires_at' => $expirationTime,
                'attempts' => 1,
            ]);

            // Customize the message body to include the OTP and expiration info
            $body = "Your OTP is \n *$otp* \nvalid for 1 minute.";

            $client = new WhatsAppApi($ultramsg_token, $instance_id);

            $to = $dialCode . $contactNo;

            // Use $body as the message content
            $api = $client->sendChatMessage($to, $body);

            return response()->json($api);
        }
    }

    public function verifyOTP(Request $request)
    {
        // Validate the request data
        $this->validate($request, [
            'dial_code' => 'required|string',
            'contact_no' => 'required|string',
            'otp' => 'required|string',
        ]);

        $dialCode = $request->input('dial_code');
        $contactNo = $request->input('contact_no');
        $inputOTP = $request->input('otp');

        $verificationRecord = OtpPhoneVerification::where('dial_code', $dialCode)
            ->where('contact_no', $contactNo)
            ->where('expires_at', '>=', now())
            ->first();// Check if OTP is not expired

        if (!$verificationRecord) {
            return response()->json(['message' => 'OTP verification record not found or expired.'], 404);
        }

        // Check if the input OTP matches the stored OTP
        if ($inputOTP === $verificationRecord->otp) {
            // OTP is correct

            // Delete the OTP verification record
            OtpPhoneVerification::where('contact_no', $contactNo)->delete();

            return response()->json(['message' => 'OTP verified successfully.'], 200);
        } else {
            return response()->json(['message' => 'Incorrect OTP.'], 404);
        }
    }

}
