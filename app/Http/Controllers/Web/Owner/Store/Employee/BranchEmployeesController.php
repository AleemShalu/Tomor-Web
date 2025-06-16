<?php

namespace App\Http\Controllers\Web\Owner\Store\Employee;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Spatie\Permission\Models\Role;

class BranchEmployeesController extends Controller
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
    public function create($store_id)
    {
        $store = Store::find($store_id);
        $this->authorize('manage', $store);

        if ($store) {
            $hasStoreBranches = $store->branches()->exists();
            $positions = Role::whereIn('name', ['worker', 'worker_supervisor'])->get();
            $branches = StoreBranch::where('store_id', $store_id)->get();
            return view('owner.store.manage.employee.employee-add', compact('positions', 'store', 'hasStoreBranches', 'branches'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    protected function passwordRules()
    {
        return ['required', 'string', new Password];
    }

    public function store(Request $request)
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'role_id' => ['required'],
                'full_name' => ['required', 'min:6', 'max:60'],
                'password' => $this->passwordRules(),
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'store_branch_id' => ['required'],
                'contact_no' => ['required', 'string', 'unique:users'],
                'dial_code' => ['required', 'string'],

            ]);

            if ($validator->fails()) {
                // Rollback if validation fails
                DB::rollback();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'dial_code' => $request->dial_code,
                'password' => Hash::make($request->password),
                'store_id' => $request->store_id,
                'status' => 1,
            ]);

            $role = Role::find($request->role_id);

            // Check role and assign
            if ($role && in_array($role->name, ['worker', 'worker_supervisor'])) {
                $user->assignRole($role->name);
            }

            if ($request->store_branch_id && $request->role_id) {
                $user->employee_roles()->attach([
                    $request->role_id => ['store_branch_id' => $request->store_branch_id]
                ]);
            }

            // Commit the transaction
            DB::commit();

//            // Customize the notification data based on the updated employee data
//            $notificationData = [
//                'title' => 'Create New Account Employee',
//                'message' => "Hello {$user->name}, your employee information has been created successfully.",
//                'actionText' => 'View Details',
//            ];
//
//            // Send a notification to the user
//            $user->notify(new CustomNotification(['mobile', 'mail', 'web'], $notificationData));


            return redirect()->route('employee.manage', $request->store_id)->withSuccess('Record created successfully');

        } catch (\Exception $e) {
            // If anything goes wrong, rollback and return error
            DB::rollback();
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $employee_id)
    {
        $employee = User::with(['employee_branches', 'employee_roles', 'employee_store'])
            ->where('id', $employee_id)
            ->firstOrFail();

        $store = Store::find($employee->store_id);
        $this->authorize('manage', $store);


        $roles = Role::whereBetween('id', [3, 4])->get();
        $branches = StoreBranch::with('store')->where('store_id', $store->id)->get();

        return view('owner.store.manage.employee.employee-update', compact('branches', 'employee', 'roles', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $storeId = $request->input('store_id');
        $store = Store::find($storeId);
        $this->authorize('manage', $store);


        $employeeId = $request->input('employee_id');

        $validator = Validator::make($request->all(), [
            'role_id' => ['required'],
            'full_name' => ['required', 'min:6', 'max:60'],
            'password' => ['nullable', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->input('employee_id')),
            ],
            'store_branch_id' => ['required'],
            'contact_no' => ['required', 'string'], // Add your desired validation rules for the phone field
            'dial_code' => ['required', 'string'], // Add your desired validation rules for the phone field

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($request->input('employee_id'));
        $user->name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('contact_no');
        $user->dial_code = $request->input('dial_code');
        $user->status = $request->input('status');


        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Update branch and position here
        if ($request->input('store_branch_id') && $request->input('role_id')) {
            $user->employee_roles()->sync([
                $request->input('role_id') => ['store_branch_id' => $request->input('store_branch_id')]
            ]);
        }

        $user->roles()->detach(); // Clear existing roles

        $roleName = ''; // Initialize the role name variable

        $role = Role::find($request->input('role_id'));

        if ($role->name_en === 'worker') {
            $roleName = 'worker';
        } elseif ($role->name_en === 'supervisor') {
            $roleName = 'supervisor';
        }

        if ($roleName !== '') {
            $user->assignRole($roleName);
        }

        // Update roles
        $user->syncRoles([]); // Clear existing roles

        $roleName = ''; // Initialize the role name variable

        $role = Role::where('name_en', $role->name_en)->first();

        if ($role) {
            $roleName = $role->name;
            $user->syncRoles([$roleName]); // Assign the new role
        }
        // Customize the notification data based on the updated employee data
        $notificationData = [
            'title' => 'Employee Updated',
            'message' => "Hello {$user->name}, your employee information has been updated successfully.",
            'actionText' => 'View Details',
        ];

        // Send a notification to the user
//        $user->notify(new CustomNotification(['mobile', 'mail', 'web'], $notificationData));

        return redirect()->route('employee.edit', [
            'storeId' => $storeId,
            'employeeId' => $employeeId
        ])->withSuccess('User record updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('employee.manage', $request->store_id)->withSuccess('User and Branch Employee deleted successfully');
    }

    public function previewEmployee($storeId, $employeeId)
    {

        $employee = User::with(['employee_branches', 'employee_roles', 'employee_store'])
            ->where('id', $employeeId)
            ->firstOrFail();

        $store = Store::find($employee->store_id);
        $this->authorize('manage', $store);

        return view('owner.store.manage.employee.employee-preview', compact('employee'));

    }
}
