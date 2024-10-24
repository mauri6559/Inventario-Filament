<?php

namespace App\Filament\Widgets;

use App\Models\Venta;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VentasChart extends ChartWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Ventas';

    protected function getData(): array
    {
        $data = Trend::model(Venta::class)
        ->between(
            start: now()->subMonths(6),
            end: now(),
        )
        ->perMonth()
        ->count();
        $colors = [
            '#FF6384', // Color 1
            '#36A2EB', // Color 2
            '#FFCE56', // Color 3
            '#4BC0C0', // Color 4
            '#9966FF', // Color 5
            '#FF9F40', // Color 6
        ];
        return [
            'datasets' => [
                [
                    'label' => 'Ventas Realizadas',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => $colors,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
