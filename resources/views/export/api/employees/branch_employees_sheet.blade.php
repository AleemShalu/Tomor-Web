<table>
    <thead>
        <tr>
            <td colspan="8" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Title: {{ ' ' . ucwords($report_title) }}
            </td>
        </tr>
        <tr>
            <td colspan="8" class="nowrap" style="white-space: nowrap; text-align: left;">
                Store: {{ $store->commercial_name_en }} ({{ $store->short_name_en }})
            </td>
        </tr>
        <tr>
            <td colspan="8" class="nowrap" style="white-space: nowrap; text-align: left;">
                Branch: {{ $branch->name_en }}
            </td>
        </tr>
        <tr>
            <td colspan="8" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Created by: {{ ' ' . $user->name }}
            </td>
        </tr>
        <tr>
            <td colspan="8" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Created at: {{ ' ' . Carbon\Carbon::now(request('timezone')) . ' ' . request('timezone') . ' '  . '(GMT' . get_request_timezone_diff_hours() . ')'}}
            </td>
        </tr>
        <tr>
            <th class="nowrap" style="text-align:center">#</th>
            <th class="nowrap" style="text-align:center">Employee Name</th>
            <th class="nowrap" style="text-align:center">Email</th>
            <th class="nowrap" style="text-align:center">Phone</th>
            <th class="nowrap" style="text-align:center">Role</th>
            <th class="nowrap" style="text-align:center">Work Time</th>
            <th class="nowrap" style="text-align:center">Orders Count</th>
            <th class="nowrap" style="text-align:center">Sales Amount</th>
            <th class="nowrap" style="text-align:center">Rate</th>
        </tr>
    </thead>
    <tbody>
    @php
        $sum_report_orders_count_total = 0;
        $sum_report_sales_amount_total = 0;
        $avg_report_rate_total = 0;
    @endphp
    @forelse ($employees as $i => $employee)
        @php
            $worke_time = '00:00:00'; $order_count = 0; $sales_amount = 0;
            switch ($duration) {
                case 'daily':
                    $worke_time = $employee->get_total_hours_today();
                    $order_count = $employee->get_total_orders_today();
                    $sales_amount = $employee->get_total_cost_today();
                    break;
                case 'weekly':
                    $worke_time = $employee->get_total_hours_last_week();
                    $order_count = $employee->get_total_orders_last_week();
                    $sales_amount = $employee->get_total_cost_last_week();
                    break;
                case 'monthly':
                    $worke_time = $employee->get_total_hours_this_month();
                    $order_count = $employee->get_total_orders_this_month();
                    $sales_amount = $employee->get_total_cost_this_month();
                    break;
                case 'yearly':
                    $worke_time = $employee->get_total_hours_this_year();
                    $order_count = $employee->get_total_orders_this_year();
                    $sales_amount = $employee->get_total_cost_this_year();
                    break;
                default:
                    break;
            }
        @endphp
        <tr>
            <td style="white-space: nowrap; text-align: center;"> {{ $i + 1 }} </td>
            <td style="white-space: nowrap; text-align: left;">{{ $employee->name }}</td>
            <td style="white-space: nowrap; text-align: left;">{{ $employee->email }}</td>
            <td style="white-space: nowrap; text-align: left;">+{{ $employee->getUserPhoneNumber() }}</td>
            <td style="white-space: nowrap; text-align: center;">{{ $employee->roles->count() ? $employee->roles[0]->name_en : null }}</td>
            <td style="white-space: nowrap; text-align: center;"> {{ $worke_time }} </td>
            <td style="white-space: nowrap; text-align: center;"> {{ $order_count }} </td>
            <td style="white-space: nowrap; text-align: right;"> {{ formatPrice($sales_amount, config('app.currency')) }} </td>
            <td style="white-space: nowrap; text-align: center;"> {{ $employee->get_average_rating_employee() }} </td>
        </tr>
        @php
            $sum_report_orders_count_total += (float) $order_count;
            $sum_report_sales_amount_total += (float) $sales_amount;
            $avg_report_rate_total += $employee->get_average_rating_employee();
        @endphp
    @empty
    @endforelse
        <tr>
            <td colspan="6" class="nowrap" style="text-align:right; background-color:#ebebe0">
                <strong>Total</strong>
            </td>
            <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color:#ebebe0;">
                {{ $sum_report_orders_count_total }}
            </td>
            <td style="white-space: nowrap; text-align: right; font-weight: bold; background-color:#ebebe0;">
                {{ formatPrice($sum_report_sales_amount_total, config('app.currency')) }}
            </td>
            <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color:#ebebe0;">
                {{ $avg_report_rate_total / $employees->count() }}
            </td>
        </tr>
    </tbody>
</table>
