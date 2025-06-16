<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Contract\Messaging;

class FcmTokenService
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }


    public function updateFcmTokenForUser($userId, $newFcmToken, $locale = 'en')
    {
        try {
            DB::transaction(function () use ($userId, $newFcmToken, $locale) {
                $existingToken = DB::table('fcm_tokens')
                    ->where('user_id', $userId)
                    ->value('fcm_token');

                if ($existingToken) {
                    DB::table('fcm_tokens')
                        ->where('user_id', $userId)
                        ->update([
                            'fcm_token' => $newFcmToken,
                            'locale' => $locale,
                            'updated_at' => now(),
                        ]);
                } else {
                    $existingTokenCount = DB::table('fcm_tokens')
                        ->where('fcm_token', $newFcmToken)
                        ->count();

                    if ($existingTokenCount == 0) {
                        DB::table('fcm_tokens')->insert([
                            'user_id' => $userId,
                            'fcm_token' => $newFcmToken,
                            'locale' => $locale,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('fcm_tokens')
                            ->where('fcm_token', $newFcmToken)
                            ->update([
                                'user_id' => $userId,
                                'fcm_token' => $newFcmToken,
                                'locale' => $locale,
                                'updated_at' => now(),
                            ]);
                    }
                }
            });

            return response()->json(['message' => 'FCM token and locale updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update FCM token'], 500);
        }
    }

    //    public function sendNotification(Request $request)
//    {
//        $userId = 1;
//        $fcmToken = $this->getFcmTokenForUser($userId);
//
//        $title = $request->input('title');
//        $body = $request->input('body');
//
//        $message = CloudMessage::fromArray([
//            'token' => $fcmToken,
//            'notification' => [
//                'title' => $title,
//                'body' => $body
//            ],
//        ]);
//
//        $this->messaging->send($message); // Fixed the property name here
//    }
}
