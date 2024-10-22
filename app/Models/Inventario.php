<?php

namespace App\Models;

use App\Observers\InventarioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InventarioObserver::class])]
class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock',
        'id_tienda',
        'id_producto',
    ];

    public function tienda() 
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function producto() 
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
