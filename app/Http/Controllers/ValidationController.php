<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Store;
use App\Models\User;
use App\Models\Usher;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function validateContactNo(Request $request)
    {
        $contactNo = $request->input('contact_no');

        // Check if the contact number already exists in the database
        $existingUser = User::where('contact_no', $contactNo)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Contact number already taken'], 422);
        } else {
            return response()->json(['message' => 'Contact number is available'], 200);
        }
    }

    public function validateEmail(Request $request)
    {
        $email = $request->input('email');

        // Check if the email already exists in the database
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Email is already taken'], 422);
        } else {
            return response()->json(['message' => 'Email is available'], 200);
        }
    }

    public function validateUsherCode(Request $request)
    {
        $code_usher = $request->input('usher_code');

        // Check if the email already exists in the database
        $existingUser = Usher::where('code_usher', $code_usher)->first();

        if (!$existingUser) {
            return response()->json(['message' => 'Usher code not found'], 422);
        } else {
            return response()->json(['message' => 'founded'], 200);
        }
    }

    public function validateTaxId(Request $request)
    {
        $taxIdNumber = $request->input('tax_id');

        // Check if the tax ID number already exists in the database
        $existingTaxIdNumber = Store::where('tax_id_number', $taxIdNumber)->first();

        if ($existingTaxIdNumber) {
            return response()->json(['message' => 'Tax ID number already taken'], 422);
        } else {
            return response()->json(['message' => 'Tax ID number is available'], 200);
        }
    }

    public function validateCommercialRegistration(Request $request)
    {
        $commercialRegistrationNumber = $request->input('commercial_registration');

        // Check if the commercial registration number already exists in the database
        $existingCommercialRegistration = Store::where('commercial_registration_no', $commercialRegistrationNumber)->first();

        if ($existingCommercialRegistration) {
            return response()->json(['message' => 'Commercial registration number already taken'], 422);
        } else {
            return response()->json(['message' => 'Commercial registration number is available'], 200);
        }
    }


    public function validateMunicipalLicense(Request $request)
    {
        $municipalLicenseNumber = $request->input('municipal_license');

        // Check if the municipal license number is null or empty
        if (empty($municipalLicenseNumber)) {
            return response()->json(['message' => 'Municipal license number is empty or not provided'], 200);
        }

        // Check if the municipal license number already exists in the database
        $existingMunicipalLicense = Store::where('municipal_license_no', $municipalLicenseNumber)->first();

        if ($existingMunicipalLicense) {
            return response()->json(['message' => 'Municipal license number already taken'], 422);
        } else {
            return response()->json(['message' => 'Municipal license number is available'], 200);
        }
    }


    public function validateIban(Request $request)
    {
        $ibanNumber = $request->input('iban_number');

        // Check if the IBAN number already exists in the database
        $existingIban = BankAccount::where('iban_number', $ibanNumber)->first();

        if ($existingIban) {
            return response()->json(['message' => 'IBAN already exists'], 422);
        } else {
            return response()->json(['message' => 'Valid IBAN'], 200);
        }
    }

    public function validateContactNoStore(Request $request)
    {
        $contactNoStore = $request->input('contact_no');

        // Check if the contact number store already exists in the database
        $existingContactNoStore = Store::where('contact_no', $contactNoStore)->first();

        if ($existingContactNoStore) {
            return response()->json(['message' => 'Contact number store already taken'], 422);
        } else {
            return response()->json(['message' => 'Contact number store is available'], 200);
        }
    }

    public function validateContactNoStoreSecondary(Request $request)
    {
        $contactNoStoreSecondary = $request->input('contact_no_secondary');

        // Check if the secondary contact number store already exists in the database
        $existingContactNoStoreSecondary = Store::where('secondary_contact_no', $contactNoStoreSecondary)->first();

        if ($existingContactNoStoreSecondary) {
            return response()->json(['message' => 'Secondary contact number store already taken'], 422);
        } else {
            return response()->json(['message' => 'Secondary contact number store is available'], 200);
        }
    }
}

