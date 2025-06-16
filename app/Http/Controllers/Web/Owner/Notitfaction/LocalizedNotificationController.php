<?php

namespace App\Http\Controllers\Web\Owner\Notitfaction;

use App\Http\Controllers\Controller;
use App\Models\LocalizedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalizedNotificationController extends Controller
{
    public function index()
    {
        $localizedNotifications = LocalizedNotification::all();
        return response()->json($localizedNotifications);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'notification_type_id' => 'required|exists:notification_types,id',
            'locale' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create and store a new localized notification
        $localizedNotification = LocalizedNotification::create($request->all());

        return response()->json(['message' => 'Localized notification created', 'data' => $localizedNotification], 201);
    }

    public function show($id)
    {
        $localizedNotification = LocalizedNotification::findOrFail($id);
        return response()->json($localizedNotification);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'notification_type_id' => 'required|exists:notification_types,id',
            'locale' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $localizedNotification = LocalizedNotification::findOrFail($id);
        $localizedNotification->update($request->all());

        return response()->json(['message' => 'Localized notification updated', 'data' => $localizedNotification]);
    }

    public function destroy($id)
    {
        $localizedNotification = LocalizedNotification::findOrFail($id);
        $localizedNotification->delete();

        return response()->json(['message' => 'Localized notification deleted']);
    }
}
