<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevos Clientes', Cliente::count())
                ->description('Nuevos clientes que se crearon')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('success'),
            Stat::make('Nuevos Productos', Cliente::count())
                ->description('Nuevos productos ingresados')
                ->descriptionIcon('heroicon-o-squares-plus', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('info'),
            Stat::make('Sucursales', Cliente::count())
                ->description('Sucursales registradas')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning')
        ];
    }
}
