<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OrderRatingsChart;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\VisitorsChart;
use Filament\Pages\Page;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;

class SuperAdminDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.super-admin-dashboard';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    protected function getWidgets(): array
    {
        return [
            StatsOverview::class,
            OrdersChart::class,
            VisitorsChart::class,
            OrderRatingsChart::class,
        ];
    }
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }
}
