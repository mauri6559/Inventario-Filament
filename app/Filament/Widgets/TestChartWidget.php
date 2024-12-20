<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TestChartWidget extends ChartWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Clientes agregados';

    protected function getData(): array
    {
        $data = Trend::model(Cliente::class)
        ->between(
            start: now()->subMonths(6),
            end: now(),
        )
        ->perMonth()
        ->count();
        $colors = [
            '#FF6384', // Color 1
            '#4BC0C0', // Color 4
            '#9966FF', // Color 5
            '#FF9F40', // Color 6
        ];
        //dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Clientes agregados',
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
