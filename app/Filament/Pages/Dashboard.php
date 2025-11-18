<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\UserStatsWidget;
use App\Filament\Widgets\LatestTopUpWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\RevenueStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\PopularEpisodesWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            UserStatsWidget::class,
            RevenueStatsWidget::class,
            RevenueChartWidget::class,
            PopularEpisodesWidget::class,
            LatestTopUpWidget::class
        ];
    }
}
