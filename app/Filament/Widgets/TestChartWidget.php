<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TestChartWidget extends ChartWidget
{
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

        //dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Clientes agregados',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
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
