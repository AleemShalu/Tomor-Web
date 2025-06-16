<?php

namespace App\Http\Controllers\Web\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebasePushController;
use App\Mail\CustomEmail;
use App\Models\NotificationsGroup;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use UltraMsg\WhatsAppApi;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notificationsGroup = NotificationsGroup::all();

        return view('admin.notifications.index', compact('notificationsGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notifications.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function sendNotificationToUsers(Request $request)
    {
        $notification_type = $request->notificationType;
        $platform_type = $request->notificationPlatform;
        $users_type = $request->targetAudience;

        $notificationTitleAr = $request->notificationTitleAr;
        $notificationMessageAr = $request->notificationMessageAr;

        $notificationTitleEn = $request->notificationTitleEn;
        $notificationMessageEn = $request->notificationMessageEn;


        $notification = new NotificationsGroup();
        $notification->notification_type = $notification_type;
        $notification->platform_type = $platform_type;
        $notification->users_type = $users_type;
        $notification->notification_title_ar = $notificationTitleAr;
        $notification->notification_message_ar = $notificationMessageAr;
        $notification->notification_title_en = $notificationTitleEn;
        $notification->notification_message_en = $notificationMessageEn;
        $notification->save();


        if ($platform_type == 'web') //only for owners of the web
        {
            //ar
            $this->sendWebOwners($notificationTitleAr, $notificationMessageAr);

            //en
            $this->sendWebOwners($notificationTitleEn, $notificationMessageEn);

        } else if ($platform_type == 'mobile') //only for app
        {

            //ar
            $this->sendFirebase($notificationTitleAr, $notificationMessageAr, $users_type);

            //en
            $this->sendFirebase($notificationTitleEn, $notificationMessageEn, $users_type);

        } else if ($platform_type == 'email') //for all users , can select target audience
        {
            $title = "$notificationTitleAr | $notificationTitleEn";
            $this->sendEmails($title, $notificationMessageAr, $notificationMessageEn, $users_type);

        } else if ($platform_type == 'whatsapp') //for all users , can select target audience
        {
            $combinedMessage = "$notificationTitleAr \n$notificationMessageAr\n\n$notificationTitleEn \n$notificationMessageEn \nمنصة تؤمر\nTomor";

            $this->sendWhatsapp($combinedMessage, $users_type);
        }


        return redirect()->route('admin.notifications.index');

    }

    protected function sendEmails($title, $bodyAr, $bodyEn, $targetAudience)
    {
        try {


            if ($targetAudience == 'all') {
                // Retrieve all users from the database
                $users = User::all();

                foreach ($users as $user) {
                    // Send the email to each user
                    Mail::to($user->email)->send(new CustomEmail($title, $bodyAr, $bodyEn));

                    //sleep for 15 seconds
                    sleep(15);
                }
            } elseif ($targetAudience == 'customers') {

                // Retrieve all users from the database
                $users = User::role('customer')->get();

                foreach ($users as $user) {
                    // Send the email to each user
                    Mail::to($user->email)->send(new CustomEmail($title, $bodyAr, $bodyEn));

                    //sleep for 15 seconds
                    sleep(15);
                }
            } elseif ($targetAudience == 'owners') {

                // Retrieve all users from the database
                $users = User::role('owner')->get();

                foreach ($users as $user) {
                    // Send the email to each user
                    Mail::to($user->email)->send(new CustomEmail($title, $bodyAr, $bodyEn));

                    //sleep for 15 seconds
                    sleep(15);
                }
            }

            return response()->json(['message' => 'Test emails sent successfully to all users'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send test emails. ' . $e->getMessage()], 500);
        }
    }

    protected function sendWebOwners($subject, $body)
    {

        // Define the notification type and channels
        $notificationType = 2; // Replace with your desired type
        $notificationChannels = ['web']; // Specify the desired notification channels


        // Retrieve all users from the database
        $users = User::role('owner')->get();

        foreach ($users as $user) {
            // Send the email to each user
            $user->notify(new SendMessage($user, $notificationType, $notificationChannels, $body, $subject));

            // Introduce a delay of 5 seconds between each email
            sleep(5);
        }

    }


    protected function sendWhatsapp($body, $targetAudience)
    {
        $ultramsg_token = env('ULTRAMSG_TOKEN');
        $ultramsg_instance_id = env('ULTRAMSG_INSTANCE_ID');

        $client = new WhatsAppApi($ultramsg_token, $ultramsg_instance_id);

        try {
            if ($targetAudience == 'all') {
                // Retrieve all users from the database
                $users = User::all();

                foreach ($users as $user) {
                    $client->sendChatMessage($user->phone, $body);
                }
            } elseif ($targetAudience == 'customers') {

                // Retrieve all users from the database
                $users = User::role('customer')->get();

                foreach ($users as $user) {
                    $client->sendChatMessage($user->phone, $body);
                    //sleep for 15 seconds
                    sleep(15);
                }
            } elseif ($targetAudience == 'owners') {

                // Retrieve all users from the database
                $users = User::role('owner')->get();
                foreach ($users as $user) {
                    $client->sendChatMessage($user->phone, $body);
                    //sleep for 15 seconds
                    sleep(15);
                }
            }

            return response()->json(['message' => 'Test emails sent successfully to all users'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send test emails. ' . $e->getMessage()], 500);
        }


    }

    protected function sendFirebase($title, $body, $targetAudience)
    {
        $firebaseController = app(FirebasePushController::class);

        try {
            if ($targetAudience == 'all') {
                // Retrieve all users from the database
                $users = User::all();

                foreach ($users as $user) {
                    $request = new Request([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                    ]);

                    $response = $firebaseController->sendFirebaseNotification($request);
                }
            } elseif ($targetAudience == 'customers') {

                // Retrieve all users from the database
                $users = User::role('customer')->get();

                foreach ($users as $user) {
                    $request = new Request([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                    ]);

                    $response = $firebaseController->sendFirebaseNotification($request);
                }
            } elseif ($targetAudience == 'owners') {

                // Retrieve all users from the database
                $users = User::role('owner')->get();

                foreach ($users as $user) {
                    $request = new Request([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                    ]);

                    $response = $firebaseController->sendFirebaseNotification($request);
                }
            }

            return response()->json(['message' => 'Test emails sent successfully to all users'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send test emails. ' . $e->getMessage()], 500);
        }


    }
}
