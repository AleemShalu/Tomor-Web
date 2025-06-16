<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderRatingsChart extends ChartWidget
{
    protected static ?string $heading = 'Order Ratings by Type';

    /**
     * Get the data to be displayed in the chart.
     *
     * @return array
     */
    protected function getData(): array
    {
        // Fetch rating types and their average ratings
        $ratings = DB::table('order_ratings')
            ->select('order_rating_types.code', DB::raw('AVG(order_ratings.rating) as average_rating'))
            ->join('order_rating_types', 'order_ratings.order_rating_type_id', '=', 'order_rating_types.id')
            ->groupBy('order_rating_types.code')
            ->get();

        // Prepare chart labels and data
        $labels = $ratings->pluck('code')->toArray();
        $data = $ratings->pluck('average_rating')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Average Rating',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get the type of chart.
     *
     * @return string
     */
    protected function getType(): string
    {
        return 'bar'; // You can change this to other types like 'line', 'pie', etc.
    }
}
