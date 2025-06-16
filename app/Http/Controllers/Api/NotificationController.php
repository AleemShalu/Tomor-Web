<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Services\DhamenApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationController extends Controller
{

    protected $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexNotificationApp(Request $request)
    {
        $user_id = $request->input('user_id');

        // Retrieve unread notifications and format them using the resource
        $notifications = Notification::whereJsonContains('data->recipient_id', $user_id)
            ->where('data->type', 1)
            ->where('data->channel', 'REGEXP', '\\bapp\\b')
            ->whereNull('read_at') // Filter by unread notifications
            ->get();

        // Format the data using the resource
        $formattedNotifications = NotificationResource::collection($notifications);

        return response()->json($formattedNotifications);
    }

    public function indexNotificationStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $request->input('user_id');

        // Retrieve unread notifications and format them using the resource
        $notifications = Notification::whereJsonContains('data->recipient_id', $user_id)
            ->where('data->type', 2)
            ->where('data->channel', 'REGEXP', '\\bapp\\b')
            ->whereNull('read_at') // Filter by unread notifications
            ->get();

        // Format the data using the resource
        $formattedNotifications = NotificationResource::collection($notifications);

        return response()->json($formattedNotifications);
    }

    public function markNotificationAsReadApp(Request $request)
    {
        $user_id = $request->input('user_id');
        $notification_id = $request->input('notification_id'); // Assuming you send the notification ID to mark as read

        // Find the notification by ID and verify that it belongs to the specified user
        $notification = Notification::whereJsonContains('data->recipient_id', $user_id)
            ->where('data->type', 1)
            ->where('data->channel', 'REGEXP', '\\bapp\\b')
            ->find($notification_id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        // Mark the notification as read
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markNotificationAsReadStore(Request $request)
    {
        $user_id = $request->input('user_id');
        $notification_id = $request->input('notification_id'); // Assuming you send the notification ID to mark as read

        // Find the notification by ID and verify that it belongs to the specified user
        $notification = Notification::whereJsonContains('data->recipient_id', $user_id)
            ->where('data->type', 2)
            ->where('data->channel', 'REGEXP', '\\bapp\\b')
            ->find($notification_id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        // Mark the notification as read
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function handleCallback(Request $request)
    {
        // Log the notification for debugging purposes
        Log::info('Notification received', $request->all());

        // Extract relevant data from the request
        $notificationData = $request->all();

        // Generate a unique UUID for the notification (you might use a library or function for this)
        $uuid = (string)Str::uuid();

        // Save the notification data to the database
        Notification::create([
            'id' => $uuid,
            'notifiable_id' => 0,
            'notifiable_type' => 0,
            'sender_id' => null,
            'recipient_id' => null,
            'type' => 'dhamen',
            'data' => json_encode($notificationData),
            'channel' => null, // Set as appropriate
            'read_at' => null,
        ]);

        $this->dhamenApiService->handleNotification($notificationData);

        // Return a successful response
        return response()->json(['status' => 'SUCCESS']);
    }
}
