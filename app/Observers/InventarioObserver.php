<?php

namespace App\Observers;

use App\Models\Inventario;
use App\Models\User;
use Filament\Notifications\Notification;

class InventarioObserver
{
    /**
     * Handle the Inventario "created" event.
     */
    public function created(Inventario $inventario): void
    {
        
    }

    /**
     * Handle the Inventario "updated" event.
     */
    public function updated(Inventario $inventario): void
    {
        if ($inventario->stock < 10) {
            // Obtener a todos los usuarios (o filtrar por rol si es necesario)
            $users = User::all(); // Puedes ajustar este query para filtrar por rol si solo quieres notificar a algunos usuarios

            foreach ($users as $user) {
                // Enviar la notificación a cada usuario
                Notification::make()
                    ->title('Stock Bajo')
                    ->body('El producto ' . $inventario->producto->nombre . ' tiene un stock bajo de ' . $inventario->stock . ' unidades.')
                    ->sendToDatabase($user); // Notificación a la base de datos para cada usuario
            }
        }
    }

    /**
     * Handle the Inventario "deleted" event.
     */
    public function deleted(Inventario $inventario): void
    {
        //
    }

    /**
     * Handle the Inventario "restored" event.
     */
    public function restored(Inventario $inventario): void
    {
        //
    }

    /**
     * Handle the Inventario "force deleted" event.
     */
    public function forceDeleted(Inventario $inventario): void
    {
        //
    }
}
