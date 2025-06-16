<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function users()
    {
        $users = User::with('roles', 'customer_with_special_needs')->get()->except(1);
        return view('admin.user.index', compact('users'));
    }

    public function view($id)
    {
        $user = User::with('customer_with_special_needs', 'owner_stores', 'employee_store', 'employee_orders', 'employee_branches.store', 'roles')->where('id', $id)->first();
        return view('admin.user.view', compact('user'));
    }

    public function register()
    {
        return view('admin.user.add-new-user');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email:rfc|max:50|unique:App\Models\User,email,',
            'user_type' => 'required',
            'password' => 'required|min:8',
            'hidden_contact_no' => 'required',
            'dial_code_contact_no' => 'required',
            'special_needs_description' => 'nullable|string|min:1|max:255',
            'special_needs_attachment' => 'nullable|file|mimes:pdf,doc,docx|max:1024',  // up to 1 MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->contact_no = $request->hidden_contact_no;
        $user->dial_code = $request->dial_code_contact_no;

        $user->save();

        if ($request->user_type === 'customer') {
            $user = User::findOrFail($user->id);
            $customerRole = Role::findByName('customer');
            $user->assignRole($customerRole);

            // Save special needs details for customer user
            $customer_profile = new CustomerWithSpecialNeeds();
            $customer_profile->special_needs_description = $request->special_needs_description;

            if ($request->hasFile('special_needs_attachment')) {
                $customer_attachments_folder = 'users/' . $user->id . '/customer/special-needs-attachments';
                if (!File::exists(storage_path($customer_attachments_folder))) {
                    Storage::disk(getSecondaryStorageDisk())->makeDirectory($customer_attachments_folder);
                }

                $special_needs_attachment_file = $request->file('special_needs_attachment');
                $special_needs_attachment_path = Storage::disk(getSecondaryStorageDisk())->putFileAs(
                    $customer_attachments_folder,
                    $special_needs_attachment_file,
                    $special_needs_attachment_file->getClientOriginalName()
                    . '.' . $special_needs_attachment_file->getClientOriginalExtension()
                );
                $customer_profile->special_needs_attachment = $special_needs_attachment_path;
            }

            $customer_profile->customer_id = $user->id;
            $customer_profile->special_needs_status = 'pending';
            $customer_profile->save();


        } elseif ($request->user_type === 'owner') {
            $user = User::findOrFail($user->id);
            $ownerRole = Role::findByName('owner');
            $user->assignRole($ownerRole);
        }
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }


    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.user.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        // Validate the form input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'contact_no' => 'nullable|string|max:20',
            'dial_code' => 'nullable|string|max:6',
        ]);

        // Update the user record in the database
        $user = User::findOrFail($id);
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->contact_no = $validatedData['contact_no'];
        $user->dial_code = $validatedData['dial_code'];

        $user->save();

        // Redirect the user to a relevant page after the update
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        // Fetch the user by ID and perform the deletion logic
        $user = User::findOrFail($id);
        // Perform any additional validation or checks before deleting, if necessary
        $user->delete();
        // Optionally, redirect back to a page after successful deletion
        return redirect()->route('admin.user.special-needs')->with('success', 'User deleted successfully.');
    }

    public function updateUserStatus($id, $status)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        // Update the user status based on the submitted form data
        $user->status = $status;

        // Save the updated user status
        $user->save();

        // Optionally, you can redirect back to the user listing page or perform any other actions
        return redirect()->route('admin.users')->with('success', 'User status updated successfully.');
    }
}
