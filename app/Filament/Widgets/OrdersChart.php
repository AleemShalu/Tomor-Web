<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';

    protected function getData(): array
    {
        $orders = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $orders->pluck('date')->toArray();
        $data = $orders->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
