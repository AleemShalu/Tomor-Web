<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Chart;
use App\Models\BranchVisitor; // Ensure this is the correct model

class VisitorsChart extends ChartWidget
{
    protected static ?string $heading = 'Visitor Statistics';

    protected function getData(): array
    {
        // Replace with actual data fetching logic
        $data = BranchVisitor::select('created_at') // Adjust fields as needed
        ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->date)->format('M Y'); // Group by month and year
            });

        return [
            'labels' => $data->keys(),
            'datasets' => [
                [
                    'label' => 'Visitors',
                    'data' => $data->map->count()->values(),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // This can be 'line', 'pie', etc., based on your requirement
    }
}
