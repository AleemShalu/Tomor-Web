<?php

namespace App\Http\Controllers\Web\Admin\Store;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::with('owner')->get();
        return view('admin.store.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $businesses = BusinessType::all();
        return view('admin.store.create', compact('countries', 'businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    //            'owner_id' => 'required',
    public function store(Request $request)
    {
        $owner_id = Request('owner_id');
        $id = null;
        $validator = Validator::make($request->all(), [
            'business_type_id' => 'required|numeric|exists:App\Models\BusinessType,id',
            'commercial_name_en' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]+$/',
            'commercial_name_ar' => 'required|string|min:1|max:100|regex:/^[0-9أ-ي ]+$/u',
            'short_name_en' => 'required|string|min:1|max:20|regex:/^[a-zA-Z0-9 ]+$/',
            'short_name_ar' => 'required|string|min:1|max:20|regex:/^[0-9أ-ي ]+$/u',
            'country_id' => 'required|numeric|exists:App\Models\Country,id',
            'country_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'capacity' => ['required', Rule::in(['large', 'small'])],
            'contact_no' => [
                'required',
                'max:15',
                // 'regex:/^[0-9]+$/',
//                Rule::unique('stores')->where(fn ($query) => $query->where('dial_code', request('dial_code', null)))->ignore($id),
            ],
            'secondary_contact_no' => 'required',
            'country_code_secondary' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'tax_id_number' => 'required|numeric|digits:15',
            'tax_id_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'commercial_registration_no' => 'required|numeric|digits:10',
            'commercial_registration_expiry' => 'required',
            'commercial_registration_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'municipal_license_no' => 'nullable|numeric|digits:11',
            'logo' => 'required|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
            'croppedImageData' => 'required', // up to 1 MB
            // 'description_ar' => 'required|string|min:1|max:500|regex:/^[0-9أ-ي ]+$/u', // Arabic text and numbers
            // 'description_en' => 'required|string|min:1|max:500|regex:/^[a-zA-Z0-9 ]+$/', // English text and numbers
            'description_ar' => 'required|string|min:1|max:1000', // Arabic text and numbers
            'description_en' => 'required|string|min:1|max:1000', // English text and numbers
            'account_holder_name' => 'required|string|min:2|max:255',
            'iban_number' => ['required', 'string', 'unique:App\Models\BankAccount,iban_number'],
            'iban_attachment' => 'required|mimes:pdf|max:1024', // up to 1 MB
            'bank_name' => 'required|string|min:1|max:50|',
            'status' => [
                'nullable',
                Rule::in([0, 1]),
            ],

        ], [
            'commercial_name_ar.regex' => 'The :attribute field must contain Arabic characters, numbers, and spaces only.',
            'short_name_ar.regex' => 'The :attribute field must contain Arabic characters, numbers, and spaces only.',
            'commercial_name_en.regex' => 'The :attribute field must contain only English letters, numbers, and spaces.',
            'short_name_en.regex' => 'The :attribute field must contain only English letters, numbers, and spaces.',
            'description_ar.regex' => 'The :attribute field must contain Arabic characters, numbers, and spaces only.',
            'description_en.regex' => 'The :attribute field must contain only English letters, numbers, and spaces.',
        ]);

        // If validation fails, redirect back with errors and old input
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new store using the validated data
        $store = new Store();
        $store->business_type_id = $validator->validated()['business_type_id'];
        $store->commercial_name_en = $validator->validated()['commercial_name_en'];
        $store->commercial_name_ar = $validator->validated()['commercial_name_ar'];
        $store->short_name_en = $validator->validated()['short_name_en'];
        $store->short_name_ar = $validator->validated()['short_name_ar'];
        $store->country_id = $validator->validated()['country_id'];

        $store->contact_no = $validator->validated()['contact_no'];
        $store->dial_code = $validator->validated()['country_code'];

        $store->secondary_contact_no = $validator->validated()['secondary_contact_no'];
        $store->secondary_dial_code = $validator->validated()['country_code_secondary'];

        $store->tax_id_number = $validator->validated()['tax_id_number'];
        $store->commercial_registration_no = $validator->validated()['commercial_registration_no'];
        $store->commercial_registration_expiry = convertHijriToGregorian($validator->validated()['commercial_registration_expiry'], '/');
        $store->municipal_license_no = $validator->validated()['municipal_license_no'];
//        $store->website = $validator->validated()['website'];
        $store->description_ar = $validator->validated()['description_ar'];
        $store->description_en = $validator->validated()['description_en'];
        $store->capacity = $validator->validated()['capacity'];
        $store->status = 1;
        // Set the owner ID
        $store->owner_id = $owner_id;

        // Save the store record
        $store->save();
        // Create a new bank account
        $bankAccount = new BankAccount();
        $bankAccount->account_holder_name = $validator->validated()['account_holder_name'];
        $bankAccount->iban_number = $validator->validated()['iban_number'];
        $bankAccount->bank_name = $validator->validated()['bank_name'];
        $bankAccount->store_id = $store->id;
        $bankAccount->save();
        if (isset($validator->validated()['iban_attachment'])) { // upload bank account iban_attachment file
            $store_iban_attachments_folder = 'stores/' . $store->id . '/attachments/iban-attachments/' . $bankAccount->id;
            if (!File::exists(storage_path($store_iban_attachments_folder))) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_iban_attachments_folder);
            }
            $iban_attachment_file = $validator->validated()['iban_attachment'];
            $iban_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                $store_iban_attachments_folder,
                $iban_attachment_file,
                uniqid('iban-attachments-', true) . '.' . $iban_attachment_file->getClientOriginalExtension(),
            );
            $bankAccount->iban_attachment = $iban_attachment_path;
            $bankAccount->save();
        }

        // Handle tax_id_attachment upload
        if ($request->hasFile('tax_id_attachment')) {
            $taxIdAttachment = $request->file('tax_id_attachment');
            $storeTaxAttachmentsFolder = 'stores/' . $store->id . '/attachments';
            $taxAttachmentPath = $taxIdAttachment->storeAs(
                $storeTaxAttachmentsFolder,
                uniqid('tax-id-attachments-', true) . '.' . $taxIdAttachment->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->tax_id_attachment = $taxAttachmentPath;
        }

        // Handle commercial_registration_attachment upload
        if ($request->hasFile('commercial_registration_attachment')) {
            $commercialRegistrationAttachment = $request->file('commercial_registration_attachment');
            $storeCrAttachmentsFolder = 'stores/' . $store->id . '/attachments';
            $crAttachmentPath = $commercialRegistrationAttachment->storeAs(
                $storeCrAttachmentsFolder,
                uniqid('store-cr-attachment-', true) . '.' . $commercialRegistrationAttachment->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->commercial_registration_attachment = $crAttachmentPath;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $storeLogoFolder = 'stores/' . $store->id . '/logo';
            $logoPath = $logo->storeAs(
                $storeLogoFolder,
                uniqid('store-logo-', true) . '.' . $logo->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->logo = $logoPath;
        }

        if ($request->has('croppedImageData')) {
            // Get the base64-encoded image data
            $croppedImageData = $request->input('croppedImageData');

            // Convert the base64 data to binary
            $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

            // Determine the file path
            $storeHeaderFolder = 'stores/' . $store->id . '/header';
            if (!Storage::disk('public')->exists($storeHeaderFolder)) {
                Storage::disk('public')->makeDirectory($storeHeaderFolder);
            }

            $headerPath = $storeHeaderFolder . '/' . uniqid('header-', true) . '.png'; // You can use a specific extension

            // Store the binary image data
            Storage::disk('public')->put($headerPath, $binaryImageData);

            // Update the store model with the image path
            $store->store_header = $headerPath;
        }

        // Save the store model
        $store->save();

        return redirect()->route('admin.store.index')->withSuccess('store created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = Store::with('owner', 'branches', 'bank_accounts', 'employees.employee_roles', 'products', 'business_type')->find($id);
        $employees = $store->employees()->paginate(10); // Change '10' to the number of employees you want to display per page.
        // Retrieve the BusinessType using the ID
        $businessType = BusinessType::find($store->business_type_id);
        $products = Product::all()->where('store_id', $id);
//        return response()->json($store);

        return view('admin.store.view', compact('store', 'employees', 'businessType', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = Store::with('owner', 'branches', 'bank_accounts', 'employees.employee_roles')->find($id);
        $countries = Country::all();
        $banks = BankAccount::all()->where('store_id', $id);
        return view('admin.store.edit', compact('store', 'countries', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateCommercialName(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);

        $request->validate([
            'commercial_name_en' => 'required|string|max:255',
            'commercial_name_ar' => 'required|string|max:255',
            'short_name_en' => 'required|string|max:255',
            'short_name_ar' => 'required|string|max:255',
            'description_ar' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'capacity' => 'required|in:large,small',
        ]);


        $store->commercial_name_en = $request->commercial_name_en;
        $store->commercial_name_ar = $request->commercial_name_ar;
        $store->short_name_en = $request->short_name_en;
        $store->short_name_ar = $request->short_name_ar;
        $store->description_ar = $request->description_ar;
        $store->description_en = $request->description_en;
        $store->capacity = $request->capacity;
        $store->save();

        return redirect()->route('admin.store.edit', $store_id)->withSuccess('Commercial Name Updated Successfully.');
    }


    public function updateContactInformation(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);

        $request->validate([
            'contact_no' => 'required|string|max:255',
            'secondary_contact_no' => 'nullable|string|max:255',
        ]);

        // Check if the provided contact_no is unique among other stores
        $uniqueContactNoRule = Rule::unique('stores')->ignore($store_id);
        $request->validate([
            'contact_no' => ['required', 'string', 'max:255', $uniqueContactNoRule],
        ]);

        // Check if the provided secondary_contact_no is unique among other stores
        $uniqueSecondaryContactNoRule = Rule::unique('stores')->ignore($store_id);
        $request->validate([
            'secondary_contact_no' => ['nullable', 'string', 'max:255', $uniqueSecondaryContactNoRule],
        ]);

        // Update the store's contact information
        $store->contact_no = $request->contact_no;
        $store->dial_code = $request->country_code;

        $store->secondary_contact_no = $request->secondary_contact_no;
        $store->secondary_dial_code = $request->country_code_secondary;

        $store->save();

        return redirect()->route('admin.store.edit', $store_id)->withSuccess('Contact Information Updated Successfully.');
    }

    public function updateAdditionalInformation(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);

//        return $request;
        $request->validate([
            'tax_id_number' => 'required|numeric|digits:15',
            'tax_id_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'commercial_registration_no' => 'required|numeric|digits:10',
            'commercial_registration_expiry_date' => 'required',
            'commercial_registration_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'municipal_license_no' => 'nullable|numeric|digits:11',
        ]);

        // Check if the provided tax_id_number is unique among other stores
        $uniqueTaxIdNumberRule = Rule::unique('stores')->ignore($store_id);
        $request->validate([
            'tax_id_number' => ['required', 'numeric', 'digits:15', $uniqueTaxIdNumberRule],
        ]);

        // Check if the provided commercial_registration_no is unique among other stores
        $uniqueCommercialRegistrationNoRule = Rule::unique('stores')->ignore($store_id);
        $request->validate([
            'commercial_registration_no' => ['required', 'numeric', 'digits:10', $uniqueCommercialRegistrationNoRule],
        ]);

        // Check if the provided municipal_license_no is unique among other stores
        $uniqueMunicipalLicenseNoRule = Rule::unique('stores')->ignore($store_id);
        $request->validate([
            'municipal_license_no' => ['nullable', 'numeric', 'digits:11', $uniqueMunicipalLicenseNoRule],
        ]);


        $store->tax_id_number = $request->tax_id_number;

        // Handle tax_id_attachment upload
        if ($request->hasFile('tax_id_attachment')) {
            $taxIdAttachment = $request->file('tax_id_attachment');
            $storeTaxAttachmentsFolder = 'stores/' . $store->id . '/attachments';
            $taxAttachmentPath = $taxIdAttachment->storeAs(
                $storeTaxAttachmentsFolder,
                uniqid('tax-id-attachments-', true) . '.' . $taxIdAttachment->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->tax_id_attachment = $taxAttachmentPath;
        }

        $store->commercial_registration_no = $request->commercial_registration_no;
        $store->commercial_registration_expiry = $request->commercial_registration_expiry_date;

        // Handle commercial_registration_attachment upload
        if ($request->hasFile('commercial_registration_attachment')) {
            $commercialRegistrationAttachment = $request->file('commercial_registration_attachment');
            $storeCrAttachmentsFolder = 'stores/' . $store->id . '/attachments';
            $crAttachmentPath = $commercialRegistrationAttachment->storeAs(
                $storeCrAttachmentsFolder,
                uniqid('store-cr-attachment-', true) . '.' . $commercialRegistrationAttachment->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->commercial_registration_attachment = $crAttachmentPath;
        }

        $store->municipal_license_no = $request->municipal_license_no;
        $store->save();

        return redirect()->route('admin.store.edit', $store_id)->withSuccess('Additional Information Updated Successfully.');
    }


    public function updateStoreStatus($id)
    {
        // Retrieve the store by ID
        $store = Store::findOrFail($id);

        // Toggle the store status between 1 and 0
        $store->status = $store->status === 1 ? 0 : 1;

        // Save the updated store status
        $store->save();

        // Redirect back to the store listing page or perform any other actions
        return redirect()->route('admin.store.index')->with('success', 'Store status updated successfully.');
    }

    public function stores_ajax(Request $request)
    {
        if ($request->ajax()) {
            $usersQuery = Store::all();
            return Datatables::of($usersQuery->toArray($request))->make(true);
        } else {
            abort(403);
        }
    }

    public function search_owners_ajax(Request $request)
    {
        $query = $request->input('query');

        $owners = User::with('roles')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('contact_no', 'like', '%' . $query . '%');
            })
            ->whereHas('roles', function ($queryBuilder) {
                $queryBuilder->where('name', 'owner');
            })
            ->get();

        return response()->json($owners);
    }

}
