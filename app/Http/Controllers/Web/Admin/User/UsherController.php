<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Usher;
use App\Models\UsherClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsherController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ushers = Usher::all();
        $storeOwners = UsherClient::with('user')->get()->pluck('user'); // adjust this logic based on your relationships and needs


//        return response()->json($storeOwners);
        return view('admin.user.usher.index', compact('ushers', 'storeOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.usher.create'); // assuming you have a Blade view for creating an usher
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:ushers',
            'phone_number' => 'nullable|string|max:20',
            'code_usher' => 'required|string|max:255|unique:ushers,code_usher'
        ]);

        // Store the usher in the database
        $usher = Usher::create($validated);

        // Redirect with a success message
        return redirect()->route('admin.usher')->with('success', 'Usher created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Usher $usher)
    {
        // Return a view to show details of the specified Usher
        return view('admin.user.usher.show', compact('usher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usher $usher)
    {
        // Return a view with a form to edit the specified Usher
        return view('admin.user.usher.edit', compact('usher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $usherId = $request->input('usher_id');
        $usherCode = $request->input('code_usher');
        $states = $request->input('states');

        $usher = Usher::find($usherId);

        // Validate the request data
        $request->validate([
            'states' => 'required|in:0,1',
            'code_usher' => [
                'required',
                'string',
                'max:255',
                'unique:ushers,code_usher,' . $usher->id,
            ],
        ]);

        // Update the usher in the database
        DB::table('ushers')
            ->where('id', $usherId)
            ->update(['states' => $states, 'code_usher' => $usherCode]);

        // Redirect with a success message
        return redirect()->route('admin.usher.edit', ['usher' => $usher->id])->with('success', __('admin.user_management.users.update_success'));
    }



    /**
     * Remove the specified resource from storage.
     */
//    public function destroy(Request $request, $id)
//    {
//        $usher = Usher::find($id);
//
//        // Delete the specified Usher
//        $usher->delete();
//
//        // Redirect with a success message
//        return redirect()->route('admin.usher')->with('success', 'Usher deleted successfully!');
//    }
}
