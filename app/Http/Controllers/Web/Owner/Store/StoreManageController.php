<?php

namespace App\Http\Controllers\Web\Owner\Store;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use Illuminate\Http\Request;

class StoreManageController extends Controller
{

    // Automatically apply authorization checks for CRUD operations
    public function __construct()
    {
        $this->authorizeResource(Store::class, 'store');
    }


    public function indexStore($id)
    {
        $store = Store::with(
            'country',
            'employees.employee_branches',
            'employees.employee_roles',
            'branches.orders.order_ratings',
            'branches.branch_visitors',
            'bank_accounts',
            'order_ratings',
            'branch_visitors'
        )->find($id);
        $this->authorize('manage', $store);

        return view('owner.store.manage.manage', compact('store'));
    }

    public function indexBranch($id)
    {
        $branches = StoreBranch::with('city', 'work_statuses')->where('store_id', $id)->get();

        // Format the work statuses' start_time and end_time using Carbon in 12-hour format with AM/PM
        foreach ($branches as $branch) {
            foreach ($branch->work_statuses as $workStatus) {
                $workStatus->start_time = convertLocalTimeToConfiguredTimezone($workStatus->start_time, 'UTC', 'Asia/Riyadh');
                $workStatus->end_time = convertLocalTimeToConfiguredTimezone($workStatus->end_time, 'UTC', 'Asia/Riyadh');
            }
        }
        $store = Store::with('country')->find($id);
        $this->authorize('manage', $store);

//        return response()->json($branches);

        return view('owner.store.manage.branch', compact('store', 'branches'));
    }

    public function indexEmployees($id)
    {
        $store = Store::with('country')->find($id);
        $this->authorize('manage', $store);

        // Assuming 'employee' is the name of the role for employees
        $employees = User::with(['employee_branches', 'employee_roles'])
            ->where('store_id', $id)
            ->get();

        return view('owner.store.manage.employees', compact('store', 'employees'));
    }

    public function indexProducts($id)
    {
        $store = Store::with('country')->find($id);
        $products = Product::with('product_brand', 'translations', 'images', 'product_category')->where('store_id', $id)->get();
        // Authorize the user to manage the store
        $this->authorize('manage', $store);

//        return response()->json($products);
        return view('owner.store.manage.product', compact('store', 'products'));
    }

    public function indexSettings($id)
    {
        $store = Store::with('country')->find($id);

        // Authorize the user to manage the store
        $this->authorize('manage', $store);

        $countries = Country::all();
        $banks = BankAccount::all()->where('store_id', $id);

        return view('owner.store.manage.settings', compact('store', 'countries', 'banks'));
    }

    /**
     * Display a listing of the resource.
     */
    public function create()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
