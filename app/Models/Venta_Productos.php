<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta_Productos extends Model
{
    use HasFactory;

    protected $fillable = ['id_venta', 'id_producto', 'cantidad', 'precio_unitario'];

    // Relación con Venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}