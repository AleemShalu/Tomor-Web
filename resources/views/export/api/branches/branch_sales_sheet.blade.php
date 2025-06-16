<table>
    <thead>
        <tr>
            <td colspan="4" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Title: {{ ' ' . ucwords($report_title) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="nowrap" style="white-space: nowrap; text-align: left;">
                Store: {{ $store->commercial_name_en }} ({{ $store->short_name_en }})
            </td>
        </tr>
        <tr>
            <td colspan="4" class="nowrap" style="white-space: nowrap; text-align: left;">
                Branch: {{ $branch->name_en }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Created by: {{ ' ' . $user->name }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="nowrap"  style="white-space: nowrap; text-align: left;">
                Created at: {{ ' ' . Carbon\Carbon::now(request('timezone')) . ' ' . request('timezone') . ' '  . '(GMT' . get_request_timezone_diff_hours() . ')'}}
            </td>
        </tr>
        <tr>
            <th class="nowrap" style="text-align:center">#</th>
            <th class="nowrap" style="text-align:center">Orders Count</th>
            <th class="nowrap" style="text-align:center">Sales Amount</th>
            <th class="nowrap" style="text-align:center">Rating</th>
        </tr>
    </thead>
    <tbody>
    @php
        $sum_report_orders_count_total = 0;
        $sum_report_sales_amount_total = 0;
        $avg_report_rating_total = 0;
    @endphp
    @isset ($branch_sales)
        @php $i = 0; $branchSalesData = $branch_sales['branch_stats']; @endphp
        <tr>
            <td style="white-space: nowrap; text-align: center;"> {{ $i + 1 }} </td>
            <td style="white-space: nowrap; text-align: center;"> {{ $branchSalesData['orders_by_branch_count'] }} </td>
            <td style="white-space: nowrap; text-align: right;"> {{ formatPrice($branchSalesData['orders_by_branch_sales'], config('app.currency')) }} </td>
            <td style="white-space: nowrap; text-align: center;"> {{ $branchSalesData['branch_ratings_avg'] }} </td>
        </tr>
        @php
            $sum_report_orders_count_total += (float) $branchSalesData['orders_by_branch_count'];
            $sum_report_sales_amount_total += (float) $branchSalesData['orders_by_branch_sales'];
            $avg_report_rating_total += (float) $branchSalesData['branch_ratings_avg'];
        @endphp
    @endisset
    <tr>
        <td colspan="1" class="nowrap" style="text-align:right; background-color:#ebebe0">
            <strong></strong>
        </td>
        <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color:#ebebe0;">
            {{ $sum_report_orders_count_total }}
        </td>
        <td style="white-space: nowrap; text-align: right; font-weight: bold; background-color:#ebebe0;">
            {{ formatPrice($sum_report_sales_amount_total, config('app.currency')) }}
        </td>
        <td style="white-space: nowrap; text-align: center; font-weight: bold; background-color:#ebebe0;">
            {{ $avg_report_rating_total }}
        </td>
    </tr>
    </tbody>
</table>
