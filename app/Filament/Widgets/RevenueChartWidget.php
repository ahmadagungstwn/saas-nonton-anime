<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\CoinTopUp;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan 7 Hari Terakhir';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = collect();
        $labels = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels->push($date->format('d M Y'));

            $revenue = CoinTopUp::where('status', 'success')
                ->whereDate('created_at', $date)
                ->sum('amount'); // <= ini penting!

            $data->push($revenue);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data->toArray(),
                    'borderColor' => 'rgb(53, 162, 235)',
                    'backgroundColor' => 'rgba(53, 162, 235, 0.5)',
                    'fill' => true,
                    'tension' => 0.1,
                ]
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) {return 'Rp ' + value.toLocaleString('id-ID');}"
                    ]
                ],
            ],
        ];
    }
}
