<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['id_tienda', 'id_proveedor', 'total'];

    // Relación con Tienda
    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function pedidoProducto(): HasMany
    {
        return $this->hasMany(Pedido_Productos::class, 'id_pedido');
    }
}
