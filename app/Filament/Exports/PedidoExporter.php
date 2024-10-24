<?php

namespace App\Filament\Exports;

use App\Models\Pedido;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PedidoExporter extends Exporter
{
    protected static ?string $model = Pedido::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),  // Sigue exportando el ID del pedido
            ExportColumn::make('id_tienda')  // Exportar el nombre de la tienda
                ->label('Nombre de la Tienda')
                ->formatStateUsing(function (Pedido $record) {
                    return $record->tienda ? $record->tienda->nombre : 'N/A';  // Formateamos el valor con el nombre de la tienda
                }),
            ExportColumn::make('id_proveedor')  // Exportar el nombre del proveedor
                ->label('Proveedor')
                ->formatStateUsing(function (Pedido $record) {
                    return $record->proveedor ? $record->proveedor->razon_social : 'N/A';  // Formateamos el valor con la razón social del proveedor
                }),
            ExportColumn::make('total'),  // Exporta el total del pedido
            ExportColumn::make('fecha'),  // Exporta la fecha del pedido
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pedido export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
