<?php

namespace App\Http\Controllers\Web\Owner\Store\Branch;

use App\Http\Controllers\Web\Owner\Controller;
use App\Http\Requests\Web\StoreBranchRequest;
use App\Models\BankAccount;
use App\Models\BranchLocation;
use App\Models\BranchWorkStatus;
use App\Models\City;
use App\Models\LocationConfig;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Services\DhamenApiService;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBranchController extends Controller
{
    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($storeId)
    {
        $config = LocationConfig::where('code', 'BR')->first();
        $cities = City::all();
        $store = Store::find($storeId);
        $this->authorize('manage', $store);

        $bankAccounts = BankAccount::where('store_id', $storeId)->get();

        return view('owner.branch.create', compact('config', 'cities', 'storeId', 'store', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        DB::beginTransaction(); // Start transaction

        try {
            $randomNumber = sprintf("%06d", rand(0, 999999));

            $branch = StoreBranch::create([
                'store_id' => $request->store_id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'commercial_registration_no' => $request->commercial_registration_no,
                'commercial_registration_expiry' => $request->commercial_registration_expiry,
                'email' => $request->email,
                'dial_code' => $request->dial_code,
                'contact_no' => $request->contact_no,
                'city_id' => $request->city_id,
                'branch_serial_number' => $randomNumber,
                'bank_account_id' => $request->bank_account_id,
            ]);

            // Handle the file upload
            if ($request->hasFile('commercial_registration_attachment')) {
                $path = $request->file('commercial_registration_attachment')
                    ->store('stores/' . $branch->store_id . '/branches/' . $branch->id . '/attachments');
                $branch->update(['commercial_registration_attachment' => $path]);
            }

            // Create Branch Location
            BranchLocation::create([
                'store_branch_id' => $branch->id,
                'location_description' => $request->location_description,
                'google_maps_url' => $request->location,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'district' => $request->district,
                'location_radius' => $request->location_radius,
            ]);

            // Create Branch Work Status
            BranchWorkStatus::create([
                'store_branch_id' => $branch->id,
                'status' => 'active',
                'start_time' => '21:00:01',
                'end_time' => '20:50:01',
                'store_id' => $branch->store_id,
            ]);

            // create Supplier
            if (app()->environment('dev')) {
                $supplierCreated = $this->dhamenApiService->createSupplier([
                    'store_branch_id' => $branch->id,
                ]);
            }

            DB::commit(); // Commit transaction

            return redirect()->route('branch.manage', $request->store_id)
                ->with('success', 'Store created successfully.');

        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
