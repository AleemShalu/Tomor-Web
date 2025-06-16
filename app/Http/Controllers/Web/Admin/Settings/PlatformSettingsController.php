<?php

namespace App\Http\Controllers\Web\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\Setting;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class PlatformSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        $latestTerms = TermsCondition::latest('created_at')->first();
        $latestPrivacyPolicy = PrivacyPolicy::latest('created_at')->first();

        return view('admin.settings.platform', compact('latestTerms', 'latestPrivacyPolicy', 'settings'));
    }

    public function changeStatusPlatform(Request $request)
    {
        // Assuming there's only one settings row in the database
        $settings = Setting::first();

        // Update the statuses based on the request data.
        // I'm assuming the request will send data like: ['web_status' => true, 'app_status' => false]
        $settings->web_status = $request->input('web_application') === "on" ? true : false;
        $settings->app_status = $request->input('mobile_app') === "on" ? true : false;

        $settings->save();

        // Redirect or return response after the update
        return redirect()->back()->with('message', 'Settings updated successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
}
