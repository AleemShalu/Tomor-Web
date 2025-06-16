<?php

namespace App\Filament\Widgets;

use App\Models\BranchVisitor;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stores_count = Store::all()->count();
        $users_count = User::all()->count();
        $orders_count = Order::all()->count();
        $visitors_count = BranchVisitor::all()->count();
        return [
            Stat::make('Stores', $stores_count),
            Stat::make('Users', $users_count),
            Stat::make('Orders', $orders_count),
            Stat::make('Visitors', $visitors_count),
        ];
    }
}
