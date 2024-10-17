<?php

namespace App\Filament\Resources\TiendaResource\Pages;

use App\Filament\Resources\TiendaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTienda extends CreateRecord
{
    protected static string $resource = TiendaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tienda Creada Exitosamente';
    }
}
