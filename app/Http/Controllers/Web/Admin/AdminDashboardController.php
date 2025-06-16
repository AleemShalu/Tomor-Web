<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Owner\Controller;
use App\Models\Order;
use App\Models\PlatformRating;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    public function getUserGrowth(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all'); // Default to 'all' if not specified

        $labels = [];
        $dataPoints = [];

        switch ($timeframe) {
            case 'week':
                // Fetch data for the past week
                $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $startOfWeek = Carbon::now()->startOfWeek();
                $dataPoints = [];
                for ($i = 0; $i < 7; $i++) {
                    $count = User::whereDate('created_at', '=', $startOfWeek->copy()->addDays($i))->count();
                    array_push($dataPoints, $count);
                }
                break;

            case 'month':
                // Fetch data for the past month
                $labels = [];
                $dataPoints = [];
                for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
                    $labels[] = "Day $i";
                    $count = User::whereDate('created_at', '=', Carbon::now()->startOfMonth()->addDays($i - 1))->count();
                    array_push($dataPoints, $count);
                }
                break;

            case '3months':
                // Fetch data for the past 3 months
                $labels = ['Month 1', 'Month 2', 'Month 3'];
                for ($i = 2; $i >= 0; $i--) {
                    $count = User::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)->count();
                    array_push($dataPoints, $count);
                }
                break;

            case '6months':
                // Fetch data for the past 6 months
                $labels = [];
                $dataPoints = [];
                for ($i = 5; $i >= 0; $i--) {
                    $monthName = Carbon::now()->subMonths($i)->format('F');
                    $labels[] = $monthName;
                    $count = User::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)->count();
                    array_push($dataPoints, $count);
                }
                break;

            default:
                // Handle 'all' or any other cases
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $dataPoints = [];
                for ($i = 1; $i <= 12; $i++) {
                    $count = User::whereMonth('created_at', '=', $i)->count();
                    array_push($dataPoints, $count);
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataPoints,
                    'borderColor' => '#4F46E5', // Color for the line
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)', // Color for the area under the line
                ]
            ]
        ]);
    }

    public function getStoreGrowth(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all');

        $labels = [];
        $dataPoints = [];

        switch ($timeframe) {
            case 'week':
                // Fetch data for the past week
                $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $startOfWeek = Carbon::now()->startOfWeek();
                $dataPoints = [];
                for ($i = 0; $i < 7; $i++) {
                    $count = Store::whereDate('created_at', '=', $startOfWeek->copy()->addDays($i))->count();
                    array_push($dataPoints, $count);
                }
                break;

            case 'month':
                // Fetch data for the past month
                $labels = [];
                $dataPoints = [];
                for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
                    $labels[] = "Day $i";
                    $count = Store::whereDate('created_at', '=', Carbon::now()->startOfMonth()->addDays($i - 1))->count();
                    array_push($dataPoints, $count);
                }
                break;

            case '3months':
                // Fetch data for the past 3 months
                $labels = ['Month 1', 'Month 2', 'Month 3'];
                for ($i = 2; $i >= 0; $i--) {
                    $count = Store::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)->count();
                    array_push($dataPoints, $count);
                }
                break;

            case '6months':
                // Fetch data for the past 6 months
                $labels = [];
                $dataPoints = [];
                for ($i = 5; $i >= 0; $i--) {
                    $monthName = Carbon::now()->subMonths($i)->format('F');
                    $labels[] = $monthName;
                    $count = Store::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)->count();
                    array_push($dataPoints, $count);
                }
                break;

            default:
                // Handle 'all' or any other cases
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $dataPoints = [];
                for ($i = 1; $i <= 12; $i++) {
                    $count = Store::whereMonth('created_at', '=', $i)->count();
                    array_push($dataPoints, $count);
                }
                break;
        }
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataPoints,
                    'borderColor' => '#E53E3E', // Color for the line
                    'backgroundColor' => 'rgba(229, 62, 62, 0.1)', // Color for the area under the line
                ]
            ]
        ]);
    }

    public function getServiceCostDistribution(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all');

        $labels = [];
        $dataPoints = [];

        switch ($timeframe) {
            case 'week':
                $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $startOfWeek = Carbon::now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $sum = Order::whereDate('created_at', '=', $startOfWeek->copy()->addDays($i))
                        ->sum('service_total');
                    array_push($dataPoints, $sum);
                }
                break;

            case 'month':
                $labels = [];
                for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
                    $labels[] = "Day $i";
                    $sum = Order::whereDate('created_at', '=', Carbon::now()->startOfMonth()->addDays($i - 1))
                        ->sum('service_total');
                    array_push($dataPoints, $sum);
                }
                break;

            case '3months':
                $labels = ['Month 1', 'Month 2', 'Month 3'];
                for ($i = 2; $i >= 0; $i--) {
                    $sum = Order::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)
                        ->sum('service_total');
                    array_push($dataPoints, $sum);
                }
                break;

            case '6months':
                $labels = [];
                for ($i = 5; $i >= 0; $i--) {
                    $monthName = Carbon::now()->subMonths($i)->format('F');
                    $labels[] = $monthName;
                    $sum = Order::whereMonth('created_at', '=', Carbon::now()->subMonths($i)->month)
                        ->sum('service_total');
                    array_push($dataPoints, $sum);
                }
                break;

            default:
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                for ($i = 1; $i <= 12; $i++) {
                    $sum = Order::whereMonth('created_at', '=', $i)
                        ->sum('service_total');
                    array_push($dataPoints, $sum);
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataPoints,
                    'borderColor' => '#3B82F6', // Color for the line
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)', // Color for the area under the line
                ]
            ]
        ]);
    }

    public function getPlatformRatingDistribution(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all');

        $labels = [];
        $dataPointsWeb = [];  // ratings for the 'web' platform
        $dataPointsApp = [];  // ratings for the 'app' platform

        switch ($timeframe) {
            case 'week':
                $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $startOfWeek = Carbon::now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $dataPointsWeb[] = PlatformRating::whereDate('created_at', '=', $date)->where('platform', 'web')->avg('rating');
                    $dataPointsApp[] = PlatformRating::whereDate('created_at', '=', $date)->where('platform', 'app')->avg('rating');
                }
                break;

            case 'month':
                for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
                    $date = Carbon::now()->startOfMonth()->addDays($i - 1);
                    $labels[] = "Day $i";
                    $dataPointsWeb[] = PlatformRating::whereDate('created_at', '=', $date)->where('platform', 'web')->avg('rating');
                    $dataPointsApp[] = PlatformRating::whereDate('created_at', '=', $date)->where('platform', 'app')->avg('rating');
                }
                break;

            case '3months':
                $labels = ['Month 1', 'Month 2', 'Month 3'];
                for ($i = 2; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $dataPointsWeb[] = PlatformRating::whereMonth('created_at', '=', $month->month)->where('platform', 'web')->avg('rating');
                    $dataPointsApp[] = PlatformRating::whereMonth('created_at', '=', $month->month)->where('platform', 'app')->avg('rating');
                }
                break;

            case '6months':
                $labels = [];
                for ($i = 5; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $monthName = $month->format('F');
                    $labels[] = $monthName;
                    $dataPointsWeb[] = PlatformRating::whereMonth('created_at', '=', $month->month)->where('platform', 'web')->avg('rating');
                    $dataPointsApp[] = PlatformRating::whereMonth('created_at', '=', $month->month)->where('platform', 'app')->avg('rating');
                }
                break;

            default:  // all (yearly distribution)
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                for ($i = 1; $i <= 12; $i++) {
                    $dataPointsWeb[] = PlatformRating::whereMonth('created_at', '=', $i)->where('platform', 'web')->avg('rating');
                    $dataPointsApp[] = PlatformRating::whereMonth('created_at', '=', $i)->where('platform', 'app')->avg('rating');
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Web',
                    'data' => $dataPointsWeb,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'App',
                    'data' => $dataPointsApp,
                    'borderColor' => '#48BB78',
                    'backgroundColor' => 'rgba(72, 187, 120, 0.1)',
                ]
            ]
        ]);
    }

    public function getUserStatistics()
    {
        // Fetch user statistics from your database or wherever your data is stored.

        // Count total users.
        $currentUsersCount = User::count();

        // Count users who registered before one month ago.
        $oneMonthAgo = Carbon::now()->subMonth();
        $previousUsersCount = User::where('created_at', '<', $oneMonthAgo)->count();

        // Calculate the percentage difference.
        $percentageDifference = 0;
        if ($previousUsersCount > 0) {  // Avoid division by zero.
            $percentageDifference = (($currentUsersCount - $previousUsersCount) / $previousUsersCount) * 100;
        }

        return response()->json([
            'currentUsersCount' => $currentUsersCount,
            'percentageDifference' => $percentageDifference
        ]);
    }
}
