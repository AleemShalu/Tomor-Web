<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Store;
use App\Models\WorkDay;
use App\Models\BreakTime;
use App\Models\StoreBranch;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Exports\Api\EmployeesExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\EmployeeResource;
use App\Traits\Api\UserIsAuthorizedTrait;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class EmployeeController extends Controller
{
    use UserIsAuthorizedTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $error = $this->checkIfUserIsOwnerOrSupervisor($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $userId = $request->user()->id;

            $filterEmployeesByBranch = function ($q) use ($userId) {
                return $q->whereNot('id', $userId)
                    ->whereHas('employee_branches', function ($q) {
                        $q->where('id', request('store_branch_id'));
                    });
            };

            $list_of_employees = User::with([
                // 'employee_roles',
                'roles',
                'employee_store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar',
                'employee_branches:id,name_ar,name_en,branch_serial_number,store_id',
            ])->role(['worker_supervisor', 'worker'])
                ->where('store_id', request('store_id'))
                ->when(request('store_branch_id'), $filterEmployeesByBranch)
                ->orderBy('id', 'asc')
                ->paginate(request('limit', 10));

            return EmployeeResource::collection($list_of_employees);

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:index: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $error = $this->checkIfUserIsOwnerOrSupervisor($request);
        if ($error) return $error;

        // Validate request fields
        $employeeValidationRules = new EmployeeRequest();
        $validator = Validator::make($request->all(), $employeeValidationRules->rules());

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        // return response()->json([
        //     "message" => "Test Response",
        //     "code" => "TEST_CODE"
        // ], Response::HTTP_BAD_REQUEST);

        try {

            // create new user employee model
            $user = User::create([
                'name' => request('name'),
                'email' => request('email') ? Str::lower(request('email')) : null,
                'dial_code' => request('contact_no') ? request('dial_code') : null,
                'contact_no' => request('contact_no'),
                'password' => Hash::make(request('password')),
                'store_id' => request('store_id'),
                'status' => request('status', 0),
            ]);

            // assign role the employee
            if (request('role_id')) {
                $user_role = Role::find(request('role_id'));
                // check role and assign
                if ($user_role && in_array($user_role->name, ['worker_supervisor', 'worker'])) {
                    $user->assignRole($user_role);
                }
            }

            // assign employee the the branch
            if (request('store_branch_id') && request('role_id')) {
                $user->employee_branches()->attach([
                    request('store_branch_id') => ['role_id' => request('role_id')]
                ]);
            }

            // fetch the created user with its related models
            $user = User::with([
                // 'employee_roles',
                'roles',
                'employee_store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar',
                'employee_branches:id,name_ar,name_en,branch_serial_number,store_id',
            ])->findOrFail($user->id);

            return response()->json([
                "user" => EmployeeResource::make($user),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Employee']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:store: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['owner', 'worker_supervisor', 'worker']);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|numeric|exists:App\Models\User,id',
            'store_id' => 'nullable|numeric|exists:App\Models\Store,id',
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $employee = User::with([
                // 'employee_roles',
                'roles',
                'employee_store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar',
                'employee_branches:id,name_ar,name_en,branch_serial_number,email,dial_code,contact_no,default_branch,store_id',
                'employee_branches' => ['work_statuses'],
            ])->role(['worker_supervisor', 'worker'])
                ->when(request('store_id'), function ($query) {
                    return $query->whereHas('employee_store', function ($query) {
                        $query->where('id', request('store_id'));
                    });
            })->findOrFail(request('employee_id'));

            return new EmployeeResource($employee);

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $error = $this->checkIfUserIsOwnerOrSupervisor($request);
        if ($error) return $error;

        // validate request fields
        $employeeValidationRules = new EmployeeRequest();
        $rules = array_merge($employeeValidationRules->rules($id), [
            'password' => ['nullable'],
        ]);
        $validator = Validator::make($request->all(), $rules);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        // return response()->json([
        //     "message" => "Test Response",
        //     "code" => "TEST_CODE"
        // ], Response::HTTP_BAD_REQUEST);

        try {

            $user = User::findOrFail($id);

            // create new user employee model
            $user->update([
                'name' => request('name'),
                'email' => request('email') ? Str::lower(request('email')) : null,
                'dial_code' => request('contact_no') ? request('dial_code') : null,
                'contact_no' => request('contact_no'),
                // 'password' => Hash::make(request('password')),
                // 'store_id' => request('store_id'),
                'status' => request('status', 0),
            ]);

            // assign role the employee
            if (request('role_id')) {
                $user_role = Role::find(request('role_id'));
                // check role and assign
                if ($user_role && in_array($user_role->name, ['worker_supervisor', 'worker'])) {
                    $user->syncRoles($user_role);
                }
            }

            // assign employee the the branch
            if (request('store_branch_id') && request('role_id')) {
                $user->employee_branches()->sync([
                    request('store_branch_id') => ['role_id' => request('role_id')]
                ]);
            }

            // fetch the created user with its related models
            $user = User::with([
                // 'employee_roles',
                'roles',
                'employee_store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar',
                'employee_branches:id,name_ar,name_en,branch_serial_number,store_id',
            ])->findOrFail($user->id);

            return response()->json([
                "user" => EmployeeResource::make($user),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Employee']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:update: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportExcel(Request $request)
    {
        $error = $this->checkIfUserIsOwnerOrSupervisor($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'file_name' => 'nullable|string',
        ]);

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $userId = $request->user()->id;

            $store = Store::find(request('store_id'));
            $branch = StoreBranch::find(request('store_branch_id'));

            $filterEmployeesByBranch = function ($q) use ($userId) {
                return $q->whereNot('id', $userId)
                    ->whereHas('employee_branches', function ($q) {
                        $q->where('id', request('store_branch_id'));
                    });
            };

            $list_of_employees = User::with([
                // 'employee_roles',
                'roles',
                'employee_store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar',
                'employee_branches:id,name_ar,name_en,branch_serial_number,store_id',
            ])->role(['worker_supervisor', 'worker'])
                ->where('store_id', request('store_id'))
                ->when(isset($branch), $filterEmployeesByBranch)
                ->orderBy('id', 'asc')
                ->get();

            // create unique report title
            $report_title = 'Employees Report';
            if (request('duration')) {
                $local_now = now(request('timezone'));
                $month_name = $local_now->format('F');
                $today = $local_now->format('Y-m-d');
                $year = $local_now->format('Y');
                $duration = ' ('. ucwords(request('duration')) . ')';
                switch (request('duration')) {
                    case 'daily':
                        $report_title .= ' For ' . $today . $duration;
                        break;
                    case 'weekly':
                        $first_date = $local_now->copy()->subWeek()->format('Y-m-d');
                        $report_title .= ' Between ' . $first_date . ' And ' .  $today . $duration;
                        break;
                    case 'monthly':
                        $report_title .= ' For ' . $month_name . ' ' .  $year . $duration;
                        break;
                    case 'yearly':
                        $report_title .= ' For ' . $year . $duration;
                        break;
                    default:
                        break;
                }
            }

            $params =  array(
                'user' => auth()->user(),
                'store' => $store,
                'branch' => $branch,
                'report_title' => $report_title,
                'duration' => request('duration'), // do not use $$duration as it's changed.
                'employees' => $list_of_employees,
            );

            $file_name = request('file_name', 'branch_employees_report_' . $local_now->timestamp * 1000 . '.xlsx');
            return Excel::download(new EmployeesExport($params), $file_name);

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:exportExcel: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function startWorkDay(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // check if the worker_supervisor / worker user is authorized to use the resource
        $error = $this->checkIfUserBelongsToBranch($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|numeric|exists:App\Models\User,id',
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        // check if the employee located in the branch circular area
        $checkIfEmployeeInBranchArea = $this->checkIfEmployeeInBranchArea($request);
        if ($checkIfEmployeeInBranchArea instanceof JsonResponse) {
            return $checkIfEmployeeInBranchArea;
        }

        try {

            $employee = User::find($request->input('employee_id'));

            // check if the employee has any open workday for today
            $openWorkDay = WorkDay::where('employee_id', $employee->id)
                ->where('work_date', now()->toDateString())
                ->whereNull('end_time')
                ->first();

            if ($openWorkDay) {
                // Update the start time of the open workday
                $openWorkDay->start_time = now()->toTimeString();
                $openWorkDay->save();

                return response()->json([
                    "message" => 'The employee\'s workday has started again.'
                ], Response::HTTP_OK);

            } else {
                // Create a new record in the work_days table for the employee
                $workDay = new WorkDay([
                    'employee_id' => $employee->id,
                    'work_date' => now()->toDateString(),
                    'start_time' => now()->toTimeString(),
                ]);
                $workDay->save();

                return response()->json([
                    "message" => 'The employee\'s workday has started.'
                ], Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:startWorkDay: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function endWorkDay(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // check if the worker_supervisor / worker user is authorized to use the resource
        $error = $this->checkIfUserBelongsToBranch($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|numeric|exists:App\Models\User,id',
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        // // check if the employee located in the branch circular area
        // $checkIfEmployeeInBranchArea = $this->checkIfEmployeeInBranchArea($request);
        // if ($checkIfEmployeeInBranchArea instanceof JsonResponse) {
        //     return $checkIfEmployeeInBranchArea;
        // }

        try {

            $employee = User::find($request->input('employee_id'));

            // Find the latest workday record for the current date and employee
            $latestWorkDay = WorkDay::where('employee_id', $employee->id)
                ->where('work_date', now()->toDateString())
                ->latest('id')
                ->first();

            if ($latestWorkDay) {
                // Check if the end time is not set
                if ($latestWorkDay->end_time === null) {
                    // Update the end time of the workday session
                    $latestWorkDay->end_time = now()->toTimeString();
                    $latestWorkDay->save();

                    return response()->json([
                        "message" => 'The employee\'s workday session has ended.'
                    ], Response::HTTP_OK);

                } else {
                    return response()->json([
                        "message" => 'The employee\'s workday session has already ended.'
                    ], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return response()->json([
                    "message" => 'The employee has not started their workday session today.'
                ], Response::HTTP_BAD_REQUEST);
            }

        } catch (QueryException $e) {
            Log::error('API:EmployeeController:endWorkDay: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkIsWorkingNow(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // Validate user credentials and request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $employee = User::find($request->input('employee_id'));

        if ($employee->is_working_now()) {
            return response()->json([
                "message" => 'The employee is currently working.',
                'is_working' => TRUE,
            ], Response::HTTP_OK);
        }

        return response()->json([
            "message" => 'The employee is not working right now.',
            'is_working' => FALSE,
        ], Response::HTTP_OK);
    }

    public function startBreak(Request $request)
    {
        // Validate user credentials and request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $employee = User::find($request->input('employee_id'));

        // Check if the employee has started their workday today
        if ($employee->has_started_work_today()) {
            // Check if the employee has ended their workday today
            if (!$employee->has_ended_work_today()) {
                // Check if there is an active break for the current workday
                $activeBreak = BreakTime::where('work_day_id', $employee->get_current_work_day()->id)
                    ->whereNull('break_end_time')
                    ->first();

                if (!$activeBreak) {
                    // Create a new record in the break_times table to start the break
                    $breakTime = new BreakTime([
                        'work_day_id' => $employee->get_current_work_day()->id,
                        'break_start_time' => now()->toTimeString(),
                    ]);
                    $breakTime->save();

                    return response()->json([
                        "message" => 'The employee\'s break has started.'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        "message" => 'The employee has already started a break.'
                    ], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return response()->json([
                    "message" => 'The employee has already ended their workday today.'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return response()->json([
            "message" => 'The employee has not started their workday today.'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function endBreak(Request $request)
    {
        // Validate user credentials and request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $employee = User::find($request->input('employee_id'));

        // Check if the employee has started their workday today
        if ($employee->has_started_work_today()) {
            // Find the active break record for the current workday
            $breakTime = BreakTime::where('work_day_id', $employee->get_current_work_day()->id)
                ->whereNull('break_end_time')
                ->first();

            if ($breakTime) {
                // Update the end time of the break
                $breakTime->break_end_time = now()->toTimeString();
                $breakTime->save();

                return response()->json([
                    "message" => 'The employee\'s break has ended.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => 'The employee has no active break to end.'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return response()->json([
            "message" => 'The employee has not started their workday today.'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function checkIsOnBreakNow(Request $request)
    {
        // Validate user credentials and request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $employee = User::find($request->input('employee_id'));

        // Check if the employee has started their workday today
        if ($employee->has_started_work_today()) {
            // Check if the employee has ended their workday today
            if (!$employee->has_ended_work_today()) {
                // Check if there is an active break for the current workday
                $activeBreak = BreakTime::where('work_day_id', $employee->get_current_work_day()->id)
                    ->whereNull('break_end_time')
                    ->first();

                if ($activeBreak) {
                    return response()->json([
                        "message" => 'The employee is currently on a break.',
                        'is_on_break' => true
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        "message" => 'The employee is not on a break right now.',
                        'is_on_break' => false
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    "message" => 'The employee has already ended their workday today.',
                    'is_on_break' => false
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return response()->json([
            "message" => 'The employee has not started their workday today.',
            'is_on_break' => false
        ], Response::HTTP_BAD_REQUEST);
    }

    public function checkIfEmployeeInBranchArea(Request $request)
    {
        $userLatitude = request('latitude');
        $userLongitude = request('longitude');

        try {

            $branchLocationFiltering = function ($query) use ($userLatitude, $userLongitude) {
                $query->select('*')
                    ->selectRaw(getHaversineDistance('latitude', 'longitude', $userLatitude, $userLongitude) . ' AS distance')
                    ->whereRaw(getHaversineDistance('latitude', 'longitude', $userLatitude, $userLongitude) . ' <= location_radius');
            };

            $branch = StoreBranch::with([
                'location' => $branchLocationFiltering,
            ])->whereHas('location', $branchLocationFiltering)
                ->where('id', request('store_branch_id'))->exists();

            if (!$branch) {
                return response()->json([
                    "message" => __('locale.api.branches.user_is_outside_branch_area'),
                    "code" => "BRANCH_LOCATION_AREA_ERROR"
                ], Response::HTTP_BAD_REQUEST);
            }

            return $branch;
        } catch (QueryException $e) {
            Log::error('API:EmployeeController:checkIfEmployeeInBranchArea: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
