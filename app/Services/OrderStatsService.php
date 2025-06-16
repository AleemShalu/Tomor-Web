<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderStatsService
{
    public function countOrdersByWeekdayForEmployee($request)
    {
        $ordersCountPerDayOfWeek = $this->initializeWeekdayCounts();

        $orders = Order::when($request->employee_id || $request->store_branch_id, function ($query) use ($request) {
            $query->where(function ($subquery) use ($request) {
                if ($request->employee_id) {
                    $subquery->whereHas('employee', function ($q) use ($request) {
                        $q->where('id', $request->employee_id);
                    });
                }
                if ($request->store_branch_id) {
                    $subquery->whereHas('store_branch', function ($q) use ($request) {
                        $q->where('id', $request->store_branch_id);
                    });
                }
            });
        })->selectRaw($this->covertOrderDateToLocalTimeZone() . ' AS order_date')->get();

        foreach ($orders as $order) {
            $dayOfWeekName = Carbon::parse($order->order_date)->format('l');
            if (isset($ordersCountPerDayOfWeek[$dayOfWeekName])) {
                $ordersCountPerDayOfWeek[$dayOfWeekName]++;
            }
        }

        return $this->sortWeekdayCounts($ordersCountPerDayOfWeek);
    }

    public function countOrdersByWeeksForEmployee($request)
    {
        $currentDate = Carbon::now($request->timezone);
        $startOfWeek = $currentDate->copy()->subWeeks(3)->startOfWeek();

        $weekCounts = $this->initializeWeekCounts();

        for ($i = 1; $i <= 4; $i++) {
            $weekStartDate = $startOfWeek->copy()->addWeeks($i - 1)->subDay();
            $weekEndDate = $startOfWeek->copy()->addWeeks($i - 1)->endOfWeek()->subDay();

            $orders = Order::when($request->employee_id || $request->store_branch_id, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    if ($request->employee_id) {
                        $subquery->whereHas('employee', function ($q) use ($request) {
                            $q->where('id', $request->employee_id);
                        });
                    }
                    if ($request->store_branch_id) {
                        $subquery->whereHas('store_branch', function ($q) use ($request) {
                            $q->where('id', $request->store_branch_id);
                        });
                    }
                });
            })->whereBetween(DB::raw($this->covertOrderDateToLocalTimeZone()), [$weekStartDate, $weekEndDate])
                ->count();

            $weekCounts["Week $i"] = $orders;
        }

        return $weekCounts;
    }

    public function countOrdersByThreeMonthsForEmployee($request)
    {
        $currentDate = Carbon::now($request->timezone);
        $startOfFirstMonth = $currentDate->copy()->subMonths(2)->startOfMonth();

        $monthCounts = [];

        for ($i = 0; $i < 3; $i++) {
            $startOfMonth = $startOfFirstMonth->copy()->addMonths($i);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $orders = Order::when($request->employee_id || $request->store_branch_id, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    if ($request->employee_id) {
                        $subquery->whereHas('employee', function ($q) use ($request) {
                            $q->where('id', $request->employee_id);
                        });
                    }
                    if ($request->store_branch_id) {
                        $subquery->whereHas('store_branch', function ($q) use ($request) {
                            $q->where('id', $request->store_branch_id);
                        });
                    }
                });
            })->whereBetween(DB::raw($this->covertOrderDateToLocalTimeZone()), [$startOfMonth, $endOfMonth])
                ->count();

            $monthName = $startOfMonth->format('F');
            $monthCounts[$monthName] = $orders;
        }

        return $monthCounts;
    }

    private function initializeWeekdayCounts()
    {
        return [
            "Saturday" => 0,
            "Sunday" => 0,
            "Monday" => 0,
            "Tuesday" => 0,
            "Wednesday" => 0,
            "Thursday" => 0,
            "Friday" => 0,
        ];
    }

    private function sortWeekdayCounts($ordersCountPerDayOfWeek)
    {
        $daysOrder = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        $sortedData = [];

        foreach ($daysOrder as $day) {
            $sortedData[$day] = $ordersCountPerDayOfWeek[$day];
        }

        return $sortedData;
    }

    private function initializeWeekCounts()
    {
        return [
            "Week 1" => 0,
            "Week 2" => 0,
            "Week 3" => 0,
            "Week 4" => 0,
        ];
    }

    private function covertOrderDateToLocalTimeZone() {
        $timezoneDifHours = get_request_timezone_diff_hours();
        return "CONVERT_TZ(order_date, '+00:00', '$timezoneDifHours')";
    }
}
