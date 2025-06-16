<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Exports\Api\BranchSalesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\BranchLocation;
use App\Models\BranchVisitor;
use App\Models\BranchWorkStatus;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use App\Services\DhamenApiService;
use App\Traits\Api\UserIsAuthorizedTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    use UserIsAuthorizedTrait;

    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        $error = $this->checkIfUserHasRightRoles($request, ['owner', 'worker_supervisor']);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $list_of_branches_belongs_to_store = StoreBranch::with([
                'city',
                'location',
                'bank_account',
                'work_statuses',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->where('store_id', request('store_id'))
                ->orderBy('created_at', 'desc')
                ->paginate(request('limit', 10));

            return BranchResource::collection($list_of_branches_belongs_to_store);

        } catch (QueryException $e) {
            Log::error('API:BranchController:index: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        $branchValidationRules = new BranchRequest();
        $validator = Validator::make($request->all(), $branchValidationRules->rules());

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }
        DB::beginTransaction();

        if (request('commercial_registration_expiry')) {
            $crExpiryToGregorian = convertHijriToGregorian(request('commercial_registration_expiry'), '-');
        }

        try {

            // create new model of branch
            # id, name_ar, name_en, branch_serial_number, qr_code,
            # commercial_registration_no, commercial_registration_expiry, commercial_registration_attachment,
            # bank_account_id, email, dial_code, contact_no, city_id, default_branch, store_id, created_at, updated_at
            $branch = new StoreBranch();
            $branch->name_ar = request('name_ar');
            $branch->name_en = request('name_en');
            // $branch->branch_serial_number = StoreBranch::generateUniqueSerialNumber();
            $branch->commercial_registration_no = request('commercial_registration_no');
            $branch->commercial_registration_expiry = isset($crExpiryToGregorian) ? $crExpiryToGregorian : null;
            $branch->bank_account_id = request('bank_account_id');
            $branch->email = request('email') ? Str::lower(request('email')) : null;
            $branch->dial_code = request('contact_no') ? request('dial_code') : null;
            $branch->contact_no = request('contact_no');
            $branch->city_id = request('city_id');
            $branch->default_branch = request('default_branch', 0);
            $branch->store_id = request('store_id');
            $branch->save();

            // Set the `default_branch` status for all other records to 0
            if (request('default_branch', 0) == 1) {
                StoreBranch::query()
                    ->where('id', '!=', $branch->id) // Exclude current row
                    ->where('store_id', $branch->store_id)
                    ->update(['default_branch' => 0]);
            }

            // create new instance of branch location
            # id, store_branch_id, location_description, location_radius, latitude, longitude,
            # district, national_address, google_maps_url, created_at, updated_at
            $branchLocation = new BranchLocation();
            $branchLocation->store_branch_id = $branch->id;
            // $branchLocation->location_description = request('location_description');
            $branchLocation->location_radius = request('location_radius');
            $branchLocation->latitude = request('latitude');
            $branchLocation->longitude = request('longitude');
            $branchLocation->district = request('district');
            // $branchLocation->national_address = request('national_address');
            $branchLocation->google_maps_url = request('google_maps_url');
            $branchLocation->save();

            // add work statuses to the branch
            if (request('work_statuses')) {
                # id, store_branch_id, status, start_time, end_time, store_id, created_at, updated_at
                foreach (request('work_statuses') as $work_status) {
                    $work_status_start_time = $work_status['start_time'] ?? null;
                    if ($work_status_start_time) {
                        $work_status_start_time = convertLocalTimeToConfiguredTimezone($work_status_start_time, request('timezone'));
                    }
                    $work_status_end_time = $work_status['end_time'] ?? null;
                    if ($work_status_end_time) {
                        $work_status_end_time = convertLocalTimeToConfiguredTimezone($work_status_end_time, request('timezone'));
                    }
                    $branchWorkStatus = new BranchWorkStatus();
                    $branchWorkStatus->store_branch_id = $branch->id;
                    $branchWorkStatus->status = $work_status['status'] ?? 'inactive';
                    $branchWorkStatus->start_time = $work_status_start_time;
                    $branchWorkStatus->end_time = $work_status_end_time;
                    $branchWorkStatus->store_id = $branch->store_id;
                    $branchWorkStatus->save();
                }
            }

            // upload store commercial_registration_attachment file
            if (request('commercial_registration_attachment')) {
                $branch_attachments_folder = 'stores/' . $branch->store_id . '/branches/' . $branch->id . '/attachments';
                if (!File::exists(storage_path($branch_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($branch_attachments_folder);
                }
                $cr_attachment_file = request('commercial_registration_attachment');
                $cr_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $branch_attachments_folder,
                    $cr_attachment_file,
                    uniqid('branch-cr-attachment-', true) . '.' . $cr_attachment_file->getClientOriginalExtension(),
                );
                $branch->commercial_registration_attachment = $cr_attachment_path;
                $branch->save();
            }

            $branch = StoreBranch::with([
                'city',
                'location',
                'bank_account',
                'work_statuses',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($branch->id);

            // create Supplier
            if (app()->environment('dev')) {
                $supplierCreated = $this->dhamenApiService->createSupplier([
                    'store_branch_id' => $branch->id,
                ]);

                if (!$supplierCreated) {
                    throw new Exception('Supplier creation failed.');
                }
            }
            DB::commit();
            return response()->json([
                "branch" => BranchResource::make($branch),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Branch']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            DB::rollback();
            Log::error('API:BranchController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('API:BranchController:store: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public
    function show(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        $error = $this->checkIfUserHasRightRoles($request, ['owner']);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            // 'store_id' => 'required|numeric|exists:App\Models\branch,id',
            'branch_id' => 'nullable|required_if:branch_serial_number,null|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            if (request('branch_serial_number')) {
                $branch = StoreBranch::with([
                    // 'branch_type',
                    'city',
                    'location',
                    'work_statuses',
                    'bank_account',
                    'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
                ])->where('branch_serial_number', request('branch_serial_number'))
                    ->firstOrFail();
            } else {
                $branch = StoreBranch::with([
                    'city',
                    'location',
                    'bank_account',
                    'work_statuses',
                    'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
                ])->findOrFail($request->branch_id);
            }

            return new BranchResource($branch);

        } catch (QueryException $e) {
            Log::error('API:BranchController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        $branchValidationRules = new BranchRequest();
        $rules = array_merge($branchValidationRules->rules($id), [
            'old_commercial_registration_attachment' => 'nullable|string',
            'commercial_registration_attachment' => 'nullable|mimes:pdf|max:1024', // up to 1 MB
            'work_statuses' => 'nullable|array|max:5',
            'work_statuses.*' => 'present|array',
            'work_statuses.*.status' => [
                'required',
                Rule::in(['active', 'busy', 'closed', 'inactive']),
            ],
            'work_statuses.*.start_time' => 'required|date_format:H:i:s',
            'work_statuses.*.end_time' => 'required|date_format:H:i:s',
        ]);
        $validator = Validator::make($request->all(), $rules);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // find the branch first
            $branch = StoreBranch::findOrFail($id);

            // update branch
            $branch->name_ar = request('name_ar');
            $branch->name_en = request('name_en');
            // $branch->branch_serial_number = StoreBranch::generateUniqueSerialNumber();
            // $branch->commercial_registration_no = request('commercial_registration_no');
            // $branch->commercial_registration_expiry = request('commercial_registration_expiry');
            $branch->bank_account_id = request('bank_account_id');
            $branch->email = request('email') ? Str::lower(request('email')) : null;
            $branch->dial_code = request('contact_no') ? request('dial_code') : null;
            $branch->contact_no = request('contact_no');
            $branch->city_id = request('city_id');
            $branch->default_branch = request('default_branch', 0);
            $branch->store_id = request('store_id');
            $branch->save();

            // Set the `default_branch` status for all other records to 0
            if (request('default_branch', 0) == 1) {
                StoreBranch::query()
                    ->where('id', '!=', $branch->id) // exclude current row
                    ->where('store_id', $branch->store_id)
                    ->update(['default_branch' => 0]);
            }

            // find current commercial_registration_attachment file / upload new
            $cr_attachment_path = $branch->commercial_registration_attachment;
            if (request('commercial_registration_attachment') && !$branch->commercial_registration_attachment) {
                $branch_cr_attachments_folder = 'stores/' . $branch->store_id . '/branches/' . $branch->id . '/attachments';
                if (!File::exists(storage_path($branch_cr_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($branch_cr_attachments_folder);
                }
                if (request('old_commercial_registration_attachment') && $branch->commercial_registration_attachment) { // Delete old commercial_registration_attachment
                    if (Storage::disk(getSecondaryStorageDisk())->exists($branch->commercial_registration_attachment)) {
                        Storage::disk(getSecondaryStorageDisk())->delete($branch->commercial_registration_attachment);
                    }
                }
                $cr_attachment_file = request('commercial_registration_attachment');
                $cr_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $branch_cr_attachments_folder,
                    $cr_attachment_file,
                    uniqid('store-cr-attachment-', true) . '.' . $cr_attachment_file->getClientOriginalExtension(),
                );
                $branch->commercial_registration_attachment = $cr_attachment_path;
                $branch->save();
            } else if (!request('old_commercial_registration_attachment') && $branch->commercial_registration_attachment) {
                if (Storage::disk(getSecondaryStorageDisk())->exists($branch->commercial_registration_attachment)) {
                    Storage::disk(getSecondaryStorageDisk())->delete($branch->commercial_registration_attachment);
                }
                $cr_attachment_path = null;
                $branch->commercial_registration_attachment = $cr_attachment_path;
                $branch->save();
            }

            $branch_location = BranchLocation::updateOrCreate(
                [
                    'store_branch_id' => $branch->id,
                ],
                [
                    'location_radius' => request('location_radius'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude'),
                    'district' => request('district'),
                    // 'national_address' => request('national_address'),
                    'google_maps_url' => request('google_maps_url'),
                ],
            );

            if (request('work_statuses')) {
                $work_statuses_to_keep = [];
                foreach (request('work_statuses') as $work_status) {
                    $work_status_start_time = $work_status['start_time'] ?? null;
                    if ($work_status_start_time) {
                        $work_status_start_time = convertLocalTimeToConfiguredTimezone($work_status_start_time, request('timezone'));
                    }
                    $work_status_end_time = $work_status['end_time'] ?? null;
                    if ($work_status_end_time) {
                        $work_status_end_time = convertLocalTimeToConfiguredTimezone($work_status_end_time, request('timezone'));
                    }
                    # id, store_branch_id, status, start_time, end_time, store_id, created_at, updated_at
                    // if an existing record meets both requirenments in first array, then update it, otherwise create one.
                    $work_status = BranchWorkStatus::updateOrCreate(
                        [
                            'id' => $work_status['id'],
                            'store_branch_id' => $id,
                            'store_id' => $branch->store_id,
                        ],
                        [
                            'status' => $work_status['status'] ?? 'inactive',
                            'start_time' => $work_status_start_time,
                            'end_time' => $work_status_end_time,
                        ],
                    );
                    $work_statuses_to_keep[] = $work_status->id;
                }
                // delete any work_status not included in work_statuses_to_keep array
                $branch->work_statuses()->whereNotIn('id', $work_statuses_to_keep)->delete();
            } else {
                $branch->work_statuses()->delete();
            }

            $branch = StoreBranch::with([
                'city',
                'location',
                'bank_account',
                'work_statuses',
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($branch->id);

            // update Supplier
            if (app()->environment('dev')) {
                $supplierCreated = $this->dhamenApiService->updateSupplier([
                    'store_branch_id' => $branch->id,
                ]);
            }

            return response()->json([
                "branch" => BranchResource::make($branch),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Branch']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BranchController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(Request $request)
    {
        $error = $this->checkIfUserIsOwnerAndBelongToStore($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $branch = StoreBranch::findOrFail(request('branch_id'));
            if ($branch->qr_code and Storage::disk(getSecondaryStorageDisk())->exists($branch->qr_code)) {
                Storage::disk(getSecondaryStorageDisk())->delete($branch->qr_code);
            }

            $branch->delete(); // delete the branch from DB

            return response()->json([
                "branch" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Branch']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BranchController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function getBranchesInCustomerArea(Request $request)
    {

//        return    Auth::guard('sanctum')->user();

//        // validate user credentials
//        $error = $this->checkIfRequestHasAuthUser($request);
//        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'radius' => 'required|numeric',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $customerLatitude = request('latitude');
        $customerLongitude = request('longitude');
        $customerRadius = request('radius');

        try {

            $branchelocationsFiltering = function ($query) use ($customerLatitude, $customerLongitude, $customerRadius) {
                $query->select('*')
                    ->selectRaw(getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude) . ' AS distance')
                    ->whereRaw(getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude) . ' <= ?', [$customerRadius]);
            };

            $branchesWithinCustomerCircularArea = StoreBranch::with([
                'location' => $branchelocationsFiltering,
                'store' => ['business_type'],
                'work_statuses',
            ])->whereHas('location', $branchelocationsFiltering)
                // ->paginate(request('limit', 10));
                ->whereHas('store', function ($query) {
                    $query->where('status', 1);
                })
                ->get();

            $branchesWithinCustomerCircularArea = $branchesWithinCustomerCircularArea->sortBy(function ($store_branch) {
                return $store_branch->location->distance;
            });

            return BranchResource::collection($branchesWithinCustomerCircularArea);

        } catch (QueryException $e) {
            Log::error('API:BranchController:branchesInCustomerArea: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function showBranchInCustomerArea(Request $request)
    {
//        // validate user credentials
//        $error = $this->checkIfRequestHasAuthUser($request);
//        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'radius' => 'required|numeric',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $customerLatitude = request('latitude');
        $customerLongitude = request('longitude');
        $customerRadius = request('radius');

        try {

            $branchelocationsFiltering = function ($query) use ($customerLatitude, $customerLongitude, $customerRadius) {
                $query->select('*')
                    ->selectRaw(getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude) . ' AS distance')
                    ->whereRaw(getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude) . ' <= ?', [$customerRadius]);
            };

            $brancheWithinCustomerCircularArea = StoreBranch::with([
                'location' => $branchelocationsFiltering,
                'work_statuses',
                'store' => ['business_type'],
            ])->whereHas('location', $branchelocationsFiltering)
                ->findOrFail(request('branch_id'));

            return new BranchResource($brancheWithinCustomerCircularArea);

        } catch (QueryException $e) {
            Log::error('API:BranchController:getBranchInCustomerArea: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function getCustomerFavoriteBranches(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $customerLatitude = request('latitude');
        $customerLongitude = request('longitude');
        $customerRadius = request('radius');

        try {

            $branchelocationsFiltering = function ($query) use ($customerLatitude, $customerLongitude, $customerRadius) {
                $query->select('*')
                    ->when(isset($customerLatitude) && isset($customerLongitude) && isset($customerRadius),
                        function ($query) use ($customerLatitude, $customerLongitude, $customerRadius) {
                            return $query->selectRaw(getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude) . ' AS distance')
                                ->selectRaw('CASE WHEN ' . getHaversineDistance('latitude', 'longitude', $customerLatitude, $customerLongitude)
                                    . ' <= location_radius + ? THEN 1 ELSE 0 END AS in_range', [$customerRadius]);
                        }
                    );
            };

            $ListOfCustomerFavoriteBranches = StoreBranch::with([
                'location' => $branchelocationsFiltering,
                'work_statuses',
                'store' => ['business_type'],
            ])->whereHas('favoured_by_customers', fn($q) => $q->where('customer_id', auth()->user()->id))
                ->paginate(request('limit', 10));

            $ListOfCustomerFavoriteBranches = $ListOfCustomerFavoriteBranches->sortBy(function ($store_branch) {
                return $store_branch->location->distance;
            });

            return BranchResource::collection($ListOfCustomerFavoriteBranches);

        } catch (QueryException $e) {
            Log::error('API:BranchController:getCustomerFavoriteBranches: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }

    }

    public function toggleCustomerFavoriteBranch(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // 1. find user model
            $user = User::findOrFail(auth()->user()->id);

            // 2. detach/attach the store branch from/to the customer's favorites
            if ($user->favorite_branches()->where('store_branch_id', request('branch_id'))->exists()) {
                $user->favorite_branches()->detach(request('branch_id'));  // detach branch if exists
                return response()->json([
                    "message" => __('locale.api.customer.favourite_branches.branch_removed_from_customer_favourites'),
                ], Response::HTTP_OK);
            } else {
                $user->favorite_branches()->attach(request('branch_id'));  // attach branch
                return response()->json([
                    "message" => __('locale.api.customer.favourite_branches.branch_added_to_customer_favourites'),
                ], Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            Log::error('API:BranchController:toggleCustomerFavoritesBranch: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function getOrdersCountsInBranch(Request $request, string $id)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // Find the branch by ID
        $branch = StoreBranch::find($id);

        if (!$branch) {
            return response()->json([
                "message" => "Branch not found.",
                "code" => "BRANCH_NOT_FOUND_ERROR"
            ], Response::HTTP_NOT_FOUND);
        }

        // Specify an array of statuses you want to filter by
        $statuses = OrderStatusEnum::only(['RECEIVED', 'PROCESSING', 'DELIVERING']);

        // Retrieve orders with the specified statuses for this branch
        $orders = $branch->orders()->whereIn('status', $statuses)->get();

        // Calculate the order counts based on your requirements
        $orderCounts = count($orders);

        return response()->json([
            "message" => "Order counts retrieved successfully.",
            "order_counts" => $orderCounts
        ], Response::HTTP_OK);
    }

    public
    function recordBranchVistor(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'user_id' => 'required|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        $branch = StoreBranch::findOrFail(request('store_branch_id'));

        try {

            $branch_visitor = new BranchVisitor();
            $branch_visitor->store_branch_id = $branch->id;
            $branch_visitor->store_id = $branch->store_id;
            $branch_visitor->user_id = request('user_id');
            $branch_visitor->save();

            return response()->json([
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'BranchVisitor']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BranchController:recordBranchVistor: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function checkBranchWorkingStatus(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        $branch = StoreBranch::findOrFail(request('store_branch_id'));

        try {

            if ($branch->branchIsWorkingNow()) {
                return response()->json([
                    "message" => __('locale.api.branches.branch_is_active'),
                    "working" => TRUE,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.branches.branch_is_closed_or_inactive'),
                    "working" => FALSE,
                ], Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            Log::error('API:BranchController:checkBranchWorkingStatus: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function updateBranchWorkStatus(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor']);
        if ($error) return $error;

        // check if the worker_supervisor user is authorized to use the resource
        $error = $this->checkIfUserBelongsToStoreAndBranch($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id', // for security purpose
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'id' => 'nullable|numeric|exists:App\Models\BranchWorkStatus,id',
            'status' => [
                'required',
                Rule::in(['active', 'busy', 'closed', 'inactive']),
            ],
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after_or_equal:start_time',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $work_status_start_time = request('start_time');
        if ($work_status_start_time) {
            $work_status_start_time = convertLocalTimeToConfiguredTimezone($work_status_start_time, request('timezone'));
        }
        $work_status_end_time = request('end_time');
        if ($work_status_end_time) {
            $work_status_end_time = convertLocalTimeToConfiguredTimezone($work_status_end_time, request('timezone'));
        }

        // update the branch work status model
        $work_status = BranchWorkStatus::updateOrCreate(
            [
                'id' => request('id'),
                'store_branch_id' => request('store_branch_id'),
                'store_id' => request('store_id'),
            ],
            [
                'status' => request('status', 'inactive'),
                'start_time' => $work_status_start_time,
                'end_time' => $work_status_end_time,
            ],
        );

        return response()->json([
            "message" => "Branch work status updated successfully!",
            "data" => $work_status,
        ], Response::HTTP_OK);
    }

    public
    function viewBranchStats(Request $request)
    {
        try {

            $branchStats = $this->getBranchStats($request);
            if ($branchStats instanceof JsonResponse) {
                return $branchStats;
            }

            return response()->json($branchStats, Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BranchController:viewBranchStats: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public
    function exportBranchStats(Request $request)
    {
        try {

            $branchStatsResult = $this->getBranchStats($request);
            if ($branchStatsResult instanceof JsonResponse) {
                return $branchStatsResult;
            }

            $local_now = now(request('timezone'));

            $store = Store::find(request('store_id'));
            $branch = StoreBranch::find(request('branch_id'));

            // create unique report title
            $report_title = 'Branch Sales Report';

            // parse start / end dates if exist
            if (request('start_date') && request('end_date')) {
                $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
                $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');
                $report_title .= ' Between ' . $start_date . ' And ' . $end_date;
            }

            $params = array(
                'report_title' => $report_title,
                'user' => auth()->user(),
                'store' => $store,
                'branch' => $branch,
                'branch_sales' => $branchStatsResult,
            );

            $file_name = request('file_name', 'branch_sales_report_' . $local_now->timestamp * 1000 . '.xlsx');
            return Excel::download(new BranchSalesExport($params), $file_name);

        } catch (QueryException $e) {
            Log::error('API:BranchController:exportBranchStats: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    private
    function getBranchStats(Request $request)
    {
        $error = $this->checkIfUserIsOwnerOrSupervisor($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id', // for security purpose
            'branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'currency_code' => 'required|string|in:SAR',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
            'file_name' => 'nullable|string',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        $branch = StoreBranch::findOrFail(request('branch_id'));

        if (request('branch_id')) {
            if ($branch->store_id != $request->store_id) {
                return response()->json([
                    "message" => __('locale.api.branches.branch_does_not_belong_to_store'),
                    "code" => "BRANCH_STORE_ERROR"
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {

            // order status set to be included in the sales.
            $salesStatus = OrderStatusEnum::only(['RECEIVED', 'PROCESSING', 'DELIVERING', 'DELIVERED']);

            $orders_by_branch = $branch->orders()->when(request('start_date') && request('end_date'), function ($query) {
                $startDate = Carbon::createFromFormat('Y-m-d', request('start_date'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', request('end_date'))->endOfDay();
                $query->whereBetween(
                    DB::raw(covertOrderDateToLocalTimeZone()),
                    [$startDate, $endDate],
                );
            })->whereIn('status', $salesStatus);

            $orders_by_branch_count = $orders_by_branch->count(); // get branch orders count
            $orders_by_branch_sales = $orders_by_branch->sum('sub_total'); // get branch salses

            // get branch orders ratings.

            $branch_ratings_total = $orders_by_branch->get()->flatMap(function ($order) {
                return $order->order_ratings;
            })->sum('rating');
            $branch_ratings_count = $orders_by_branch->get()->flatMap(function ($order) {
                return $order->order_ratings;
            })->count();
            $branch_ratings_avg = 0;
            if ($branch_ratings_count > 0) { // to avoid devide by zero
                $branch_ratings_avg = $branch_ratings_total / $branch_ratings_count;
            }

            // get branch visitors count.
            $branch_visitors_count = $branch->branch_visitors()->count();

            return collect([
                "branch_stats" => [
                    "orders_by_branch_count" => (int)$orders_by_branch_count,
                    "orders_by_branch_sales" => (float)$orders_by_branch_sales,
                    "branch_ratings_total" => (float)$branch_ratings_total,
                    "branch_ratings_count" => (int)$branch_ratings_count,
                    "branch_ratings_avg" => (float)$branch_ratings_avg,
                    "branch_visitors_count" => (int)$branch_visitors_count,
                ],
            ]);

        } catch (QueryException $e) {
            Log::error('API:BranchController:getBranchStats: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
