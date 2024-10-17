<?php

namespace App\Policies;

use App\Models\Tienda;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TiendaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('Ver tiendas')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tienda $tienda)
    {
        if ($user->hasPermissionTo('Ver tiendas')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('Crear tiendas')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tienda $tienda)
    {
        if ($user->hasPermissionTo('Editar tiendas')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tienda $tienda)
    {
        if ($user->hasPermissionTo('Eliminar tiendas')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tienda $tienda)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tienda $tienda)
    {
        //
    }
}
