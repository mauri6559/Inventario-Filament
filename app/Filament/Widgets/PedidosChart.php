<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PedidosChart extends ChartWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Pedidos';

    protected function getData(): array
    {
        $data = Trend::model(Pedido::class)
        ->between(
            start: now()->subMonths(6),
            end: now(),
        )
        ->perMonth()
        ->count();
        $colors = [
            '#FF9F40', // Color 6
            '#36A2EB', // Color 2
            '#4BC0C0', // Color 4
            '#FFCE56', // Color 3
            '#FF6384', // Color 1
            '#9966FF', // Color 5
        ];
        return [
            'datasets' => [
                [
                    'label' => 'Pedidos Realizados',
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
