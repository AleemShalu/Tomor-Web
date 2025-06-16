<?php

namespace App\Http\Controllers\Web\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\ServiceDefinition;
use Illuminate\Http\Request;

class ServiceDefinitionController extends Controller
{
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'code' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        // Find the service definition by code and update it
        $serviceDefinition = ServiceDefinition::where('code', $request->code)->firstOrFail();

        // Update the service definition with the provided data
        $serviceDefinition->update([
            'price' => $request->price,
        ]);

        // Redirect back with a success message
        return back()->with('success', 'Service definition updated successfully.');
    }
}
