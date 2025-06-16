<?php

namespace App\Http\Controllers\Web\Admin\User\SpecialNeeds;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\SpecialNeedsCollection;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SpecialNeedsController extends Controller
{

    public function usersSpecialNeeds()
    {

        $users = User::whereHas('customer_with_special_needs')->with('customer_with_special_needs.special_needs_type')->get();

//        return response()->json($users);
        return view('admin.user.special-needs.special-needs', compact('users'));
    }

    /**
     * @throws \Exception
     */
    public function usersSpecialNeedsAjax(Request $request)
    {
        if ($request->ajax()) {
            $usersQuery = User::whereHas('customer')->with('customer');

            // Use the SpecialNeedsCollection resource to format the data
            $users = new SpecialNeedsCollection($usersQuery->get());

            return Datatables::of($users->toArray($request))->make(true);
        } else {
            abort(403);
        }
    }

    public function view($id)
    {
        // Get the user with the associated special needs
        $user = User::with('customer_with_special_needs')->findOrFail($id);

        // Return the view to display the special needs information for the user, passing the formatted user data
        return view('admin.user.special-needs.view', compact('user'));
    }

    public function edit($id)
    {
        // Get the user with the associated special needs
        $user = User::with('customer_with_special_needs')->findOrFail($id);

        if ($user->customer && $user->customer->special_needs_status === 'pending') {
            // Update the special needs information for the user
            $user->customer->update([
                'special_needs_status' => 'reviewing',
                'special_needs_qualified' => '0',
            ]);


            // Return the view, passing the transformed user data as 'user'
            return view('admin.user.special-needs.edit', compact('user'));
        } else {
            return view('admin.user.special-needs.edit', compact('user'));
        }
    }

    public function update(Request $request)
    {
        // Get the user with the associated special needs, and eager load the 'customer' relationship
        $user = User::with('customer_with_special_needs')->findOrFail($request->input('user_id'));

        // Check if the user has a related customer record
        if ($user->customer_with_special_needs) {
            // Update the special needs information for the user
            $user->customer_with_special_needs->update([
                'special_needs_qualified' => $request->input('customer_qualified'),
                'special_needs_status' => $request->input('customer_qualified') == 1 ? 'approved' : 'rejected'

            ]);

            // Redirect back to the edit page with a success message
            return redirect()->route('admin.users.special-needs.edit', ['id' => $user->id])->with('success', 'Special needs information updated successfully.');
        } else {
            // Handle the case where the user does not have a related customer record
            return redirect()->route('admin.users.special-needs.edit', ['id' => $user->id])->with('error', 'User does not have a customer record.');
        }
    }
}
