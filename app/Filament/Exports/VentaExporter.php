<?php

namespace App\Filament\Exports;

use App\Models\Venta;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class VentaExporter extends Exporter
{
    protected static ?string $model = Venta::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),  // Esto sigue funcionando correctamente
            ExportColumn::make('id_tienda')  // Aquí se debe usar el nombre de la columna en la base de datos
                ->label('Nombre de la Tienda')  // Este es solo un título para el Excel
                ->formatStateUsing(function (Venta $record) {
                    return $record->tienda ? $record->tienda->nombre : 'N/A';  // Formateamos el valor
                }),
            ExportColumn::make('id_cliente')  // Igual para el cliente
                ->label('Cliente')
                ->formatStateUsing(function (Venta $record) {
                    return $record->cliente ? $record->cliente->razon_social : 'N/A';
                }),
            ExportColumn::make('total'),  // Esto sigue funcionando correctamente
            ExportColumn::make('fecha'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de las ventas se ha completado con ' . number_format($export->successful_rows) . ' ' . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
