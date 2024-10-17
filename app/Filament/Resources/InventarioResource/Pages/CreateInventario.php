<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use App\Models\Inventario;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventario extends CreateRecord
{
    protected static string $resource = InventarioResource::class;

    protected function handleRecordCreation(array $data): Inventario
    {
        // Verifica si ya existe un inventario con el mismo producto y tienda
        $inventarioExistente = Inventario::where('id_producto', $data['id_producto'])
            ->where('id_tienda', $data['id_tienda'])
            ->first();

        if ($inventarioExistente) {
            // Si ya existe, simplemente incrementa el stock
            $inventarioExistente->stock += $data['stock'];
            $inventarioExistente->save();

            return $inventarioExistente;
        }

        // Si no existe, crea uno nuevo
        return Inventario::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Inventario Creado Exitosamente';
    }
}
