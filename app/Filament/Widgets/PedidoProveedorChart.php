<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use App\Models\Proveedor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PedidoProveedorChart extends ChartWidget
{
    protected static ?int $sort = 0;
    protected static ?string $heading = 'Pedidos por Proveedor';

    protected function getData(): array
    {
        $data = Pedido::select(DB::raw('id_proveedor, COUNT(*) as total_pedidos'))
        ->whereBetween('created_at', [now()->subMonths(6), now()])
        ->groupBy('id_proveedor')
        ->get();

    // Transformamos los datos para que sean compatibles con el gráfico
    $labels = $data->map(fn ($item) => Proveedor::find($item->id_proveedor)->razon_social); // Asegúrate de que "nombre" sea el campo correcto en el modelo Proveedor
    $pedidos = $data->map(fn ($item) => $item->total_pedidos);

    $colors = [
        '#9966FF', // Color 5
        '#FFCE56', // Color 3
        '#FF6384', // Color 1
        '#4BC0C0', // Color 4
        '#FF9F40', // Color 6
        '#36A2EB', // Color 2
    ];
    return [
        'datasets' => [
            [
                'label' => 'Pedidos por Proveedor',
                'data' => $pedidos,
                'borderColor' => $colors,
            ],
        ],
        'labels' => $labels,
    ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
