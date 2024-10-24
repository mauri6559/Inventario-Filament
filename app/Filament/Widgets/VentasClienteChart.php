<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Venta;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VentasClienteChart extends ChartWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Ventas por Cliente';

    protected function getData(): array
    {
        $data = Venta::select(DB::raw('id_cliente, COUNT(*) as total_ventas'))
            ->whereBetween('created_at', [now()->subMonths(6), now()])
            ->groupBy('id_cliente')
            ->get();

        // Transformamos los datos para que sean compatibles con el gráfico
        $labels = $data->map(fn ($item) => Cliente::find($item->id_cliente)->razon_social); // Asegúrate de que "nombre" sea el campo correcto en el modelo Cliente
        $ventas = $data->map(fn ($item) => $item->total_ventas);

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
                    'label' => 'Ventas por Cliente',
                    'data' => $ventas,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
