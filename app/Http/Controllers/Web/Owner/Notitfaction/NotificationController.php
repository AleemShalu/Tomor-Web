<?php

namespace App\Http\Controllers\Web\Owner\Notitfaction;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use App\Models\User;
use App\Notifications\SendMessage;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

// Adjust the namespace as needed
// Import the job class

class NotificationController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppNotificationService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function sendToUser(Request $request)
    {
        $sender = User::find($request->input('sender_id'));
        $reserve = User::find($request->input('reserve_id'));

        if (!$reserve) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $notificationType = 'offer';
        $notificationChannels = ['mail', 'database', 'whatsapp'];

        // Get the custom message from the request
        $message = $request->input('message', 'Default message if not provided');

        try {
            // Create and send the notification with the custom message
            $reserve->notify(new SendMessage($sender, $reserve, $notificationType, $notificationChannels, $message, 'test'));

            return response()->json(['message' => 'Notification sent to user']);
        } catch (\Exception $e) {
            // Log or return an error response to handle the exception
            return response()->json(['message' => 'Error sending WhatsApp message: ' . $e->getMessage()], 500);
        }
    }

    public function sendToRole(Request $request, $roleName)
    {
        // Find users with the specified role
        $usersWithRole = User::role($roleName)->get();
        $sender = User::find(1);

        if ($usersWithRole->isEmpty()) {
            return response()->json(['message' => 'No users with the specified role'], 404);
        }

        // Define notification type, channels, and any other data
        $notificationType = 'offer';
        $notificationChannels = ['mail', 'database']; // Add 'whatsapp' channel

        // Get the custom message from the request
        $message = $request->input('message', 'Default message if not provided');

        foreach ($usersWithRole as $user) {
            // Dispatch a job to send the notification
            SendNotificationJob::dispatch($sender, $user, $notificationType, $notificationChannels, $message);
        }

        return response()->json(['message' => 'Notifications queued for users with the role']);
    }

    public function store(Request $request)
    {
        // Validate and store a new notification
        // Example code for storing a notification goes here
    }

    public function markNotificationAsRead($notificationId)
    {
        // Find the notification by ID
        $notification = DB::table('notifications')->where('id', $notificationId)->first();

        // Check if the notification exists
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        // Decode the JSON string in the data field to an array
        $notificationData = json_decode($notification->data, true);

        // Check if the notification belongs to the current user
        if ($notificationData['recipient_id'] !== auth()->user()->id) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        // Mark the notification as read
        DB::table('notifications')->where('id', $notificationId)->update(['read_at' => now()]);

        return redirect()->back();
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return response()->json($notification);
    }


    public function getUserNotifications($userId)
    {
        $notifications = Notification::where('user_id', $userId)->get();
        return response()->json($notifications);
    }

    public function showDhamenNotifications()
    {
        // Fetch all notifications with type 'dhamen'
        $notifications = \App\Models\Notification::where('type', 'dhamen')->get();


        // Pass notifications to the view
        return view('admin.notifications.dhamen.index', compact('notifications'));
    }
}