<?php

namespace App\Http\Controllers\Web\Owner\Store\Branch;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Web\Owner\Controller;
use App\Models\BranchLocation;
use App\Models\BranchWorkStatus;
use App\Models\Invoice;
use App\Models\LocationConfig;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use App\Services\DhamenApiService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BranchManageController extends Controller
{

    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    public function indexBranch(StoreBranch $branch)
    {
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        try {
            // Eager load related data
            $branch->load(['orders.order_ratings', 'employees.employee_orders', 'work_statuses', 'branch_visitors']);

            // Convert start_time and end_time for each work status
            foreach ($branch->work_statuses as $workStatus) {
                $workStatus->start_time = convertLocalTimeToConfiguredTimezone($workStatus->start_time, 'UTC', 'Asia/Riyadh');
                $workStatus->end_time = convertLocalTimeToConfiguredTimezone($workStatus->end_time, 'UTC', 'Asia/Riyadh');
            }

            // Compute the top 5 employees with most orders
            $employees = $branch->employees;
            $topEmployees = $employees->sortByDesc(function ($employee) {
                return count($employee->employee_orders);
            })->take(5);

            // Return these top employees (or add them to the data you're already returning)
            return view('owner.store.branch-manage.branch', ['branch' => $branch, 'topEmployees' => $topEmployees]);
        } catch (ModelNotFoundException $e) {
            return view('owner.utility.404');
        }
    }

    public function indexOrders($id, Request $request)
    {
        $branch = StoreBranch::with('store')->where('id', $id)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));


        if (Auth::check()) {
            $userId = Auth::id();

            $validator = Validator::make(
                [
                    'id' => $id,
                ],
                [
                    'id' => ['required', 'exists:store_branches'],
                ]
            );

            if ($validator->fails()) {
                return view('owner.utility.404');
            }

            try {
                $ordersQuery = Order::with('order_ratings', 'customer')
                    ->whereIn('status', ['delivering', 'delivered', 'canceled', 'unknown'])
                    ->where('store_branch_id', $branch->id);

                // Apply filters based on form inputs if they are not null or empty
                if (!empty($request->input('order_number'))) {
                    $ordersQuery->where('order_number', 'LIKE', '%' . $request->input('order_number') . '%');
                }

                if (!empty($request->input('status'))) {
                    $ordersQuery->where('status', 'LIKE', '%' . $request->input('status') . '%');
                }

                if (!empty($request->input('branch_order_number'))) {
                    $ordersQuery->where('branch_order_number', 'LIKE', '%' . $request->input('branch_order_number') . '%');
                }

                if (!empty($request->input('branch_queue_number'))) {
                    $ordersQuery->where('branch_queue_number', 'LIKE', '%' . $request->input('branch_queue_number') . '%');
                }

                if (!empty($request->input('start_date'))) {
                    $ordersQuery->whereDate('order_date', '>=', $request->input('start_date'));
                }

                if (!empty($request->input('end_date'))) {
                    $ordersQuery->whereDate('order_date', '<=', $request->input('end_date'));
                }

                $orders = $ordersQuery->get();
                $orders_count = $orders->count();

                $invoice = Invoice::with('order')->whereIn('order_id', $orders->pluck('id'))->get();

                $totalBaseTaxable = $orders->sum('base_taxable_total');

                // Compare user ID with owner ID
                if ($userId == $branch->store->owner_id) {
                    return view('owner.store.branch-manage.branch-orders', compact('branch', 'orders_count', 'totalBaseTaxable', 'orders', 'invoice'));
                } else {
                    return view('owner.utility.404');
                }
            } catch (ModelNotFoundException $e) {
                return view('owner.utility.404');
            }
        } else {
            return view('owner.utility.404');
        }
    }

    public function indexOrdersLive($branchId, Request $request)
    {
        $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        $numIdOrder = (int)"$branchId";

        $branch = StoreBranch::find($numIdOrder);

        // Check if the branch is null
        if (!$branch) {
            // Set $orders to null
            $orders = null;
        } else {
            // Assuming you have a 'status' column in your 'orders' table
            $orders = Order::where('store_branch_id', $numIdOrder)
                ->whereIn('status', ['received', 'processing', 'delivering', 'delivered'])
                ->get();

            // Transform the orders data into a format suitable for Ag-Grid
            $orders = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'branch_order_number' => $order->branch_order_number,
                    'branch_queue_number' => $order->branch_queue_number,
                    'order_date' => $order->order_date,
                    'customer_name' => $order->customer_name,
                ];
            });
        }

        return view('owner.store.branch-manage.branch-orders-live', compact('branch', 'orders'));
    }


    public function getOrdersLive($branchId, Request $request)
    {
        $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        $orders = Order::where('store_branch_id', $branchId)
            ->whereIn('status', ['received', 'processing', 'delivering', 'delivered'])
            ->get();

        $updatedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'branch_order_number' => $order->branch_order_number,
                'branch_queue_number' => $order->branch_queue_number,
                'order_date' => $order->order_date,
                'customer_name' => $order->customer_name,
            ];
        });

        return response()->json(['orders' => $orders]);
    }

    public function indexEmployees($id)
    {
        $branch = StoreBranch::with('store')->where('id', $id)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        // Check if user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();

            $validator = Validator::make(
                [
                    'id' => $id,
                ],
                [
                    'id' => ['required', 'exists:store_branches'],
                ]
            );

            if ($validator->fails()) {
                return view('owner.utility.404');
            }

            try {
                $branch = StoreBranch::with('store')->where('id', $id)->firstOrFail();

                // Compare user ID with owner ID
                if ($userId == $branch->store->owner_id) {
                    $employees = User::with(['employee_branches', 'employee_roles'])
                        ->whereHas('employee_branches', function ($query) use ($id) {
                            $query->where('store_branch_id', $id);
                        })
                        ->where('store_id', $branch->store_id)
                        ->get()
                        ->each(function ($employee) {
                            $employee->total_hours_this_week = $employee->get_total_hours_last_week();
                        });

                    foreach ($employees as $employee) {
                        $orderCount = Order::where('employee_id', $employee->id)->count();
                        $employee->count_orders = $orderCount;
                        $isWorkingNow = $employee->is_working_now();
                        $employee->is_working_now = $isWorkingNow;
                    }

                    // Create an array of employees with their count_orders attribute
                    $employeesArray = $employees->toArray();

//                    return response()->json($employeesArray);
                    return view('owner.store.branch-manage.branch-employees', compact('branch', 'employees', 'id'));
                } else {
                    return view('owner.utility.404');
                }
            } catch (ModelNotFoundException $e) {
                return view('owner.utility.404');
            }
        } else {
            return view('owner.utility.404');
        }
    }

    public function indexSettings($id)
    {
        $config = LocationConfig::where('code', 'BR')->first();
        $branch = StoreBranch::with('store')->where('id', $id)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        // Check if user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();

            $validator = Validator::make(
                [
                    'id' => $id,
                    'timezone' => request('timezone'),
                ],
                [
                    'id' => ['required', 'exists:store_branches'],
                    'timezone' => ['required', 'string'],
                ],
            );

            if ($validator->fails()) {
                return view('owner.utility.404');
            }

            try {
                $branch = StoreBranch::with('store', 'work_statuses', 'bank_account')->where('id', $id)->firstOrFail();
                $branchLocation = StoreBranch::with('location', 'city')->where('id', $id)->firstOrFail();
                $storeId = $id;

                // Compare user ID with owner ID
                if ($userId == $branch->store->owner_id) {
                    return view('owner.store.branch-manage.branch-settings', compact('config', 'branch', 'branchLocation', 'storeId'));
                } else {
                    return view('owner.utility.404');
                }
            } catch (ModelNotFoundException $e) {
                return view('owner.utility.404');
            }
        } else {
            return view('owner.utility.404');
        }
    }

    public function updateBranchContact(Request $request)
    {
        $request->validate([
            'business-name-ar' => 'required|string|min:1|max:100',
            'business-name-en' => 'required|string|min:1|max:100',
            'contact_no' => 'required|max:9',
            'country_code' => 'required|max:3',
            'email' => 'required|email',
        ]);

        // Start the transaction
        DB::beginTransaction();

        try {
            $branchId = $request->input('branch_id');
            $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
            $this->authorize('manage', Store::findOrFail($branch->store_id));

            $businessNameAr = $request->input('business-name-ar');
            $businessNameEn = $request->input('business-name-en');
            $contact_no = $request->input('contact_no');
            $dial_code = $request->input('country_code');
            $email = $request->input('email');

            // Find the branch record by ID
            $branch = StoreBranch::findOrFail($branchId);

            $branch->name_ar = $businessNameAr;
            $branch->name_en = $businessNameEn;
            $branch->contact_no = $contact_no;
            $branch->dial_code = $dial_code;

            $branch->email = $email;
            $branch->save();

            // update Supplier
            if (app()->environment('dev')) {
                $this->dhamenApiService->updateSupplier([
                    'store_branch_id' => $branch->id,
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Branch updated successfully.');

        } catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateBranchCommercial(Request $request)
    {
        $branchId = $request->input('branch_id');
        $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        $request->validate([
            'commercial-registration-no' => 'required|numeric|digits:10',
            'commercial_registration_expiry_date' => 'required|date',
            'commercial-registration-attachment' => 'nullable|file|mimes:pdf|max:1024',
        ], [
            'commercial-registration-no.required' => 'The commercial registration number is required.',
            'commercial-registration-no.numeric' => 'The commercial registration number must be numeric.',
            'commercial-registration-no.digits' => 'The commercial registration number must be 10 digits.',
            'commercial_registration_expiry.required' => 'The commercial registration expiry date is required.',
            'commercial_registration_expiry.date' => 'Invalid date format for commercial registration expiry.',
            'commercial_registration_expiry.after' => 'The commercial registration expiry date must be after today.',
            'commercial-registration-attachment.file' => 'The commercial registration attachment must be a file.',
            'commercial-registration-attachment.mimes' => 'Invalid file type. Allowed types are: pdf.',
            'commercial-registration-attachment.max' => 'The commercial registration attachment must not exceed 1024 KB.',
        ]);

        $commercialRegistrationNo = $request->input('commercial-registration-no');
        $commercialRegistrationExpiry = $request->input('commercial_registration_expiry_date');
        $commercialRegistrationAttachment = $request->file('commercial-registration-attachment');

        // Find the branch record by ID
        $branch = StoreBranch::findOrFail($branchId);

        // Update the commercial information fields
        $branch->commercial_registration_no = $commercialRegistrationNo;
        $branch->commercial_registration_expiry = $commercialRegistrationExpiry;

//        // Handle the file upload
//        if ($commercialRegistrationAttachment) {
//            $file = $commercialRegistrationAttachment;
//            $path = $file->store('stores/' . $branch->store_id . '/branches/' . $branch->id . '/attachments');
//
//            // Delete the previous attachment, if exists
////            Storage::delete($branch->commercial_registration_attachment);
//
//            // Update the commercial registration attachment
//            $branch->commercial_registration_attachment = $path;
//        }

        // upload store commercial_registration_attachment file
        if (request('commercial-registration-attachment')) {
            $branch_attachments_folder = 'stores/' . $branch->store_id . '/branches/' . $branch->id . '/attachments';
            if (!File::exists(storage_path($branch_attachments_folder))) {
                Storage::disk(getSecondaryStorageDisk())->makeDirectory($branch_attachments_folder);
            }
            $cr_attachment_file = request('commercial-registration-attachment');
            $cr_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                $branch_attachments_folder,
                $cr_attachment_file,
                uniqid('branch-cr-attachment-', true) . '.' . $cr_attachment_file->getClientOriginalExtension(),
            );
            $branch->commercial_registration_attachment = $cr_attachment_path;
            $branch->save();
        }

        // Save the updated branch record
        $branch->save();

        return redirect()->back()->with('success', 'Commercial information updated successfully.');
    }


    public function updateWorkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|numeric|exists:store_branches,id',
            'status' => 'required|in:active,inactive',
            'start_time' => 'nullable|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'errors_update_work_status')->withInput();
        }

        $branchId = $request->input('branch_id');
        $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        $status = $request->status;
        $startTime = request('start_time');
        if ($startTime) {
            $startTime = convertLocalTimeToConfiguredTimezone($startTime, 'Asia/Riyadh', 'UTC');
        }
        $endTime = request('end_time');
        if ($endTime) {
            $endTime = convertLocalTimeToConfiguredTimezone($endTime, 'Asia/Riyadh', 'UTC');
        }

        $workStatus = BranchWorkStatus::where('store_branch_id', $branchId)->first();
        if ($workStatus) {
            $workStatus->status = $status;
            $workStatus->start_time = $startTime;
            $workStatus->end_time = $endTime;
            $workStatus->save();
        }

        return redirect()->back()->with('success_update_work_status', 'Status updated successfully.');
    }


    public function updateBranchLocation(Request $request)
    {
        $branchId = $request->input('branch_id');
        $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
        $this->authorize('manage', Store::findOrFail($branch->store_id));

        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $locationRadius = $request->location_radius;
        $district = $request->district;
        $location = $request->location;
        $city_id = $request->city_id;

        // Update the branch location in the database using the provided data
        $branch_location = BranchLocation::where('store_branch_id', $branchId)->firstOrFail();

        $branch_location->longitude = $longitude;
        $branch_location->latitude = $latitude;
        $branch_location->location_radius = $locationRadius;
        $branch_location->district = $district;
        $branch_location->google_maps_url = $location;
        $branch_location->save();

        $branch = StoreBranch::where('id', $branchId)->firstOrFail();
        $branch->city_id = $city_id;
        $branch->save();


        // Return a response indicating the success of the update
        return redirect()->back()->with('success', 'Branch location updated successfully.');

    }

    public function updateBranchBank(Request $request)
    {
        // Start the transaction
        DB::beginTransaction();

        try {
            $request->validate([
                'branch_id' => 'required|exists:store_branches,id',
                'bank_account_id' => 'required|integer',
            ]);

            $branchId = $request->input('branch_id');
            $branch = StoreBranch::with('store')->where('id', $branchId)->firstOrFail();
            $this->authorize('manage', Store::findOrFail($branch->store_id));

            $bank_account_id = $request->input('bank_account_id');

            // Update the branch bank account ID
            $branch->bank_account_id = $bank_account_id;
            $branch->save();

            // update Supplier
            if (app()->environment('dev')) {
                $this->dhamenApiService->updateSupplier([
                    'store_branch_id' => $branch->id,
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Return a response indicating the success of the update
            return redirect()->back()->with('success', 'Branch Bank updated successfully.');

        } catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function previewOrder($branchId, $orderId)
    {
        $order = Order::with('order_ratings', 'customer', 'order_items.product.images')->find($orderId);
        $order->status = OrderStatusEnum::from(mb_strtolower($order->status));
        $this->authorize('manage', Store::findOrFail($order->store_id));

        return view('owner.store.branch-manage.order-management.order-preview', compact('order'));
    }
}
