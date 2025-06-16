<?php

namespace App\Http\Controllers\Web\Admin\Settings\Platform;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class WebPlatformSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the general web platform settings.
     */
    public function general()
    {
        // Your logic to display general web platform settings
    }

    /**
     * Show the user management settings for the web platform.
     */
    public function users()
    {
        // Your logic to display user management settings
    }

    /**
     * Show the content management settings for the web platform.
     */
    public function content()
    {
        // Your logic to display content management settings
    }

    public function updateTermsConditions(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'terms_ar' => 'required|string',
            'terms_en' => 'required|string',
        ]);

        // Check if there's an existing terms and conditions record
        $existingTerms = TermsCondition::first();

        // If there's an existing terms & conditions, expire it
        if ($existingTerms) {
            $existingTerms->update([
                'expired_at' => now()
            ]);
        }

        // Create a new terms & conditions record
        TermsCondition::create([
            'body_ar' => $validatedData['terms_ar'],
            'body_en' => $validatedData['terms_en'],
            'issued_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Terms & Conditions updated successfully!');
    }

    public function updatePrivacyPolicies(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'privacy_ar' => 'required|string',
            'privacy_en' => 'required|string',
        ]);

        // Check if there's an existing privacy policy record
        $existingPolicy = PrivacyPolicy::first();

        // If there's an existing privacy policy, expire it
        if ($existingPolicy) {
            $existingPolicy->update([
                'expired_at' => now()
            ]);
        }

        // Create a new privacy policy record
        PrivacyPolicy::create([
            'body_ar' => $validatedData['privacy_ar'],
            'body_en' => $validatedData['privacy_en'],
            'issued_at' => now(),
            // Since you mentioned to leave 'expired_at' null for new records
        ]);

        return redirect()->back()->with('message', 'Privacy Policies updated successfully!');
    }


}
