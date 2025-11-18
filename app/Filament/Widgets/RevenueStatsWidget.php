<?php

namespace App\Filament\Widgets;

use App\Models\CoinTopUp;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class RevenueStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $weekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        // Revenue Data
        $revenueToday = CoinTopUp::where('status', 'success')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $revenueYesterday = CoinTopUp::where('status', 'success')
            ->whereDate('created_at', $yesterday)
            ->sum('amount');

        $revenueThisWeek = CoinTopUp::where('status', 'success')
            ->whereBetween('created_at', [$weekStart, Carbon::now()])
            ->sum('amount');

        $revenueLastWeek = CoinTopUp::where('status', 'success')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('amount');

        $revenueThisMonth = CoinTopUp::where('status', 'success')
            ->whereBetween('created_at', [$monthStart, Carbon::now()])
            ->sum('amount');

        // Transaction Count
        $topUpsToday = CoinTopUp::where('status', 'success')
            ->whereDate('created_at', $today)
            ->count();

        $topUpsYesterday = CoinTopUp::where('status', 'success')
            ->whereDate('created_at', $yesterday)
            ->count();

        $topUpsThisWeek = CoinTopUp::where('status', 'success')
            ->whereBetween('created_at', [$weekStart, Carbon::now()])
            ->count();

        $topUpsLastWeek = CoinTopUp::where('status', 'success')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        // Average Transaction Value
        $avgTransactionToday = $topUpsToday > 0 ? $revenueToday / $topUpsToday : 0;
        $avgTransactionWeek = $topUpsThisWeek > 0 ? $revenueThisWeek / $topUpsThisWeek : 0;

        // Chart Data - Revenue 7 hari terakhir
        $revenueChart = collect(range(6, 0))->map(function ($day) {
            $date = Carbon::today()->subDays($day);
            return CoinTopUp::where('status', 'success')
                ->whereDate('created_at', $date)
                ->sum('amount');
        })->toArray();

        // Chart Data - Transaction count 7 hari terakhir
        $transactionChart = collect(range(6, 0))->map(function ($day) {
            $date = Carbon::today()->subDays($day);
            return CoinTopUp::where('status', 'success')
                ->whereDate('created_at', $date)
                ->count();
        })->toArray();

        // Chart Data - Weekly revenue trend
        $weeklyRevenueChart = collect(range(6, 0))->map(function ($week) {
            $startOfWeek = Carbon::now()->subWeeks($week)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($week)->endOfWeek();
            return CoinTopUp::where('status', 'success')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('amount');
        })->toArray();

        // Calculate percentage changes
        $revenueChange = $revenueYesterday > 0
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1)
            : ($revenueToday > 0 ? 100 : 0);

        $weekRevenueChange = $revenueLastWeek > 0
            ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
            : ($revenueThisWeek > 0 ? 100 : 0);

        $transactionChange = $topUpsYesterday > 0
            ? round((($topUpsToday - $topUpsYesterday) / $topUpsYesterday) * 100, 1)
            : ($topUpsToday > 0 ? 100 : 0);

        return [
            Stat::make('💰 Pendapatan Hari Ini', 'Rp ' . number_format($revenueToday, 0, ',', '.'))
                ->description(
                    $revenueChange >= 0
                        ? "↗ +{$revenueChange}% dari kemarin"
                        : "↘ {$revenueChange}% dari kemarin"
                )
                ->descriptionIcon(
                    $revenueChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($revenueChart)
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300 hover:scale-[1.02]',
                ]),

            Stat::make('📊 Pendapatan Minggu Ini', 'Rp ' . number_format($revenueThisWeek, 0, ',', '.'))
                ->description(
                    $weekRevenueChange >= 0
                        ? "↗ +{$weekRevenueChange}% dari minggu lalu"
                        : "↘ {$weekRevenueChange}% dari minggu lalu"
                )
                ->descriptionIcon(
                    $weekRevenueChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($weeklyRevenueChart)
                ->color($weekRevenueChange >= 0 ? 'info' : 'warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300 hover:scale-[1.02]',
                ]),

            Stat::make('🎯 Transaksi Berhasil', $topUpsToday . ' hari ini')
                ->description(
                    $transactionChange >= 0
                        ? "↗ +{$transactionChange}% • Avg: Rp " . number_format($avgTransactionToday, 0, ',', '.')
                        : "↘ {$transactionChange}% • Avg: Rp " . number_format($avgTransactionToday, 0, ',', '.')
                )
                ->descriptionIcon(
                    $transactionChange >= 0
                        ? 'heroicon-m-check-circle'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($transactionChart)
                ->color($transactionChange >= 0 ? 'primary' : 'gray')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300 hover:scale-[1.02]',
                ]),

            Stat::make('📈 Pendapatan Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description($topUpsThisWeek . ' transaksi minggu ini • Avg: Rp ' . number_format($avgTransactionWeek, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($weeklyRevenueChart)
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-all duration-300 hover:scale-[1.02]',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 4; // 4 kolom untuk layout yang lebih dinamis
    }
}
