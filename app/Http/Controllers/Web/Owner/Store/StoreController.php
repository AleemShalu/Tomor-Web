<?php

namespace App\Http\Controllers\Web\Owner\Store;

use App\Exports\BaseExport;
use App\Exports\BasePDFExport;
use App\Helpers\FormHelper;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\BankAccount;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\Store;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class StoreController extends Controller
{

    public function index()
    {
        $user_id = auth()->user()->id;
        $stores = Store::with('business_type')->where('owner_id', $user_id)->get();
        return view('owner.store.index', compact('stores'));
    }

    /**
     * Display a listing of the resource.
     */
    public function create()
    {
        $countries = Country::all();
        $businesses = BusinessType::all();

        return view('owner.store.create', compact('countries', 'businesses'));
    }

    /**
     * Show the form for creating a new resource.
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'business_type_id' => 'required|numeric|exists:App\Models\BusinessType,id',
            'commercial_name_en' => 'required|string|min:1|max:100',
            'commercial_name_ar' => 'required|string|min:1|max:100',
            'short_name_en' => 'required|string|min:1|max:20',
            'short_name_ar' => 'required|string|min:1|max:20',
            'country_id' => 'required|numeric|exists:App\Models\Country,id',
            'dial_code_contact_no' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => 'required|max:15|regex:/^[0-9]+$/',
            'dial_code_secondary_contact_no' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'secondary_contact_no' => [
                'nullable',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('stores')->where(function ($query) use ($request) {
                    return $query->where('secondary_dial_code', $request->input('dial_code_secondary_contact_no'));
                })->ignore(null, 'id'),
            ],
            'tax_id_number' => 'required|numeric|digits:15',
            'tax_id_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'commercial_registration_no' => 'required|numeric|digits:10',
            'commercial_registration_expiry' => 'required',
            'commercial_registration_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'municipal_license_no' => 'nullable|numeric|digits:11',
            'logo' => 'required|file|image|mimes:jpg,jpeg,png|max:1024', // up to 5 MB
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

        // Begin a database transaction
        DB::beginTransaction();

        try {
            $owner = auth()->user();

            // Create a new store instance
            $store = new Store();
            $store->business_type_id = $validator->validated()['business_type_id'];
            $store->commercial_name_en = $validator->validated()['commercial_name_en'];
            $store->commercial_name_ar = $validator->validated()['commercial_name_ar'];
            $store->short_name_en = $validator->validated()['short_name_en'];
            $store->short_name_ar = $validator->validated()['short_name_ar'];
            $store->country_id = $validator->validated()['country_id'];
            $store->contact_no = $validator->validated()['contact_no'];
            $store->dial_code = $request->input('country_code');
            $store->secondary_contact_no = $validator->validated()['secondary_contact_no'];
            $store->secondary_dial_code = $request->input('country_code_secondary');
            $store->tax_id_number = $validator->validated()['tax_id_number'];
            //        $store->website = $validator->validated()['website'];
            $store->commercial_registration_no = $validator->validated()['commercial_registration_no'];
            $store->commercial_registration_expiry = convertHijriToGregorian($validator->validated()['commercial_registration_expiry'], '/');
            $store->municipal_license_no = $validator->validated()['municipal_license_no'];
            $store->description_ar = $validator->validated()['description_ar'];
            $store->description_en = $validator->validated()['description_en'];
            $store->status = 0;
            $store->owner_id = $owner->id;
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

            // upload store tax_id_attachment file
            if (request('tax_id_attachment')) {
                $store_attachments_folder = 'stores/' . $store->id . '/attachments';
                if (!File::exists(storage_path($store_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_attachments_folder);
                }
                $tax_id_attachment_file = request('tax_id_attachment');
                $tax_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_attachments_folder,
                    $tax_id_attachment_file,
                    uniqid('tax-id-attachments-', true) . '.' . $tax_id_attachment_file->getClientOriginalExtension(),
                );
                $store->tax_id_attachment = $tax_attachment_path;
            }

            // upload store commercial_registration_attachment file
            if (request('commercial_registration_attachment')) {
                $store_attachments_folder = 'stores/' . $store->id . '/attachments';
                if (!File::exists(storage_path($store_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_attachments_folder);
                }
                $cr_attachment_file = request('commercial_registration_attachment');
                $cr_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_attachments_folder,
                    $cr_attachment_file,
                    uniqid('store-cr-attachment-', true) . '.' . $cr_attachment_file->getClientOriginalExtension(),
                );
                $store->commercial_registration_attachment = $cr_attachment_path;
            }

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $store_images_folder = 'stores/' . $store->id . '/images';

                if (!Storage::disk(getSecondaryStorageDisk())->exists($store_images_folder)) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }

                $thumb_img_logo = $request->file('logo');
                $filename = uniqid('store-logo-', true) . '.' . $thumb_img_logo->getClientOriginalExtension();
                $logo_path = $store_images_folder . '/' . $filename;
                $outputPath = Storage::disk(getSecondaryStorageDisk())->path($logo_path);

                // Resize the image using FormHelper
                $resized = FormHelper::resize(300, $outputPath, $thumb_img_logo->getPathname());

                if ($resized) {
                    $store->logo = $logo_path;
                }
            }

            if ($request->has('croppedImageData')) {
                // Get the base64-encoded image data
                $croppedImageData = $request->input('croppedImageData');

                // Convert the base64 data to binary
                $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

                // Determine the file path
                $storeHeaderFolder = 'stores/' . $store->id . '/header';
                if (!Storage::disk(getSecondaryStorageDisk())->exists($storeHeaderFolder)) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($storeHeaderFolder);
                }

                $headerPath = $storeHeaderFolder . '/' . uniqid('header-', true) . '.png'; // You can use a specific extension
                $outputPath = Storage::disk(getSecondaryStorageDisk())->path($headerPath);

                // Resize and store the image using FormHelper
                $resized = FormHelper::resizeFromBinary(1200, $outputPath, $binaryImageData);

                if ($resized) {
                    // Update the store model with the image path
                    $store->store_header = $headerPath;
                }
            }
            // Commit the transaction if all steps are successful
            DB::commit();

            // Save the store model
            $store->save();

            // Optionally notify admin or user about the new store registration
            $admin = User::find(1);
            $notificationChannels = ['mail', 'database', 'web']; // Specify the desired notification channels
            $title = 'New Store Registered';
            $message = "$owner->name registered new store name : $store->short_name_ar";
            $admin->notify(new SendMessage($admin, 2, $notificationChannels, $message, $title));

            // Redirect with success message
            return redirect()->route('store')->withSuccess('Store created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error occurred during store creation: ' . $e->getMessage());
            return redirect()->back()->withError('Failed to create store. Please try again.')->withInput();
        }
    }


    public function uploadMenu(Request $request)
    {
        $store = Store::find($request->store_id);

        // Validate the uploaded file
        $request->validate([
            'menu_pdf' => 'required|mimes:pdf|max:2048', // up to 2 MB
        ]);

        // Handle logo upload
        if ($request->hasFile('menu_pdf')) {
            $pdf = $request->file('menu_pdf');

            // Store the file in the specified directory
            $directory = 'stores/' . $store->id . '/menu_pdf';
            $pdfPath = $pdf->storeAs(
                $directory,
                uniqid('store-pdf-', true) . '.' . $pdf->getClientOriginalExtension(),
                getSecondaryStorageDisk()
            );
            $store->menu_pdf = $pdfPath;
            $store->save();

            return redirect()->back()->with('success', 'Menu PDF uploaded successfully.');

        }
    }


    public function destroyMenu(Request $request)
    {
        $store = Store::find($request->store_id);

        // Delete the menu PDF file
        Storage::delete($store->menu_pdf);

        // Clear the file path in the store record
        $store->menu_pdf = null;
        $store->save();

        return redirect()->back()->with('success', 'Menu PDF deleted successfully.');
    }

    public function updateMenu(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);
        // Retrieve the store with the given ID
        $this->authorize('manage', $store);

        // Validate the uploaded file
        $request->validate([
            'menu_pdf' => 'required|mimes:pdf|max:2048', // up to 2 MB
        ]);

        if ($request->hasFile('menu_pdf')) {
            $file = $request->file('menu_pdf');

            // Store the file in the specified directory
            $directory = 'stores/' . $store->id . '/menu_pdf';
            $path = $file->storeAs($directory, 'menu_pdf.pdf');

            // Update the store record with the file path
            $store->menu_pdf = $path;
            $store->save();

            return redirect()->back()->with('success', 'Menu PDF uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload menu PDF.');
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
        ]);


        // Check if the new commercial names are different from the current ones
        $store->commercial_name_en = $request->commercial_name_en;
        $store->commercial_name_ar = $request->commercial_name_ar;
        $store->short_name_en = $request->short_name_en;
        $store->short_name_ar = $request->short_name_ar;
        $store->description_ar = $request->description_ar;
        $store->description_en = $request->description_en;

        $store->save();

        return redirect()->route('settings.manage', $store_id)->withSuccess('Commercial Name Updated Successfully.');
    }


    public function updateContactInformation(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);
        // Retrieve the store with the given ID
        $this->authorize('manage', $store);

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

        return redirect()->route('settings.manage', $store_id)->withSuccess('Contact Information Updated Successfully.');
    }

    public function updateAdditionalInformation(Request $request)
    {

        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);
        // Retrieve the store with the given ID
        $this->authorize('manage', $store);

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

        return redirect()->route('settings.manage', $store_id)->withSuccess('Additional Information Updated Successfully.');
    }


    public function updateWebsiteAndLogo(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::findOrFail($store_id);
        // Retrieve the store with the given ID
        $this->authorize('manage', $store);

        $request->validate([
            'logo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:1024', // up to 1 MB
            'croppedImageData' => 'nullable',
        ]);

        $store->website = $request->website;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $store_images_folder = 'stores/' . $store->id . '/images';

            if (!Storage::disk(getSecondaryStorageDisk())->exists($store_images_folder)) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
            }

            $thumb_img_logo = $request->file('logo');
            $filename = uniqid('store-logo-', true) . '.' . $thumb_img_logo->getClientOriginalExtension();
            $logo_path = $store_images_folder . '/' . $filename;
            $outputPath = Storage::disk(getSecondaryStorageDisk())->path($logo_path);

            // Resize the image using FormHelper
            $resized = FormHelper::resize(300, $outputPath, $thumb_img_logo->getPathname());

            if ($resized) {
                $store->logo = $logo_path;
            }
        }

        if ($request->has('croppedImageData')) {
            // Get the base64-encoded image data
            $croppedImageData = $request->input('croppedImageData');

            // Convert the base64 data to binary
            $binaryImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData));

            // Determine the file path
            $storeHeaderFolder = 'stores/' . $store->id . '/header';
            if (!Storage::disk(getSecondaryStorageDisk())->exists($storeHeaderFolder)) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($storeHeaderFolder);
            }

            $headerPath = $storeHeaderFolder . '/' . uniqid('header-', true) . '.png'; // You can use a specific extension
            $outputPath = Storage::disk(getSecondaryStorageDisk())->path($headerPath);

            // Resize and store the image using FormHelper
            $resized = FormHelper::resizeFromBinary(1200, $outputPath, $binaryImageData);

            if ($resized) {
                // Update the store model with the image path
                $store->store_header = $headerPath;
            }
        }
        // Commit the transaction if all steps are successful
        DB::commit();


        $store->save();

        return redirect()->route('settings.manage', $store_id)->withSuccess('Website and Logo Updated Successfully.');
    }

    public function updateRangeTimeOrder(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'time_range_select' => 'required|integer|min:1',
        ]);

        $store_id = $validatedData['store_id'];
        // Find the store
        $store = Store::findOrFail($validatedData['store_id']);

        // Check if the authenticated user is the owner of the store
        if (Auth::user()->id !== $store->owner_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the range_time_order
        $store->range_time_order = $validatedData['time_range_select'];
        $store->save();

        return redirect()->route('settings.manage', $store_id)->withSuccess('Range Time Updated Successfully.');
    }


    public function exportExcel(Request $request)
    {
        // Define validation rules
        $rules = [
            'owner_id' => 'required|integer|exists:users,id',
        ];

        // Validate the request
        $validated = $request->validate($rules);

        // Extract validated data
        $model = Store::class;
        $selects = ['owner_id' => $request->owner_id];
        $columns = (new Store)->columnsExport();

        return Excel::download(new BaseExport($model, $selects, $columns), 'stores-owner.xlsx');
    }

    public function exportPDF(Request $request): Response
    {
        $rules = [
            'owner_id' => 'required|integer|exists:users,id',
        ];

        $validated = $request->validate($rules);

        $model = Store::class;
        $selects = ['owner_id' => $request->owner_id];
        $columns = (new Store)->columnsExport();

        $reportName = 'تقرير المتاجر';
        $pdfExport = new BasePDFExport($model, $selects, $columns, $reportName, 'L');
        $pdfContent = $pdfExport->generate();


        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="stores-owner.pdf"');
    }

}
