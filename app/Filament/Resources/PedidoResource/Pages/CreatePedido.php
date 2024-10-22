<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\Inventario;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pedido creado exitosamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Aquí puedes manipular los datos antes de crear el registro
        return $data;
    }

    protected function afterCreate(): void
    {
        // Recuperar los productos de la venta
        $productosPedido = $this->record->pedidoProducto; 

        foreach ($productosPedido as $productoPedido) {
            // Obtener el inventario correspondiente de la tienda y producto
            $inventario = Inventario::where('id_tienda', $this->record->id_tienda)
                                    ->where('id_producto', $productoPedido->id_producto)
                                    ->first();

            // Reducir el stock
            if ($inventario) {
                $inventario->stock += $productoPedido->cantidad;
                $inventario->save();  // Guardar el nuevo valor de stock
            } else {
                Inventario::create([
                    'id_tienda' => $this->record->id_tienda,
                    'id_producto' => $productoPedido->id_producto,
                    'stock' => $productoPedido->cantidad, // Establecer el stock inicial
                ]);
            }
        }
    }
}
