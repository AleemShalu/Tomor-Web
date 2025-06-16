<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Order;
use App\Models\PlatformRating;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function dashboard()
    {
        // Users: Total & Growth Percentage
        $totalUsers = User::count();
        $usersThisWeek = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $usersLastWeek = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
        $userPercent = $usersLastWeek == 0 ? ($usersThisWeek * 100) : round((($usersThisWeek - $usersLastWeek) / $usersLastWeek) * 100);

        // Stores: Total Count
        $totalStores = Store::count();

        // Service Cost: Current & Growth Percentage
        $serviceCostThisWeek = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('service_total');
        $serviceCostLastWeek = Order::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->sum('service_total');
        $earningsPercent = $serviceCostLastWeek == 0 ? (($serviceCostThisWeek != 0) ? 100 : 0) : round((($serviceCostThisWeek - $serviceCostLastWeek) / $serviceCostLastWeek) * 100);

        // Satisfaction: Percentage of users with 3+ stars
        $totalRatings = PlatformRating::count();
        $satisfiedRatingsCount = PlatformRating::where('rating', '>=', 3)->count();
        $satisfiedUsersPercentage = $totalRatings != 0 ? round(($satisfiedRatingsCount / $totalRatings) * 100) : 0;

        return view('admin.dashboard', compact(
            'totalUsers',
            'userPercent',
            'totalStores',
            'serviceCostThisWeek', // Changing the variable name to make it clear that this is for the current week
            'earningsPercent',
            'satisfiedUsersPercentage'
        ));
    }


    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, User $user)
    {
        $user->update($request->all());
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
