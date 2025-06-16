<?php

namespace App\Http\Controllers\Web\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\LocationConfig;
use App\Models\PaymentConfiguration;
use App\Models\ServiceDefinition;
use App\Models\StoreTerms;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{

    public function index()
    {
        $latestTerms = StoreTerms::all()->last();
        $branch_range = LocationConfig::where('code', 'BR')->first();
        $customer_range = LocationConfig::where('code', 'CR')->first();
        $service_definition = ServiceDefinition::where('code', '1001.00')->first();
        $payment_settings = PaymentConfiguration::where('key', 'payment_check_time_limit')->first();

        return view('admin.settings.store', compact('latestTerms', 'branch_range', 'customer_range', 'service_definition', 'payment_settings'));
    }


    public function update(Request $request)
    {
        // Validation can be added here if needed

        $latestTerms = StoreTerms::latest()->first();

        if ($latestTerms) {
            $latestTerms->update([
                'body_ar' => $request->input('terms_ar'),
                'body_en' => $request->input('terms_en'),
                'issued_at' => Carbon::now('Asia/Riyadh'),
            ]);
        } else {
            StoreTerms::create([
                'body_ar' => $request->input('terms_ar'),
                'body_en' => $request->input('terms_en'),
                'issued_at' => Carbon::now('Asia/Riyadh'),
            ]);
        }

        // Redirect or perform any other action after updating
        return redirect()->back()->with('success', 'Terms and conditions updated successfully');
    }

    public function update_branch_range(Request $request)
    {
        // Validate the request data as needed
        $request->validate([
            'max_radius' => 'required|numeric',
            'min_radius' => 'required|numeric',
        ]);

        // Update the existing record with code 'BR' and name 'branch-range'
        LocationConfig::where('code', 'BR')
            ->update([
                'unit' => $request->input('unit'),
                'max_radius' => $request->input('max_radius'),
                'min_radius' => $request->input('min_radius'),
            ]);

        // Redirect or perform any other action after updating
        return redirect()->back()->with('success', 'Branch Range updated successfully');
    }

    public function update_customer_range(Request $request)
    {
        // Validate the request data as needed
        $request->validate([
            'max_radius' => 'required|numeric',
        ]);

        // Update the existing record with code 'CR' and name 'customer-range'
        LocationConfig::where('code', 'CR')
            ->update([
                'unit' => $request->input('unit'),
                'max_radius' => $request->input('max_radius'),
            ]);

        // Redirect or perform any other action after updating
        return redirect()->back()->with('success', 'Customer Range updated successfully');
    }

    public function update_payment_time(Request $request)
    {
        // Validate the request data
        $request->validate([
            'payment_time' => 'required|numeric|min:1',
        ]);

        // Update or create the payment time limit in the payment_configurations table
        PaymentConfiguration::updateOrCreate(
            ['key' => 'payment_check_time_limit'],
            ['value' => $request->input('payment_time')]
        );

        // Redirect or perform any other action after updating
        return redirect()->back()->with('success', 'Payment time limit updated successfully');
    }


}
