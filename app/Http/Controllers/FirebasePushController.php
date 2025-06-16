<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationFirebaseResource;
use App\Notifications\FirebaseNotification;
use App\Services\FcmTokenService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FirebasePushController extends Controller
{
    protected $fcmTokenService;

    public function __construct(FcmTokenService $fcmTokenService)
    {
        $this->fcmTokenService = $fcmTokenService;
    }

    public function sendFirebaseNotification(Request $request)
    {
        // Validate and get user details from request
        $userId = $request->input('user_id');

        // Retrieve FCM token for the specified user_id
        $fcm_token = DB::table('fcm_tokens')->where('user_id', $userId)->value('fcm_token');

        // Check if FCM token exists
        if (!$fcm_token) {
            $fcm_token = null;
        }

        // Send Firebase notification
        $title = $request->input('title');
        $body = $request->input('body');

        $notification = new FirebaseNotification($fcm_token, $title, $body);

        // Assuming you have a User model and it implements the Notifiable trait
        $user = \App\Models\User::find($userId);
        $user->notify($notification);

        return response()->json([
            'message' => 'Firebase Notification sent successfully',
        ]);
    }


    public function handleTokenRefresh(Request $request)
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Check if the user is authenticated
        if (auth()->check()) {
            $userId = auth()->user()->id; // Get the user ID
            $newFcmToken = $request->input('fcm_token');
            $locale = $request->input('locale', 'en'); // Default to 'en' if locale is not provided

            // Update FCM token and locale for the user
            $this->fcmTokenService->updateFcmTokenForUser($userId, $newFcmToken, $locale);

            return response()->json([
                'message' => 'FCM Token and locale updated successfully',
            ]);
        } else {
            return response()->json([
                'error' => 'User not authenticated',
            ], 401); // Unauthorized
        }
    }

    public function getFirebaseNotifications(Request $request)
    {
        $userId = auth()->user()->id;

        // Retrieve notifications for the authenticated user
        $notifications = DatabaseNotification::where('notifiable_id', $userId)
            ->where('type', "App\\Notifications\\FirebaseNotification") // Adjust this based on your User model namespace
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform the notifications using the resource
        $notificationsResource = NotificationFirebaseResource::collection($notifications);

        return response()->json([
            'notifications' => $notificationsResource,
        ]);
    }

    public function markNotificationAsRead(Request $request)
    {
        $notificationId = Request('notification_id');
        $notification = DatabaseNotification::find($notificationId);

        // Check if the notification belongs to the authenticated user
        if ($notification && $notification->notifiable_id === auth()->user()->id) {
            $notification->markAsRead();

            return response()->json([
                'message' => 'Notification marked as read successfully',
            ]);
        } else {
            return response()->json([
                'error' => 'Notification not found or does not belong to the authenticated user',
            ], 404);
        }
    }

    public function getUnreadNotificationsCount(Request $request)
    {
        $user = auth()->user();

        if ($user) {

            $unreadNotificationsCount = $user->unreadNotifications()
                ->where('type', "App\\Notifications\\FirebaseNotification")
                ->orderBy('created_at', 'desc')
                ->count();

            // Check if there are unread notifications
            $hasUnreadNotifications = $unreadNotificationsCount > 0;

            return response()->json([
                'has_unread_notifications' => $hasUnreadNotifications,
                'unread_notifications_count' => $unreadNotificationsCount,
            ]);
        } else {
            return response()->json([
                'error' => 'User not authenticated',
            ], 401); // Unauthorized
        }
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $userId = auth()->user()->id;

        $notifications = DatabaseNotification::where('notifiable_id', $userId)
            ->where('type', "App\\Notifications\\FirebaseNotification")
            ->whereNull('read_at')
            ->get();

        // Mark all as read
        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'message' => 'All notifications marked as read successfully',
        ]);
    }

}
