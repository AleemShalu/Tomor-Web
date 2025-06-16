<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\BankAccount;
use App\Models\Store;
use App\Traits\Api\UserIsAuthorizedTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Validation\Rules\Iban;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    use UserIsAuthorizedTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['owner']);
        if ($error) return $error;

        try {

            $list_of_stores_belong_to_owner = Store::with([
                'business_type',
                'owner',
                'country',
                // 'employees',
                // 'branches',
                // 'products',
                'bank_accounts',
            ])->where('owner_id', auth()->user()->id)
                ->orderBy('created_at', 'asc')
                ->paginate(request('limit', 10));

            return StoreResource::collection($list_of_stores_belong_to_owner);

        } catch (QueryException $e) {
            Log::error('API:StoreController:index: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['owner']);
        if ($error) return $error;

        $storeValidationRules = new StoreRequest();
        $rules = array_merge($storeValidationRules->rules(), [
            'bank_accounts' => 'required|array|min:1',
            'bank_accounts.*.account_holder_name' => 'required|string|min:1|max:50',
            'bank_accounts.*.iban_number' => ['required', 'string', new Iban(), 'unique:App\Models\BankAccount,iban_number'],
            'bank_accounts.*.iban_attachment' => 'required|mimes:pdf|max:1024', // up to 1 MB
            'bank_accounts.*.bank_name' => 'nullable|string|min:1|max:50|',
            'bank_accounts.*.swift_code' => 'nullable|string|min:8|max:11',
        ]);
        $messages['bank_accounts.*.iban_number.Intervention\Validation\Rules\Iban'] =
            __('validation.custom.iban_validation', ['index' => ':index']);
        $validator = Validator::make($request->all(), $rules, $messages);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        if (request('commercial_registration_expiry')) {
            $crExpiryToGregorian = convertHijriToGregorian(request('commercial_registration_expiry'), '-');
        }

        try {
            // create new store model
            $store = new Store();
            $store->business_type_id = request('business_type_id');
            $store->commercial_name_en = request('commercial_name_en');
            $store->commercial_name_ar = request('commercial_name_ar');
            $store->short_name_en = request('short_name_en');
            $store->short_name_ar = request('short_name_ar');
            $store->description_ar = request('description_ar');
            $store->description_en = request('description_en');
            $store->email = request('email') ? Str::lower(request('email')) : null;
            $store->country_id = request('country_id');
            $store->dial_code = request('contact_no') ? request('dial_code') : null;
            $store->contact_no = request('contact_no');
            $store->secondary_dial_code = request('secondary_contact_no') ? request('secondary_dial_code') : null;
            $store->secondary_contact_no = request('secondary_contact_no');
            $store->tax_id_number = request('tax_id_number');
            $store->commercial_registration_no = request('commercial_registration_no');
            $store->commercial_registration_expiry = isset($crExpiryToGregorian) ? $crExpiryToGregorian : null;
            $store->municipal_license_no = request('municipal_license_no');
            // $store->api_url = request('api_url');
            // $store->api_admin_url = request('api_admin_url');
            // $store->menu_pdf = request('menu_pdf');
            $store->website = request('website');
            $store->status = request('status', 0);
            $store->owner_id = request('owner_id', auth()->user()->id ?? null);
            $store->save();

            // add records for the list of store bank accounts
            if (request('bank_accounts')) {
                # id, store_id, account_holder_name, iban_number, iban_attachment, bank_name, swift_code, created_at, updated_at
                foreach (request('bank_accounts') as $bank_account) {
                    $bankAccount = new BankAccount();
                    $bankAccount->account_holder_name = $bank_account['account_holder_name'];
                    $bankAccount->iban_number = $bank_account['iban_number'];
                    $bankAccount->bank_name = $bank_account['bank_name'];
                    $bankAccount->swift_code = isset($bank_account['swift_code']) ? Str::upper($bank_account['swift_code']) : null;
                    $bankAccount->store_id = $store->id;
                    $bankAccount->save();
                    if (isset($bank_account['iban_attachment'])) { // upload bank account iban_attachment file
                        $store_iban_attachments_folder = 'stores/' . $store->id . '/attachments/iban-attachments/' . $bankAccount->id;
                        if (!File::exists(storage_path($store_iban_attachments_folder))) {
                            Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_iban_attachments_folder);
                        }
                        $iban_attachment_file = $bank_account['iban_attachment'];
                        $iban_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                            $store_iban_attachments_folder,
                            $iban_attachment_file,
                            uniqid('iban-attachments-', true) . '.' . $iban_attachment_file->getClientOriginalExtension(),
                        );
                        $bankAccount->iban_attachment = $iban_attachment_path;
                        $bankAccount->save();
                    }
                }
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

            // upload store logo file
            if (request('logo')) {
                $store_images_folder = 'stores/' . $store->id . '/images';
                if (!File::exists(storage_path($store_images_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }
                $thumb_img_logo = request('logo');
                $logo_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_images_folder,
                    $thumb_img_logo,
                    uniqid('store-logo-', true) . '.' . $thumb_img_logo->getClientOriginalExtension(),
                );
                $store->logo = $logo_path;
            }

            // upload store header file
            if (request('store_header')) {
                $store_images_folder = 'stores/' . $store->id . '/images';
                if (!File::exists(storage_path($store_images_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }
                $thumb_img_header = request('store_header');
                $header_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_images_folder,
                    $thumb_img_header,
                    uniqid('store-header-', true) . '.' . $thumb_img_header->getClientOriginalExtension(),
                );
                $store->store_header = $header_path;
            }

            // save store data
            $store->save();

            $store = Store::with([
                'business_type',
                'owner',
                'country',
                'bank_accounts',
            ])->findOrFail($store->id);

            return response()->json([
                "store" => StoreResource::make($store),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Store']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $store = Store::with([
                'business_type',
                'owner',
                'country',
                'bank_accounts',
            ])->findOrFail(request('store_id'));

            return new StoreResource($store);

        } catch (QueryException $e) {
            Log::error('API:StoreController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['owner']);
        if ($error) return $error;

        // check if user is authorized to use the resource
        if ($request->user()->owner_stores()->where('id', $id)->doesntExist()) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // find store
        $store = Store::findOrFail($id);

        $storeValidationRules = new StoreRequest();
        $rules = array_merge($storeValidationRules->rules($id), [
            'old_tax_id_attachment' => 'nullable|string',
            'tax_id_attachment' => 'nullable|mimes:pdf|max:1024', // up to 1 MB
            'old_commercial_registration_attachment' => 'nullable|string',
            'commercial_registration_attachment' => 'nullable|mimes:pdf|max:1024', // up to 1 MB
            'old_logo' => 'nullable|string',
            'logo' => 'nullable|required_if:old_logo,null|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
            'old_store_header' => 'nullable|string',
            'store_header' => 'nullable|required_if:old_store_header,null|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
            'info_correctness_confirmed' => 'nullable|in:yes',
            'terms_accepted' => 'nullable|in:accepted',

            'bank_accounts' => 'required|array|min:1',
            'bank_accounts.*.id' => 'nullable|string|numeric|exists:App\Models\BankAccount,id',
            'bank_accounts.*.account_holder_name' => 'required|string|min:1|max:50',
            'bank_accounts.*.iban_number' => ['required', 'string', new Iban()],
            'bank_accounts.*.old_iban_attachment' => 'nullable|string',
            'bank_accounts.*.iban_attachment' => 'nullable|mimes:pdf|max:1024', // up to 1 MB
            'bank_accounts.*.bank_name' => 'nullable|string|min:1|max:50',
            'bank_accounts.*.swift_code' => 'nullable|string|min:8|max:11',
        ]);
        $messages['bank_accounts.*.iban_number.Intervention\Validation\Rules\Iban'] =
            __('validation.custom.iban_validation', ['index' => ':index']);
        $validator = Validator::make($request->all(), $rules, $messages);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // 1. Update store details
            // $store->business_type_id = request('business_type_id');
            $store->commercial_name_en = request('commercial_name_en');
            $store->commercial_name_ar = request('commercial_name_ar');
            $store->short_name_en = request('short_name_en');
            $store->short_name_ar = request('short_name_ar');
            $store->description_ar = request('description_ar');
            $store->description_en = request('description_en');
            $store->email = request('email') ? Str::lower(request('email')) : null;
            // $store->country_id = request('country_id');
            $store->dial_code = request('contact_no') ? request('dial_code') : null;
            $store->contact_no = request('contact_no');
            $store->secondary_dial_code = request('secondary_contact_no') ? request('secondary_dial_code') : null;
            $store->secondary_contact_no = request('secondary_contact_no');
            // $store->tax_id_number = request('tax_id_number');
            // $store->commercial_registration_no = request('commercial_registration_no');
            // $store->commercial_registration_expiry = request('commercial_registration_expiry');
            // $store->municipal_license_no = request('municipal_license_no');
            // $store->api_url = request('api_url');
            // $store->api_admin_url = request('api_admin_url');
            // $store->menu_pdf = request('menu_pdf');
            $store->website = request('website');
//            $store->status = request('status', 0);
            $store->owner_id = request('owner_id', auth()->user()->id ?? null);
            $store->save();

            // 2. add store bank accounts
            if (request('bank_accounts')) {
                $bank_accounts_to_keep = [];
                foreach (request('bank_accounts') as $bank_account) {
                    // if an existing record meets both requirenments in first array, then update it, otherwise create one.
                    $bankAccount = BankAccount::updateOrCreate(
                        [
                            'store_id' => $id,
                            'id' => $bank_account['id']
                        ],
                        [
                            'account_holder_name' => $bank_account['account_holder_name'] ?? null,
                            'iban_number' => $bank_account['iban_number'] ?? null,
                            'bank_name' => $bank_account['bank_name'] ?? null,
                            'swift_code' => isset($bank_account['swift_code']) ? Str::upper($bank_account['swift_code']) : null,
                        ],
                    );
                    $bank_accounts_to_keep[] = $bankAccount->id;
                    $iban_attachment_path = $bankAccount->iban_attachment;
                    if (isset($bank_account['iban_attachment'])) { // upload bank account iban_attachment file
                        $store_iban_attachments_folder = 'stores/' . $store->id . '/attachments/iban-attachments/' . $bankAccount->id;
                        if (!File::exists(storage_path($store_iban_attachments_folder))) {
                            Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_iban_attachments_folder);
                        }
                        if (isset($bank_account['old_iban_attachment']) && $bankAccount->iban_attachment) { // delete old iban_attachment
                            if (Storage::disk(getSecondaryStorageDisk())->exists($bankAccount->iban_attachment)) {
                                Storage::disk(getSecondaryStorageDisk())->delete($bankAccount->iban_attachment);
                            }
                        }
                        $iban_attachment_file = $bank_account['iban_attachment'];
                        $iban_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                            $store_iban_attachments_folder,
                            $iban_attachment_file,
                            uniqid('iban-attachments-', true) . '.' . $iban_attachment_file->getClientOriginalExtension(),
                        );
                        $bankAccount->iban_attachment = $iban_attachment_path;
                        $bankAccount->save();
                    } else if (!isset($bank_account['old_iban_attachment']) && $bankAccount->iban_attachment) {
                        if (Storage::disk(getSecondaryStorageDisk())->exists($bankAccount->iban_attachment)) {
                            Storage::disk(getSecondaryStorageDisk())->delete($bankAccount->iban_attachment);
                        }
                        $iban_attachment_path = null;
                        $bankAccount->iban_attachment = $iban_attachment_path;
                    }
                    $bankAccount->save();
                }
                // delete any bank_account not included in bank_accounts_to_keep array
                $store->bank_accounts()->whereNotIn('id', $bank_accounts_to_keep)->delete();
            } else {
                $store->bank_accounts()->delete();
            }

            // find current logo file / upload new
            $logo_path = $store->logo;
            if (request('logo')) {
                $store_images_folder = 'stores/' . $store->id . '/images';
                if (!File::exists(storage_path($store_images_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }
                if (request('old_logo') && $store->logo) { // delete old logo
                    if (Storage::disk(getSecondaryStorageDisk())->exists($store->logo)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($store->logo);
                    }
                }
                $thumb_img_logo = request('logo');
                $logo_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_images_folder,
                    $thumb_img_logo,
                    uniqid('store-logo-', true) . '.' . $thumb_img_logo->getClientOriginalExtension(),
                );
                $store->logo = $logo_path;
            } else if (!request('old_logo') && $store->logo) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($store->logo)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($store->logo);
                }
                $logo_path = null;
                $store->logo = $logo_path;
            }

            // find current store_header file / upload new
            $header_path = $store->store_header;
            if (request('store_header')) {
                $store_images_folder = 'stores/' . $store->id . '/images';
                if (!File::exists(storage_path($store_images_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }
                if (request('old_store_header') && $store->store_header) { // delete old store_header
                    if (Storage::disk(getSecondaryStorageDisk())->exists($store->store_header)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($store->store_header);
                    }
                }
                $thumb_img_header = request('store_header');
                $header_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_images_folder,
                    $thumb_img_header,
                    uniqid('store-header-', true) . '.' . $thumb_img_header->getClientOriginalExtension(),
                );
                $store->store_header = $header_path;
            } else if (!request('old_store_header') && $store->store_header) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($store->store_header)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($store->store_header);
                }
                $header_path = null;
                $store->store_header = $header_path;
            }

            // find current tax_id_attachment file / upload new
            $tax_attachment_path = $store->tax_id_attachment;
            if (request('tax_id_attachment') && !$store->tax_id_attachment) {
                $store_attachments_folder = 'stores/' . $store->id . '/attachments';
                if (!File::exists(storage_path($store_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_attachments_folder);
                }
                if (request('old_tax_id_attachment') && $store->tax_id_attachment) { // Delete old tax_id_attachment
                    if (Storage::disk(getSecondaryStorageDisk())->exists($store->tax_id_attachment)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($store->tax_id_attachment);
                    }
                }
                $tax_id_attachment_file = request('tax_id_attachment');
                $tax_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_attachments_folder,
                    $tax_id_attachment_file,
                    uniqid('tax-id-attachments-', true) . '.' . $tax_id_attachment_file->getClientOriginalExtension(),
                );
                $store->tax_id_attachment = $tax_attachment_path;
            } else if (!request('old_tax_id_attachment') && $store->tax_id_attachment) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($store->tax_id_attachment)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($store->tax_id_attachment);
                }
                $tax_attachment_path = null;
                $store->tax_id_attachment = $tax_attachment_path;
            }

            // find current commercial_registration_attachment file / upload new
            $cr_attachment_path = $store->commercial_registration_attachment;
            if (request('commercial_registration_attachment') && !$store->commercial_registration_attachment) {
                $store_attachments_folder = 'stores/' . $store->id . '/attachments';
                if (!File::exists(storage_path($store_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_attachments_folder);
                }
                if (request('old_commercial_registration_attachment') && $store->commercial_registration_attachment) { // Delete old commercial_registration_attachment
                    if (Storage::disk(getSecondaryStorageDisk())->exists($store->commercial_registration_attachment)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($store->commercial_registration_attachment);
                    }
                }
                $cr_attachment_file = request('commercial_registration_attachment');
                $cr_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_attachments_folder,
                    $cr_attachment_file,
                    uniqid('store-cr-attachment-', true) . '.' . $cr_attachment_file->getClientOriginalExtension(),
                );
                $store->commercial_registration_attachment = $cr_attachment_path;
            } else if (!request('old_commercial_registration_attachment') && $store->commercial_registration_attachment) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($store->commercial_registration_attachment)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($store->commercial_registration_attachment);
                }
                $cr_attachment_path = null;
                $store->commercial_registration_attachment = $cr_attachment_path;
            }

            $store->save();

            $store = Store::with([
                'business_type',
                'owner',
                'country',
                'bank_accounts',
            ])->findOrFail($store->id);

            return response()->json([
                "store" => StoreResource::make($store),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Store']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $store = Store::findOrFail(request('store_id'));
            if ($store->logo and Storage::disk(getSecondaryStorageDisk())->exists($store->logo)) {
                Storage::disk(getSecondaryStorageDisk())->delete($store->logo);
            }
            if ($store->store_header and Storage::disk(getSecondaryStorageDisk())->exists($store->store_header)) {
                Storage::disk(getSecondaryStorageDisk())->delete($store->store_header);
            }
            if ($store->tax_id_attachment and Storage::disk(getSecondaryStorageDisk())->exists($store->tax_id_attachment)) {
                Storage::disk(getSecondaryStorageDisk())->delete($store->tax_id_attachment);
            }
            if ($store->commercial_registration_attachment and Storage::disk(getSecondaryStorageDisk())->exists($store->commercial_registration_attachment)) {
                Storage::disk(getSecondaryStorageDisk())->delete($store->commercial_registration_attachment);
            }

            $store->delete(); // delete the store from DB

            return response()->json([
                "store" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Store']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function addPDFMenu(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'menu_pdf' => 'required|mimes:pdf|max:2048', // up to 2 MB
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // find store
            $store = Store::findOrFail(request('store_id'));

            // find current store menu_pdf and update it / upload new
            $menu_pdf_path = $store->menu_pdf;
            if (request('menu_pdf')) {  // check for new menu_pdf
                $store_images_folder = 'stores/' . $store->id . '/products/pdf-menu';
                if (!File::exists(storage_path($store_images_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($store_images_folder);
                }
                if (request('menu_pdf') && $store->menu_pdf) { // delete old menu_pdf
                    if (Storage::disk(getSecondaryStorageDisk())->exists($store->menu_pdf)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($store->menu_pdf);
                    }
                }
                $thumb_img_menu_pdf = request('menu_pdf');
                $menu_pdf_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $store_images_folder,
                    $thumb_img_menu_pdf,
                    uniqid('menu-pdf-', true) . '.' . $thumb_img_menu_pdf->getClientOriginalExtension()
                );
            }

            // update store menu pdf field
            $store->menu_pdf = $menu_pdf_path;
            $store->save();

            $store = Store::findOrFail($store->id);

            return response()->json([
                "data" => StoreResource::make($store),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'PDF Menu']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:addPDFMenu: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function removePDFMenu(Request $request)
    {

        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // find store
            $store = Store::findOrFail(request('store_id'));

            if ($store->menu_pdf) { // delete old menu_pdf
                if (Storage::disk(getSecondaryStorageDisk())->exists($store->menu_pdf)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($store->menu_pdf);
                }
            }

            // update store menu pdf field
            $store->menu_pdf = null;
            $store->save();

            $store = Store::findOrFail($store->id);

            return response()->json([
                "data" => StoreResource::make($store),
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'PDF Menu']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:StoreController:addPDFMenu: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
