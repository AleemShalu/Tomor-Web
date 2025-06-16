<?php

namespace App\Http\Controllers\Web\Admin\Store\Branch;

use App\Http\Controllers\Controller;
use App\Models\StoreBranch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $storeId, string $branchId)
    {
        $branch = StoreBranch::with('bank_account', 'location', 'work_statuses', 'employees.roles')
            ->where('store_id', $storeId)
            ->where('id', $branchId)
            ->first();

        return view('admin.store.branch.view', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
