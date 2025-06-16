<?php

namespace App\Http\Controllers\Web\Owner;

use App\Http\Controllers\Controller;
use App\Models\BranchVisitor;
use App\Models\Order;
use App\Models\OrderRating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $ownerId = Auth::id();


        $visitors = BranchVisitor::with('store')
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            });

        $visitorsCount = $visitors->count();

        //Data for card_1
        $earningsData = Order::with('store')
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->whereIn('status', ['delivering', 'delivered'])
            ->sum('grand_total');

        //Data for card_1
        $ordersCompletedData = Order::with('store')
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->whereIn('status', ['delivering', 'delivered'])
            ->count();


        // Data for chart_1
        $chart1Data = Order::select(DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'), DB::raw('SUM(grand_total) as total'))
            ->whereIn('status', ['delivering', 'delivered'])
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->groupBy('month')
            ->orderBy('month', 'asc') // Add this line to sort by 'month' in ascending order
            ->get();

        // Data for chart_2 (count of orders per month)
        $chart2Data = Order::select(DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'), DB::raw('COUNT(*) as order_count'))
            ->whereIn('status', ['delivering', 'delivered'])
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->groupBy('month')
            ->orderBy('month', 'asc') // Add this line to sort by 'month' in ascending order
            ->get();

        // Data for chart_3
        $RatingData = OrderRating::with('store')
            ->whereHas('store', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->select('rating');

        $chart3Data = $RatingData->get();
        $ratingAvgData = number_format($RatingData->avg('rating'), 1);


        // Data for chart_5
        $chart5Data = User::with('owner_stores.employees.employee_roles')
            ->where('id', $ownerId)
            ->get();


        return view('owner/dashboard/dashboard', compact('chart1Data', 'chart2Data', 'chart3Data', 'chart5Data', 'visitorsCount', 'earningsData', 'ordersCompletedData', 'ratingAvgData'));
    }
}
