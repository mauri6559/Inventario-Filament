<?php

namespace App\Filament\Exports;

use App\Models\Cliente;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClienteExporter extends Exporter
{
    protected static ?string $model = Cliente::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),  // Esto sigue funcionando correctamente
            ExportColumn::make('razon_social'),  // Aquí se debe usar el nombre de la columna en la base de datos
            ExportColumn::make('email'),  // Igual para el cliente
            ExportColumn::make('direccion'),  // Igual para el cliente
            ExportColumn::make('telefono')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your cliente export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
