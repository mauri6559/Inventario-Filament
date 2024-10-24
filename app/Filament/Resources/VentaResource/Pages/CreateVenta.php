<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use App\Models\Inventario;
use App\Models\Venta;
use App\Models\Venta_Productos;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Venta Registrada Exitosamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Aquí puedes manipular los datos antes de crear el registro
        return $data;
    }

    protected function afterCreate(): void
    {
        // Recuperar los productos de la venta
        $productosVenta = $this->record->ventaProductos; 

        foreach ($productosVenta as $productoVenta) {
            // Obtener el inventario correspondiente de la tienda y producto
            $inventario = Inventario::where('id_tienda', $this->record->id_tienda)
                                    ->where('id_producto', $productoVenta->id_producto)
                                    ->first();

            // Reducir el stock
            if ($inventario) {
                $inventario->stock -= $productoVenta->cantidad;
                $inventario->save();  // Guardar el nuevo valor de stock
            }
        }
    }

}
