<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['id_tienda', 'id_cliente', 'total', 'fecha'];

    // Relación con Tienda
    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Relación con VentaProducto
    public function ventaProductos(): HasMany
    {
        return $this->hasMany(Venta_Productos::class, 'id_venta');
    }
}
