<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\User;
use App\Models\Venta;
use App\Models\Venta_Productos;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
    
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    
    {

        $gananciaBruta = Venta_Productos::join('productos', 'venta__productos.id_producto', '=', 'productos.id')
            ->selectRaw('SUM((venta__productos.precio_unitario - productos.precio_unit_compra) * venta__productos.cantidad) as ganancia_bruta')
            ->first()
            ->ganancia_bruta;

        // Consulta para la ganancia neta (puedes adaptarla si hay más costos a considerar)
        $gananciaNeta = $gananciaBruta; // Si no hay costos adicionales, igualamos neta a bruta

        return [
            Stat::make('Nuevos Clientes', Cliente::count())
                ->description('Nuevos clientes que se crearon')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('success'),
            Stat::make('Nuevos Productos', Producto::count())
                ->description('Nuevos productos ingresados')
                ->descriptionIcon('heroicon-o-squares-plus', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('info'),
            Stat::make('Sucursales', Tienda::count())
                ->description('Sucursales registradas')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning'),
            Stat::make('Ventas', Venta::count())
                ->description('Ventas Realizadas')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning'),
            Stat::make('Pedidos', Pedido::count())
                ->description('Pedidos Realizados')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('info'),
            Stat::make('Ganancia Bruta', number_format($gananciaBruta, 2))
                ->description('Ganancia obtenida por productos vendidos')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->color('success'),
            Stat::make('Empleados', User::count())
                ->description('Empleados Registrados')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning'),

            /*Stat::make('Ganancia Neta', number_format($gananciaNeta, 2))
                ->description('Ganancia neta calculada')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->color('success'),*/
        ];
    }
}
