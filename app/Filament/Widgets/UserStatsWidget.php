<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s'; // Auto refresh setiap 30 detik

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $weekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        // Data Hari Ini
        $newUsersToday = User::where('role', 'user')
            ->whereDate('created_at', $today)
            ->count();

        $newUsersYesterday = User::where('role', 'user')
            ->whereDate('created_at', $yesterday)
            ->count();

        // Data Minggu Ini
        $newUsersThisWeek = User::where('role', 'user')
            ->whereBetween('created_at', [$weekStart, Carbon::now()])
            ->count();

        $newUsersLastWeek = User::where('role', 'user')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        // Data Bulan Ini
        $newUsersThisMonth = User::where('role', 'user')
            ->whereBetween('created_at', [$monthStart, Carbon::now()])
            ->count();

        // Total Users
        $totalUsers = User::where('role', 'user')->count();

        // Chart Data (7 hari terakhir)
        $last7Days = collect(range(6, 0))->map(function ($day) {
            $date = Carbon::today()->subDays($day);
            return User::where('role', 'user')
                ->whereDate('created_at', $date)
                ->count();
        })->toArray();

        // Chart Data (7 minggu terakhir untuk total users)
        $weeklyGrowth = collect(range(6, 0))->map(function ($week) {
            $date = Carbon::now()->subWeeks($week)->endOfWeek();
            return User::where('role', 'user')
                ->where('created_at', '<=', $date)
                ->count();
        })->toArray();

        // Hitung persentase perubahan
        $todayChange = $newUsersYesterday > 0
            ? round((($newUsersToday - $newUsersYesterday) / $newUsersYesterday) * 100, 1)
            : ($newUsersToday > 0 ? 100 : 0);

        $weekChange = $newUsersLastWeek > 0
            ? round((($newUsersThisWeek - $newUsersLastWeek) / $newUsersLastWeek) * 100, 1)
            : ($newUsersThisWeek > 0 ? 100 : 0);

        return [
            Stat::make('🔥 Pengguna Baru Hari Ini', $newUsersToday)
                ->description(
                    $todayChange >= 0
                        ? "+{$todayChange}% dari kemarin"
                        : "{$todayChange}% dari kemarin"
                )
                ->descriptionIcon(
                    $todayChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($last7Days)
                ->color($todayChange >= 0 ? 'success' : 'danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('📈 Pengguna Minggu Ini', $newUsersThisWeek)
                ->description(
                    $weekChange >= 0
                        ? "+{$weekChange}% dari minggu lalu"
                        : "{$weekChange}% dari minggu lalu"
                )
                ->descriptionIcon(
                    $weekChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($last7Days)
                ->color($weekChange >= 0 ? 'info' : 'warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('👥 Total Pengguna', number_format($totalUsers, 0, ',', '.'))
                ->description("+" . number_format($newUsersThisMonth, 0, ',', '.') . " bulan ini")
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($weeklyGrowth)
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 3; // 3 kolom untuk responsive layout
    }
}
